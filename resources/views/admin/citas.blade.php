<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Gestión de Citas</h2></x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filters -->
            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Estado</label>
                        <select name="estado" class="block mt-1 rounded-lg border-gray-300 text-sm focus:border-salon-500">
                            <option value="">Todos</option>
                            @foreach(['pendiente','confirmada','completada','cancelada'] as $e)
                            <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Fecha</label>
                        <input type="date" name="fecha" value="{{ request('fecha') }}" class="block mt-1 rounded-lg border-gray-300 text-sm focus:border-salon-500">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Buscar cliente</label>
                        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre o email..." class="block mt-1 rounded-lg border-gray-300 text-sm focus:border-salon-500">
                    </div>
                    <button type="submit" class="btn-primary btn-sm">Filtrar</button>
                    <a href="{{ route('admin.citas', ['fecha' => date('Y-m-d')]) }}" class="btn-gold btn-sm">Hoy</a>
                    <a href="{{ route('admin.citas') }}" class="btn-secondary btn-sm">Limpiar</a>
                </form>
            </div>
            <!-- Table -->
            <div class="card overflow-hidden">
                <table class="table-salon">
                    <thead><tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Servicios</th><th>Total</th><th>Estado</th><th>Acción</th></tr></thead>
                    <tbody>
                        @forelse($citas as $cita)
                        <tr>
                            <td>#{{ $cita->id }}</td>
                            <td class="font-medium">{{ $cita->fecha->format('d/m/Y') }}</td>
                            <td>{{ $cita->hora }}</td>
                            <td>{{ $cita->usuario ? $cita->usuario->nombre_completo : 'N/A' }}</td>
                            <td class="text-xs max-w-[200px] truncate">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                            <td class="font-semibold">{{ $cita->total_formateado }}</td>
                            <td><span class="badge badge-{{ $cita->estado_color }}">{{ ucfirst($cita->estado) }}</span></td>
                            <td class="flex items-center space-x-2">
                                <a href="{{ route('citas.show', $cita) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold" title="Ver Detalles">Ver</a>
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
                        @empty
                        <tr><td colspan="8" class="text-center py-8 text-gray-500">No se encontraron citas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $citas->withQueryString()->links() }}</div>
        </div>
    </div>
</x-app-layout>
