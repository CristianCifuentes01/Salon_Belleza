<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Detalle de Usuario</h2></x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-salon-400 to-salon-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($usuario->nombre, 0, 1) }}{{ substr($usuario->apellido, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $usuario->nombre }} {{ $usuario->apellido }}</h3>
                        <p class="text-gray-500">{{ $usuario->email }}</p>
                        <span class="badge {{ $usuario->isAdmin() ? 'badge-yellow' : 'badge-blue' }}">{{ $usuario->isAdmin() ? 'Administrador' : 'Cliente' }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500">Teléfono</p>
                        <p class="font-semibold">{{ $usuario->telefono ?: '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500">Estado</p>
                        <p class="font-semibold">{{ $usuario->confirmado ? 'Activo' : 'Inactivo' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500">Registrado</p>
                        <p class="font-semibold">{{ $usuario->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500">Total Citas</p>
                        <p class="font-semibold">{{ $usuario->citas->count() }}</p>
                    </div>
                </div>
                @if($usuario->citas->count() > 0)
                <h4 class="font-bold text-gray-800 mb-3">Historial de Citas</h4>
                <table class="table-salon">
                    <thead><tr><th>Fecha</th><th>Servicios</th><th>Total</th><th>Estado</th></tr></thead>
                    <tbody>
                        @foreach($usuario->citas->take(10) as $cita)
                        <tr>
                            <td>{{ $cita->fecha->format('d/m/Y') }} {{ $cita->hora }}</td>
                            <td class="text-xs">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                            <td class="font-semibold">{{ $cita->total_formateado }}</td>
                            <td><span class="badge badge-{{ $cita->estado_color }}">{{ ucfirst($cita->estado) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                <div class="mt-6">
                    <a href="{{ route('admin.usuarios') }}" class="btn-secondary btn-sm">← Volver</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
