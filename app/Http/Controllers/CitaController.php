<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Http\Requests\CitaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CitaController extends Controller
{
    /**
     * Horario del salón (fijo).
     */
    private const HORA_APERTURA = 9;  // 9:00 AM
    private const HORA_CIERRE = 18;   // 6:00 PM
    private const INTERVALO_MINUTOS = 30;

    /**
     * Mostrar las citas del usuario autenticado.
     */
    public function index(): View
    {
        $citas = auth()->user()->citas()
            ->with('servicios')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('citas.index', compact('citas'));
    }

    /**
     * Mostrar el formulario para crear una nueva cita.
     */
    public function create(): View
    {
        $servicios = Servicio::activo()->orderBy('nombre')->get();
        return view('citas.create', compact('servicios'));
    }

    /**
     * Almacenar una nueva cita.
     */
    public function store(CitaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Verificar disponibilidad
        $citaExistente = Cita::where('fecha', $validated['fecha'])
            ->where('hora', $validated['hora'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->exists();

        if ($citaExistente) {
            return back()->withErrors(['hora' => 'Este horario ya está ocupado. Por favor selecciona otro.'])
                         ->withInput();
        }

        // Calcular total
        $servicios = Servicio::whereIn('id', $validated['servicios'])->get();
        $total = $servicios->sum('precio');

        // Crear la cita
        $cita = Cita::create([
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'usuarioId' => auth()->id(),
            'total' => $total,
            'estado' => 'pendiente',
        ]);

        // Asociar servicios
        $cita->servicios()->attach($validated['servicios']);

        return redirect()->route('citas.index')
            ->with('success', '¡Cita reservada exitosamente! Estado: Pendiente de confirmación.');
    }

    /**
     * Mostrar los detalles de una cita.
     */
    public function show(Cita $cita): View
    {
        // Verificar que la cita pertenece al usuario o es admin
        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }

        $cita->load('servicios', 'usuario');
        return view('citas.show', compact('cita'));
    }

    /**
     * Cancelar una cita.
     */
    public function destroy(Cita $cita): RedirectResponse
    {
        if ($cita->usuarioId !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $cita->update(['estado' => 'cancelada']);

        return redirect()->route('citas.index')
            ->with('success', 'Cita cancelada exitosamente.');
    }

    /**
     * API: Obtener horarios disponibles para una fecha.
     */
    public function disponibilidad(Request $request): JsonResponse
    {
        $request->validate(['fecha' => 'required|date|after_or_equal:today']);

        $fecha = $request->fecha;
        $diaSemana = date('N', strtotime($fecha)); // 1=Lun, 7=Dom

        // Domingo cerrado
        if ($diaSemana == 7) {
            return response()->json(['horas' => [], 'mensaje' => 'El salón no abre los domingos.']);
        }

        // Generar todos los horarios posibles
        $horasDisponibles = [];
        for ($h = self::HORA_APERTURA; $h < self::HORA_CIERRE; $h++) {
            for ($m = 0; $m < 60; $m += self::INTERVALO_MINUTOS) {
                $hora = sprintf('%02d:%02d', $h, $m);

                // Verificar si ya hay cita en ese horario
                $ocupado = Cita::where('fecha', $fecha)
                    ->where('hora', $hora)
                    ->whereIn('estado', ['pendiente', 'confirmada'])
                    ->exists();

                // Si es hoy, no mostrar horas que ya pasaron
                if ($fecha == date('Y-m-d')) {
                    $horaActual = date('H:i');
                    if ($hora <= $horaActual) {
                        continue;
                    }
                }

                if (!$ocupado) {
                    $horasDisponibles[] = $hora;
                }
            }
        }

        return response()->json(['horas' => $horasDisponibles]);
    }
}
