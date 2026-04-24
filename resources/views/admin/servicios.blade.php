<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800">Gestión de Servicios</h2>
            <a href="{{ route('admin.servicios.create') }}" class="btn-primary btn-sm">+ Nuevo Servicio</a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card overflow-hidden">
                <table class="table-salon">
                    <thead><tr><th>Nombre</th><th>Precio</th><th>Duración</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody>
                        @foreach($servicios as $servicio)
                        <tr>
                            <td class="font-semibold">{{ $servicio->nombre }}</td>
                            <td class="font-bold text-salon-600">{{ $servicio->precio_formateado }}</td>
                            <td>{{ $servicio->duracion_formateada }}</td>
                            <td><span class="badge {{ $servicio->activo ? 'badge-green' : 'badge-red' }}">{{ $servicio->activo ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="space-x-2">
                                <a href="{{ route('admin.servicios.edit', $servicio) }}" class="text-salon-600 hover:text-salon-800 text-sm font-medium">Editar</a>
                                <form method="POST" action="{{ route('admin.servicios.destroy', $servicio) }}" class="inline" onsubmit="return confirm('¿Eliminar este servicio?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
