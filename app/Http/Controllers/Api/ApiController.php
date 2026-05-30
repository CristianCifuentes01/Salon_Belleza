<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Servicio;
use App\Services\DisponibilidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(private DisponibilidadService $disponibilidadService)
    {
    }

    public function servicios(): JsonResponse
    {
        $servicios = Servicio::activo()->orderBy('nombre')->get();

        return response()->json([
            'status' => 'success',
            'data' => $servicios,
            'total' => $servicios->count(),
        ]);
    }

    public function disponibilidad(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'servicios' => 'sometimes|array',
            'servicios.*' => 'exists:servicios,id',
            'cita_id' => 'nullable|integer|exists:citas,id',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'fecha' => $validated['fecha'],
                'horas' => $this->disponibilidadService->horasDisponibles(
                    $validated['fecha'],
                    $validated['servicios'] ?? [],
                    $validated['cita_id'] ?? null
                ),
            ],
        ]);
    }

    public function docs(): JsonResponse
    {
        return response()->json([
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'AppSalon API',
                'version' => '1.0.0',
            ],
            'paths' => [
                '/api/servicios' => [
                    'get' => [
                        'summary' => 'Lista servicios activos',
                        'responses' => ['200' => ['description' => 'Servicios en JSON']],
                    ],
                ],
                '/api/disponibilidad' => [
                    'get' => [
                        'summary' => 'Consulta horarios disponibles',
                        'parameters' => [
                            ['name' => 'fecha', 'in' => 'query', 'required' => true],
                            ['name' => 'servicios[]', 'in' => 'query', 'required' => false],
                        ],
                        'responses' => ['200' => ['description' => 'Horas disponibles']],
                    ],
                ],
                '/api/citas' => [
                    'post' => [
                        'summary' => 'Crea una cita',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '201' => ['description' => 'Cita creada'],
                            '422' => ['description' => 'Horario ocupado o datos invalidos'],
                        ],
                    ],
                ],
                '/api/citas/{id}' => [
                    'put' => [
                        'summary' => 'Actualiza o reprograma una cita',
                        'security' => [['bearerAuth' => []]],
                        'responses' => ['200' => ['description' => 'Cita actualizada']],
                    ],
                ],
                '/api/citas/usuario/{id}' => [
                    'get' => [
                        'summary' => 'Lista citas de un usuario',
                        'security' => [['bearerAuth' => []]],
                        'responses' => ['200' => ['description' => 'Citas del usuario']],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => ['type' => 'http', 'scheme' => 'bearer'],
                ],
            ],
        ]);
    }

    public function citasUsuario(int $id): JsonResponse
    {
        if (auth()->id() !== $id && !auth()->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autorizado.',
            ], 403);
        }

        $citas = Cita::where('usuarioId', $id)
            ->with('servicios')
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $citas,
            'total' => $citas->count(),
        ]);
    }

    public function crearCita(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
        ]);

        if (!$this->disponibilidadService->estaDisponible($validated['fecha'], $validated['hora'], $validated['servicios'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Este horario no esta disponible.',
            ], 422);
        }

        $servicios = Servicio::whereIn('id', $validated['servicios'])->get();

        $cita = Cita::create([
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'usuarioId' => auth()->id(),
            'total' => $servicios->sum('precio'),
            'estado' => 'pendiente',
        ]);

        $cita->servicios()->attach($validated['servicios']);
        $cita->load('servicios');

        return response()->json([
            'status' => 'success',
            'message' => 'Cita creada exitosamente.',
            'data' => $cita,
        ], 201);
    }

    public function actualizarCita(Request $request, int $id): JsonResponse
    {
        $cita = Cita::with('servicios')->find($id);

        if (!$cita) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cita no encontrada.',
            ], 404);
        }

        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autorizado.',
            ], 403);
        }

        $validated = $request->validate([
            'fecha' => 'sometimes|date|after_or_equal:today',
            'hora' => 'sometimes|date_format:H:i',
            'estado' => 'sometimes|in:pendiente,confirmada,completada,cancelada',
            'servicios' => 'sometimes|array|min:1',
            'servicios.*' => 'exists:servicios,id',
        ]);

        if (isset($validated['fecha']) || isset($validated['hora']) || isset($validated['servicios'])) {
            $fecha = $validated['fecha'] ?? $cita->fecha->format('Y-m-d');
            $hora = $validated['hora'] ?? substr((string) $cita->hora, 0, 5);
            $servicios = $validated['servicios'] ?? $cita->servicios->pluck('id')->all();

            if (!$this->disponibilidadService->estaDisponible($fecha, $hora, $servicios, $cita->id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Este horario no esta disponible.',
                ], 422);
            }
        }

        $cita->update(collect($validated)->except('servicios')->toArray());

        if (isset($validated['servicios'])) {
            $cita->servicios()->sync($validated['servicios']);
            $cita->update([
                'total' => Servicio::whereIn('id', $validated['servicios'])->sum('precio'),
            ]);
        }

        $cita->load('servicios');

        return response()->json([
            'status' => 'success',
            'message' => 'Cita actualizada exitosamente.',
            'data' => $cita,
        ]);
    }
}
