<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800">Mis Citas</h2>
            <a href="{{ route('citas.create') }}" class="btn-primary btn-sm">+ Nueva Cita</a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($citas->count() > 0)
            <div class="space-y-4">
                @foreach($citas as $cita)
                <div class="card p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-salon-100 rounded-xl flex flex-col items-center justify-center">
                                <span class="text-xs text-salon-600 font-semibold">{{ $cita->fecha->format('M') }}</span>
                                <span class="text-lg font-bold text-salon-800">{{ $cita->fecha->format('d') }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $cita->fecha->format('l, d M Y') }}</h3>
                                <p class="text-gray-500 text-sm">{{ $cita->hora }} hrs</p>
                                <p class="text-gray-600 text-sm mt-1">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-lg font-bold text-salon-700">{{ $cita->total_formateado }}</span>
                            <span class="badge badge-{{ $cita->estado_color }}">{{ ucfirst($cita->estado) }}</span>
                            <div class="flex space-x-2">
                                <a href="{{ route('citas.show', $cita) }}" class="text-salon-600 hover:text-salon-800 text-sm font-medium">Ver</a>
                                @if(in_array($cita->estado, ['pendiente', 'confirmada']))
                                <form method="POST" action="{{ route('citas.destroy', $cita) }}" onsubmit="return confirm('¿Cancelar esta cita?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Cancelar</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="card p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No tienes citas</h3>
                <p class="text-gray-500 mb-6">¡Reserva tu primera cita y déjate consentir!</p>
                <a href="{{ route('citas.create') }}" class="btn-primary">Reservar Cita</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
