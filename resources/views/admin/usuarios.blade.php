<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800">Gestión de Usuarios</h2>
            <a href="{{ route('admin.usuarios.create') }}" class="btn-primary btn-sm">+ Nuevo Usuario</a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card overflow-hidden">
                <table class="table-salon">
                    <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody>
                        @foreach($usuarios as $user)
                        <tr>
                            <td class="font-semibold">{{ $user->nombre }} {{ $user->apellido }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telefono ?: '—' }}</td>
                            <td><span class="badge {{ $user->isAdmin() ? 'badge-yellow' : 'badge-blue' }}">{{ $user->isAdmin() ? 'Admin' : 'Cliente' }}</span></td>
                            <td><span class="badge {{ $user->confirmado ? 'badge-green' : 'badge-red' }}">{{ $user->confirmado ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="space-x-2">
                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="text-salon-600 hover:text-salon-800 text-sm font-medium">Editar</a>
                                <form method="POST" action="{{ route('admin.usuarios.toggle', $user) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-{{ $user->confirmado ? 'red' : 'green' }}-500 hover:underline text-sm font-medium">{{ $user->confirmado ? 'Desactivar' : 'Activar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.usuarios.destroy', $user) }}" class="inline" onsubmit="return confirm('¿Eliminar usuario?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $usuarios->links() }}</div>
        </div>
    </div>
</x-app-layout>
