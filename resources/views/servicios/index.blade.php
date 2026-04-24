<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800">Nuestros Servicios</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($servicios as $servicio)
                <div class="card-gradient p-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-salon-400 to-salon-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $servicio->nombre }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $servicio->descripcion }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-salon-100">
                        <span class="text-2xl font-bold text-salon-600">{{ $servicio->precio_formateado }}</span>
                        <div class="flex items-center text-gray-500 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $servicio->duracion_formateada }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @auth
            <div class="text-center mt-8">
                <a href="{{ route('citas.create') }}" class="btn-primary">Reservar Cita →</a>
            </div>
            @endauth
        </div>
    </div>
</x-app-layout>
