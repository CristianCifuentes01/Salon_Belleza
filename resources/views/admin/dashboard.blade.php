<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">⚙️ Panel de Administración</h2></x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Citas Hoy</p>
                            <p class="text-3xl font-bold text-salon-700">{{ $stats['citasHoy'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-salon-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-salon-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Pendientes</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $stats['citasPendientes'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Clientes</p>
                            <p class="text-3xl font-bold text-blue-700">{{ $stats['totalClientes'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Ingresos Mes</p>
                            <p class="text-3xl font-bold text-green-700">${{ number_format($stats['ingresosMes'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.servicios.index') }}" class="card p-4 flex items-center space-x-3 hover:border-salon-300 border-2 border-transparent">
                    <div class="w-10 h-10 bg-salon-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-salon-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                    <span class="font-semibold text-gray-700">Servicios</span>
                </a>
                <a href="{{ route('admin.citas') }}" class="card p-4 flex items-center space-x-3 hover:border-salon-300 border-2 border-transparent">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <span class="font-semibold text-gray-700">Citas</span>
                </a>
                <a href="{{ route('admin.usuarios') }}" class="card p-4 flex items-center space-x-3 hover:border-salon-300 border-2 border-transparent">
                    <div class="w-10 h-10 bg-gold-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg></div>
                    <span class="font-semibold text-gray-700">Usuarios</span>
                </a>
                <a href="{{ route('admin.reportes') }}" class="card p-4 flex items-center space-x-3 hover:border-salon-300 border-2 border-transparent">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                    <span class="font-semibold text-gray-700">Reportes</span>
                </a>
            </div>

            <!-- Today's appointments -->
            <div class="card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Citas de Hoy</h3>
                    <a href="{{ route('admin.citas', ['fecha' => date('Y-m-d')]) }}" class="text-salon-600 hover:text-salon-800 text-sm font-medium">Ver todo →</a>
                </div>
                @if($citasHoy->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table-salon">
                        <thead><tr><th>Hora</th><th>Cliente</th><th>Servicios</th><th>Total</th><th>Estado</th><th>Acción</th></tr></thead>
                        <tbody>
                            @foreach($citasHoy as $cita)
                            <tr>
                                <td class="font-semibold">{{ $cita->hora }}</td>
                                <td>{{ $cita->usuario ? $cita->usuario->nombre_completo : 'N/A' }}</td>
                                <td class="text-xs">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                                <td class="font-semibold">{{ $cita->total_formateado }}</td>
                                <td><span class="badge badge-{{ $cita->estado_color }}">{{ ucfirst($cita->estado) }}</span></td>
                                <td class="flex items-center space-x-2">
                                    <a href="{{ route('citas.show', $cita) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Ver</a>
                                    <form method="POST" action="{{ route('admin.citas.estado', $cita) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <select name="estado" onchange="this.form.submit()" class="text-xs rounded border-gray-300 py-1">
                                            @foreach(['pendiente','confirmada','completada','cancelada'] as $e)
                                            <option value="{{ $e }}" {{ $cita->estado == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No hay citas programadas para hoy.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
