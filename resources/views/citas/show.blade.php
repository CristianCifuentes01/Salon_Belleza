<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Detalle de Cita #{{ $cita->id }}</h2></x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8">
                <div class="flex items-center justify-between mb-6">
                    <span class="badge badge-{{ $cita->estado_color }} text-sm px-4 py-1">{{ ucfirst($cita->estado) }}</span>
                    <span class="text-sm text-gray-500">Creada: {{ $cita->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div class="bg-salon-50 rounded-xl p-4">
                        <p class="text-sm text-salon-600 font-medium">Fecha</p>
                        <p class="text-lg font-bold text-gray-800">{{ $cita->fecha->format('l, d M Y') }}</p>
                    </div>
                    <div class="bg-salon-50 rounded-xl p-4">
                        <p class="text-sm text-salon-600 font-medium">Hora</p>
                        <p class="text-lg font-bold text-gray-800">{{ $cita->hora }} hrs</p>
                    </div>
                </div>
                <h4 class="font-bold text-gray-800 mb-3">Servicios</h4>
                <div class="space-y-2 mb-6">
                    @foreach($cita->servicios as $servicio)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">{{ $servicio->nombre }}</span>
                        <span class="font-semibold text-salon-600">{{ $servicio->precio_formateado }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-salon-50 to-salon-100 rounded-xl">
                    <span class="font-bold text-gray-800 text-lg">Total</span>
                    <span class="text-2xl font-bold text-salon-700">{{ $cita->total_formateado }}</span>
                </div>
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('citas.index') }}" class="btn-secondary btn-sm">← Volver</a>
                    @if(in_array($cita->estado, ['pendiente', 'confirmada']))
                    <form method="POST" action="{{ route('citas.destroy', $cita) }}" onsubmit="return confirm('¿Cancelar esta cita?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">Cancelar Cita</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
