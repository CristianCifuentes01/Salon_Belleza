<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-salon-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome -->
            <div class="card p-8 bg-gradient-to-r from-salon-600 to-salon-800 text-white">
                <h3 class="text-2xl font-bold mb-2">¡Hola, {{ auth()->user()->nombre }}! 👋</h3>
                <p class="text-salon-200">Bienvenido a AppSalon. Gestiona tus citas y descubre nuestros servicios.</p>
                <div class="mt-4">
                    <a href="{{ route('citas.create') }}" class="btn-gold btn-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nueva Cita
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('servicios.index') }}" class="stat-card flex items-center space-x-4 hover:border-salon-300">
                    <div class="w-12 h-12 bg-salon-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-salon-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div><h4 class="font-semibold text-gray-800">Ver Servicios</h4><p class="text-sm text-gray-500">Nuestro catálogo</p></div>
                </a>
                <a href="{{ route('citas.index') }}" class="stat-card flex items-center space-x-4 hover:border-salon-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div><h4 class="font-semibold text-gray-800">Mis Citas</h4><p class="text-sm text-gray-500">Historial completo</p></div>
                </a>
                <a href="{{ route('profile.edit') }}" class="stat-card flex items-center space-x-4 hover:border-salon-300">
                    <div class="w-12 h-12 bg-gold-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div><h4 class="font-semibold text-gray-800">Mi Perfil</h4><p class="text-sm text-gray-500">Editar datos</p></div>
                </a>
            </div>

            <!-- Recent Appointments -->
            <div class="card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Mis Citas Recientes</h3>
                    <a href="{{ route('citas.index') }}" class="text-salon-600 hover:text-salon-800 text-sm font-medium">Ver todas →</a>
                </div>
                @if($misCitas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table-salon">
                        <thead><tr><th>Fecha</th><th>Hora</th><th>Servicios</th><th>Total</th><th>Estado</th></tr></thead>
                        <tbody>
                            @foreach($misCitas as $cita)
                            <tr>
                                <td class="font-medium">{{ $cita->fecha->format('d/m/Y') }}</td>
                                <td>{{ $cita->hora }}</td>
                                <td>{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                                <td class="font-semibold">{{ $cita->total_formateado }}</td>
                                <td><span class="badge badge-{{ $cita->estado_color }}">{{ ucfirst($cita->estado) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-gray-500 mb-4">Aún no tienes citas reservadas.</p>
                    <a href="{{ route('citas.create') }}" class="btn-primary btn-sm">Reservar mi primera cita</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
