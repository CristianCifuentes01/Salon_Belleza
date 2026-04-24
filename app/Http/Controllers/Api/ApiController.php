<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * GET /api/servicios
     * Listar todos los servicios activos.
     */
    public function servicios(): JsonResponse
    {
        $servicios = Servicio::activo()->orderBy('nombre')->get();

        return response()->json([
            'status' => 'success',
            'data' => $servicios,
            'total' => $servicios->count(),
        ]);
    }

    /**
     * GET /api/citas/usuario/{id}
     * Listar citas de un usuario específico.
     */
    public function citasUsuario(int $id): JsonResponse
    {
        // Verificar que el usuario autenticado puede ver estas citas
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

    /**
     * POST /api/citas
     * Crear una nueva cita.
     */
    public function crearCita(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
        ]);

        // Verificar disponibilidad
        $ocupado = Cita::where('fecha', $validated['fecha'])
            ->where('hora', $validated['hora'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->exists();

        if ($ocupado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Este horario ya está ocupado.',
            ], 422);
        }

        $servicios = Servicio::whereIn('id', $validated['servicios'])->get();
        $total = $servicios->sum('precio');

        $cita = Cita::create([
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'usuarioId' => auth()->id(),
            'total' => $total,
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

    /**
     * PUT /api/citas/{id}
     * Actualizar una cita existente.
     */
    public function actualizarCita(Request $request, int $id): JsonResponse
    {
        $cita = Cita::find($id);

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

        // Si cambian fecha/hora, verificar disponibilidad
        if (isset($validated['fecha']) || isset($validated['hora'])) {
            $fecha = $validated['fecha'] ?? $cita->fecha->format('Y-m-d');
            $hora = $validated['hora'] ?? $cita->hora;

            $ocupado = Cita::where('fecha', $fecha)
                ->where('hora', $hora)
                ->where('id', '!=', $cita->id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->exists();

            if ($ocupado) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Este horario ya está ocupado.',
                ], 422);
            }
        }

        $cita->update(collect($validated)->except('servicios')->toArray());

        if (isset($validated['servicios'])) {
            $cita->servicios()->sync($validated['servicios']);
            $total = Servicio::whereIn('id', $validated['servicios'])->sum('precio');
            $cita->update(['total' => $total]);
        }

        $cita->load('servicios');

        return response()->json([
            'status' => 'success',
            'message' => 'Cita actualizada exitosamente.',
            'data' => $cita,
        ]);
    }
}
