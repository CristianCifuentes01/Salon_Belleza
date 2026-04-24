<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
            $query->whereDate('fecha', $request->fecha);
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

    /**
     * Reportes - Vista principal.
     */
    public function reportes(Request $request): View
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        // Reporte de citas por período
        $citasPorEstado = Cita::whereBetween('fecha', [$fechaInicio, $fechaFin])
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
            ->get();

        // Ingresos por día del período
        $ingresosPorDia = Cita::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->estado('completada')
            ->selectRaw('fecha, SUM(total) as ingresos, COUNT(*) as num_citas')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('admin.reportes', compact(
            'fechaInicio', 'fechaFin', 'citasPorEstado',
            'ingresosPorServicio', 'ingresosPorDia'
        ));
    }

    /**
     * Exportar citas a CSV.
     */
    public function exportarCSV(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $citas = Cita::with(['usuario', 'servicios'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
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
}
