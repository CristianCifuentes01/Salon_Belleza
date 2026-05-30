<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\SalonBloqueo;
use App\Models\SalonHorario;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Dashboard principal de administración.
     */
    public function dashboard(): View
    {
        $stats = [
            'citasHoy' => Cita::hoy()->count(),
            'citasPendientes' => Cita::estado('pendiente')->count(),
            'totalClientes' => User::where('admin', 0)->count(),
            'totalServicios' => Servicio::activo()->count(),
            'ingresosHoy' => Cita::hoy()->estado('completada')->sum('total'),
            'ingresosMes' => Cita::whereMonth('fecha', now()->month)
                                 ->whereYear('fecha', now()->year)
                                 ->estado('completada')
                                 ->sum('total'),
        ];

        $citasHoy = Cita::hoy()
            ->with(['usuario', 'servicios'])
            ->orderBy('hora')
            ->get();

        $citasRecientes = Cita::with(['usuario', 'servicios'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'citasHoy', 'citasRecientes'));
    }

    /**
     * Gestión de todas las citas.
     */
    public function citas(Request $request): View
    {
        $query = Cita::with(['usuario', 'servicios']);

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->estado($request->estado);
        }

        // Filtrar por fecha
        if ($request->filled('fecha')) {
            if ($request->input('vista') === 'semana') {
                $base = \Carbon\Carbon::parse($request->fecha);
                $query->whereBetween('fecha', [
                    $base->copy()->startOfWeek()->toDateString(),
                    $base->copy()->endOfWeek()->toDateString(),
                ]);
            } else {
                $query->whereDate('fecha', $request->fecha);
            }
        }

        // Búsqueda por nombre de usuario
        if ($request->filled('buscar')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->buscar}%")
                  ->orWhere('apellido', 'like', "%{$request->buscar}%")
                  ->orWhere('email', 'like', "%{$request->buscar}%");
            });
        }

        $citas = $query->orderBy('fecha', 'desc')
                       ->orderBy('hora', 'desc')
                       ->paginate(15);

        return view('admin.citas', compact('citas'));
    }

    /**
     * Cambiar el estado de una cita.
     */
    public function cambiarEstadoCita(Request $request, Cita $cita): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
        ]);

        $cita->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado de la cita actualizado a: ' . ucfirst($request->estado));
    }

    /**
     * Gestión de usuarios.
     */
    public function usuarios(): View
    {
        $usuarios = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Activar/Desactivar un usuario (toggle confirmado).
     */
    public function toggleUsuario(User $user): RedirectResponse
    {
        $user->update(['confirmado' => !$user->confirmado]);

        $estado = $user->confirmado ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$estado} exitosamente.");
    }

    /**
     * Gestión de servicios (listado admin).
     */
    public function servicios(): View
    {
        $servicios = Servicio::orderBy('nombre')->get();
        return view('admin.servicios', compact('servicios'));
    }

    public function horarios(): View
    {
        $horarios = SalonHorario::orderBy('dia_semana')->get();
        $bloqueos = SalonBloqueo::orderBy('fecha', 'desc')->orderBy('hora_inicio')->paginate(10);

        return view('admin.horarios', compact('horarios', 'bloqueos'));
    }

    public function actualizarHorarios(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'horarios' => 'required|array',
            'horarios.*.dia_semana' => 'required|integer|between:1,7',
            'horarios.*.hora_apertura' => 'nullable|date_format:H:i',
            'horarios.*.hora_cierre' => 'nullable|date_format:H:i',
            'horarios.*.activo' => 'sometimes|boolean',
        ]);

        foreach ($validated['horarios'] as $horario) {
            if (($horario['activo'] ?? false) && (!($horario['hora_apertura'] ?? null) || !($horario['hora_cierre'] ?? null) || $horario['hora_cierre'] <= $horario['hora_apertura'])) {
                return back()->withErrors(['horarios' => 'Cada dia activo debe tener apertura y cierre validos.'])->withInput();
            }

            SalonHorario::updateOrCreate(
                ['dia_semana' => $horario['dia_semana']],
                [
                    'hora_apertura' => $horario['hora_apertura'] ?? null,
                    'hora_cierre' => $horario['hora_cierre'] ?? null,
                    'activo' => (bool) ($horario['activo'] ?? false),
                ]
            );
        }

        return back()->with('success', 'Horarios de operacion actualizados.');
    }

    public function crearBloqueo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'nullable|required_with:hora_fin|date_format:H:i',
            'hora_fin' => 'nullable|required_with:hora_inicio|date_format:H:i|after:hora_inicio',
            'motivo' => 'nullable|string|max:255',
        ]);

        SalonBloqueo::create($validated);

        return back()->with('success', 'Bloqueo de disponibilidad creado.');
    }

    public function eliminarBloqueo(SalonBloqueo $bloqueo): RedirectResponse
    {
        $bloqueo->delete();

        return back()->with('success', 'Bloqueo eliminado.');
    }

    /**
     * Reportes - Vista principal.
     */
    public function reportes(Request $request): View
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $servicioId = $request->input('servicio_id');

        // Reporte de citas por período
        $citasPorEstado = Cita::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->when($servicioId, fn ($q) => $q->whereHas('servicios', fn ($sq) => $sq->where('servicios.id', $servicioId)))
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Reporte de ingresos por servicio
        $ingresosPorServicio = Servicio::withCount(['citas as total_citas' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('citas.fecha', [$fechaInicio, $fechaFin])
                  ->where('citas.estado', 'completada');
            }])
            ->withSum(['citas as ingresos' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('citas.fecha', [$fechaInicio, $fechaFin])
                  ->where('citas.estado', 'completada');
            }], 'citas.total')
            ->when($servicioId, fn ($q) => $q->where('id', $servicioId))
            ->get();

        // Ingresos por día del período
        $ingresosPorDia = Cita::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->estado('completada')
            ->when($servicioId, fn ($q) => $q->whereHas('servicios', fn ($sq) => $sq->where('servicios.id', $servicioId)))
            ->selectRaw('fecha, SUM(total) as ingresos, COUNT(*) as num_citas')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $servicios = Servicio::orderBy('nombre')->get();

        return view('admin.reportes', compact(
            'fechaInicio', 'fechaFin', 'citasPorEstado',
            'ingresosPorServicio', 'ingresosPorDia', 'servicios', 'servicioId'
        ));
    }

    /**
     * Exportar citas a CSV.
     */
    public function exportarCSV(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $servicioId = $request->input('servicio_id');

        $citas = Cita::with(['usuario', 'servicios'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->when($servicioId, fn ($q) => $q->whereHas('servicios', fn ($sq) => $sq->where('servicios.id', $servicioId)))
            ->orderBy('fecha')
            ->get();

        $filename = "reporte_citas_{$fechaInicio}_{$fechaFin}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($citas) {
            $file = fopen('php://output', 'w');
            // BOM para Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ID', 'Fecha', 'Hora', 'Cliente', 'Email', 'Servicios', 'Total', 'Estado']);

            foreach ($citas as $cita) {
                fputcsv($file, [
                    $cita->id,
                    $cita->fecha->format('d/m/Y'),
                    $cita->hora,
                    $cita->usuario ? $cita->usuario->nombre_completo : 'N/A',
                    $cita->usuario ? $cita->usuario->email : 'N/A',
                    $cita->servicios->pluck('nombre')->implode(', '),
                    $cita->total,
                    ucfirst($cita->estado),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar citas a PDF.
     */
    public function exportarPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $servicioId = $request->input('servicio_id');

        $citas = Cita::with(['usuario', 'servicios'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->when($servicioId, fn ($q) => $q->whereHas('servicios', fn ($sq) => $sq->where('servicios.id', $servicioId)))
            ->orderBy('fecha')
            ->get();

        $pdf = Pdf::loadView('admin.reportes-pdf', compact('citas', 'fechaInicio', 'fechaFin'));
        
        return $pdf->download("reporte_citas_{$fechaInicio}_{$fechaFin}.pdf");
    }
}
