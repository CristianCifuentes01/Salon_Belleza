<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Reservar Cita</h2></x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('citas.store') }}" x-data="citaForm()" x-init="init()">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Date & Time -->
                        <div class="card p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">📅 Fecha y Hora</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="fecha" value="Fecha" />
                                    <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" :value="old('fecha')" required x-model="fecha" @change="cargarHoras()" x-bind:min="hoy" />
                                    <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="hora" value="Hora" />
                                    <select id="hora" name="hora" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-salon-500 focus:ring-salon-500" required x-model="hora">
                                        <option value="">Selecciona una hora</option>
                                        <template x-for="h in horasDisponibles" :key="h">
                                            <option :value="h" x-text="h"></option>
                                        </template>
                                    </select>
                                    <p x-show="cargandoHoras" class="text-sm text-gray-500 mt-1">Cargando horarios...</p>
                                    <p x-show="!cargandoHoras && fecha && horasDisponibles.length === 0" class="text-sm text-red-500 mt-1">No hay horarios disponibles para esta fecha.</p>
                                    <x-input-error :messages="$errors->get('hora')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="card p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">✨ Selecciona tus Servicios</h3>
                            <x-input-error :messages="$errors->get('servicios')" class="mb-2" />
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($servicios as $servicio)
                                <label class="flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:border-salon-300"
                                       :class="selectedServicios.includes('{{ $servicio->id }}') ? 'border-salon-500 bg-salon-50' : 'border-gray-200'">
                                    <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                           class="mt-1 rounded border-gray-300 text-salon-600 focus:ring-salon-500"
                                           x-model="selectedServicios"
                                           @change="calcularTotal()"
                                           {{ in_array($servicio->id, old('servicios', [])) ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="font-semibold text-gray-800">{{ $servicio->nombre }}</span>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-salon-600 font-bold">{{ $servicio->precio_formateado }}</span>
                                            <span class="text-gray-400 text-xs">{{ $servicio->duracion_formateada }}</span>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary -->
                    <div>
                        <div class="card p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Resumen</h3>
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-medium" x-text="fecha || '---'"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Hora:</span>
                                    <span class="font-medium" x-text="hora || '---'"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Servicios:</span>
                                    <span class="font-medium" x-text="selectedServicios.length + ' seleccionados'"></span>
                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <span class="font-bold text-gray-800">Total:</span>
                                    <span class="text-2xl font-bold text-salon-600" x-text="'$' + total.toFixed(2)"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary w-full" :disabled="selectedServicios.length === 0 || !fecha || !hora">
                                Confirmar Reserva
                            </button>
                            <a href="{{ route('citas.index') }}" class="btn-secondary w-full mt-3 text-center text-sm">Cancelar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function citaForm() {
            return {
                fecha: '{{ old("fecha", "") }}',
                hora: '{{ old("hora", "") }}',
                hoy: new Date().toISOString().split('T')[0],
                horasDisponibles: [],
                cargandoHoras: false,
                selectedServicios: {!! json_encode(old('servicios', [])) !!}.map(String),
                total: 0,
                precios: {!! $servicios->pluck('precio', 'id')->toJson() !!},
                init() {
                    if (this.fecha) this.cargarHoras();
                    this.calcularTotal();
                },
                async cargarHoras() {
                    if (!this.fecha) return;
                    this.cargandoHoras = true;
                    this.horasDisponibles = [];
                    try {
                        const res = await fetch(`{{ route('citas.disponibilidad') }}?fecha=${this.fecha}`);
                        const data = await res.json();
                        this.horasDisponibles = data.horas || [];
                    } catch (e) {
                        console.error(e);
                    }
                    this.cargandoHoras = false;
                },
                calcularTotal() {
                    this.total = this.selectedServicios.reduce((sum, id) => {
                        return sum + (parseFloat(this.precios[id]) || 0);
                    }, 0);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
