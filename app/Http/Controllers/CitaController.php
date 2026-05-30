<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitaRequest;
use App\Mail\CitaCreadaMail;
use App\Models\Cita;
use App\Models\Servicio;
use App\Services\DisponibilidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CitaController extends Controller
{
    public function __construct(private DisponibilidadService $disponibilidadService)
    {
    }

    public function index(): View
    {
        $citas = auth()->user()->citas()
            ->with('servicios')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('citas.index', compact('citas'));
    }

    public function create(): View
    {
        $servicios = Servicio::activo()->orderBy('nombre')->get();

        return view('citas.create', compact('servicios'));
    }

    public function store(CitaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (!$this->disponibilidadService->estaDisponible($validated['fecha'], $validated['hora'], $validated['servicios'])) {
            return back()->withErrors(['hora' => 'Este horario no esta disponible. Por favor selecciona otro.'])
                ->withInput();
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
        $cita->load('usuario', 'servicios');

        try {
            Mail::to(auth()->user()->email)->send(new CitaCreadaMail($cita));
        } catch (\Throwable $e) {
            Log::error('Error al enviar correo de cita: ' . $e->getMessage());
        }

        return redirect()->route('citas.index')
            ->with('success', 'Cita reservada exitosamente. Estado: Pendiente de confirmacion.');
    }

    public function show(Cita $cita): View
    {
        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }

        $cita->load('servicios', 'usuario');

        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita): View
    {
        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if (!in_array($cita->estado, ['pendiente', 'confirmada'])) {
            abort(403, 'Solo se pueden reprogramar citas pendientes o confirmadas.');
        }

        $cita->load('servicios');
        $servicios = Servicio::activo()->orderBy('nombre')->get();

        return view('citas.edit', compact('cita', 'servicios'));
    }

    public function update(CitaRequest $request, Cita $cita): RedirectResponse
    {
        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        if (!$this->disponibilidadService->estaDisponible($validated['fecha'], $validated['hora'], $validated['servicios'], $cita->id)) {
            return back()->withErrors(['hora' => 'Este horario no esta disponible. Por favor selecciona otro.'])
                ->withInput();
        }

        $servicios = Servicio::whereIn('id', $validated['servicios'])->get();

        $cita->update([
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'total' => $servicios->sum('precio'),
        ]);

        $cita->servicios()->sync($validated['servicios']);

        return redirect()->route('citas.index')
            ->with('success', 'Cita reprogramada exitosamente.');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $cita->update(['estado' => 'cancelada']);

        return redirect()->route('citas.index')
            ->with('success', 'Cita cancelada exitosamente.');
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
            'horas' => $this->disponibilidadService->horasDisponibles(
                $validated['fecha'],
                $validated['servicios'] ?? [],
                $validated['cita_id'] ?? null
            ),
        ]);
    }
}
