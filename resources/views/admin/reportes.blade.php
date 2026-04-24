<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">📊 Reportes</h2></x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filters -->
            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="block mt-1 rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Fecha Fin</label>
                        <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="block mt-1 rounded-lg border-gray-300 text-sm">
                    </div>
                    <button type="submit" class="btn-primary btn-sm">Generar Reporte</button>
                    <a href="{{ route('admin.reportes.csv', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn-secondary btn-sm">📥 Exportar CSV</a>
                </form>
            </div>

            <!-- Citas por estado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Citas por Estado</h3>
                    <canvas id="chartEstados" height="200"></canvas>
                </div>
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Resumen del Período</h3>
                    <div class="space-y-3">
                        @foreach(['pendiente' => 'yellow', 'confirmada' => 'blue', 'completada' => 'green', 'cancelada' => 'red'] as $estado => $color)
                        <div class="flex items-center justify-between p-3 bg-{{ $color }}-50 rounded-lg">
                            <span class="font-medium text-gray-700">{{ ucfirst($estado) }}</span>
                            <span class="text-lg font-bold text-{{ $color }}-700">{{ $citasPorEstado[$estado] ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ingresos por servicio -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ingresos por Servicio</h3>
                <div class="overflow-x-auto">
                    <table class="table-salon">
                        <thead><tr><th>Servicio</th><th>Citas</th><th>Ingresos</th></tr></thead>
                        <tbody>
                            @foreach($ingresosPorServicio as $serv)
                            <tr>
                                <td class="font-semibold">{{ $serv->nombre }}</td>
                                <td>{{ $serv->total_citas ?? 0 }}</td>
                                <td class="font-bold text-green-700">${{ number_format($serv->ingresos ?? 0, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
    @push('scripts')
    <script>
        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: ['Pendiente', 'Confirmada', 'Completada', 'Cancelada'],
                datasets: [{
                    data: [
                        {{ $citasPorEstado['pendiente'] ?? 0 }},
                        {{ $citasPorEstado['confirmada'] ?? 0 }},
                        {{ $citasPorEstado['completada'] ?? 0 }},
                        {{ $citasPorEstado['cancelada'] ?? 0 }}
                    ],
                    backgroundColor: ['#fbbf24', '#3b82f6', '#22c55e', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    </script>
    @endpush
</x-app-layout>
