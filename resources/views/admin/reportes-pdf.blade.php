<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Citas</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        h1, h2 { color: #831843; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #fbcfe8; color: #831843; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-pendiente { background-color: #fef08a; color: #854d0e; }
        .badge-confirmada { background-color: #bfdbfe; color: #1e3a8a; }
        .badge-completada { background-color: #bbf7d0; color: #14532d; }
        .badge-cancelada { background-color: #fecaca; color: #7f1d1d; }
    </style>
</head>
<body>
    <h1>AppSalon - Reporte de Citas</h1>
    <p class="text-center">Período: {{ $fechaInicio }} al {{ $fechaFin }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Servicios</th>
                <th>Estado</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalIngresos = 0; @endphp
            @foreach($citas as $cita)
                @if($cita->estado === 'completada')
                    @php $totalIngresos += $cita->total; @endphp
                @endif
                <tr>
                    <td>#{{ $cita->id }}</td>
                    <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                    <td>{{ $cita->hora }}</td>
                    <td>{{ $cita->usuario ? $cita->usuario->nombre_completo : 'N/A' }}</td>
                    <td>{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                    <td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
                    <td class="text-right">{{ $cita->total_formateado }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right font-bold">Total Ingresos (Completadas):</td>
                <td class="text-right font-bold">${{ number_format($totalIngresos, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
