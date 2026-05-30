<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Horarios y Disponibilidad</h2></x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Horario de operacion</h3>
                <form method="POST" action="{{ route('admin.horarios.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="overflow-x-auto">
                        <table class="table-salon">
                            <thead><tr><th>Dia</th><th>Apertura</th><th>Cierre</th><th>Disponible</th></tr></thead>
                            <tbody>
                                @foreach($horarios as $horario)
                                <tr>
                                    <td class="font-semibold">
                                        {{ ['1' => 'Lunes', '2' => 'Martes', '3' => 'Miercoles', '4' => 'Jueves', '5' => 'Viernes', '6' => 'Sabado', '7' => 'Domingo'][$horario->dia_semana] }}
                                        <input type="hidden" name="horarios[{{ $loop->index }}][dia_semana]" value="{{ $horario->dia_semana }}">
                                    </td>
                                    <td><input type="time" name="horarios[{{ $loop->index }}][hora_apertura]" value="{{ old("horarios.{$loop->index}.hora_apertura", substr((string) $horario->hora_apertura, 0, 5)) }}" class="rounded-lg border-gray-300 text-sm"></td>
                                    <td><input type="time" name="horarios[{{ $loop->index }}][hora_cierre]" value="{{ old("horarios.{$loop->index}.hora_cierre", substr((string) $horario->hora_cierre, 0, 5)) }}" class="rounded-lg border-gray-300 text-sm"></td>
                                    <td>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" name="horarios[{{ $loop->index }}][activo]" value="1" {{ old("horarios.{$loop->index}.activo", $horario->activo) ? 'checked' : '' }} class="rounded border-gray-300 text-salon-600 focus:ring-salon-500">
                                            <span class="text-sm text-gray-600">Activo</span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-primary">Guardar Horarios</button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Bloquear horario</h3>
                    <form method="POST" action="{{ route('admin.horarios.bloqueos.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="fecha" value="Fecha" />
                            <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" :value="old('fecha')" required />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <x-input-label for="hora_inicio" value="Inicio" />
                                <x-text-input id="hora_inicio" name="hora_inicio" type="time" class="mt-1 block w-full" :value="old('hora_inicio')" />
                            </div>
                            <div>
                                <x-input-label for="hora_fin" value="Fin" />
                                <x-text-input id="hora_fin" name="hora_fin" type="time" class="mt-1 block w-full" :value="old('hora_fin')" />
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">Deja inicio y fin vacios para bloquear todo el dia.</p>
                        <div>
                            <x-input-label for="motivo" value="Motivo" />
                            <x-text-input id="motivo" name="motivo" class="mt-1 block w-full" :value="old('motivo')" />
                        </div>
                        <button type="submit" class="btn-primary w-full">Crear Bloqueo</button>
                    </form>
                </div>

                <div class="lg:col-span-2 card overflow-hidden">
                    <table class="table-salon">
                        <thead><tr><th>Fecha</th><th>Horario</th><th>Motivo</th><th>Accion</th></tr></thead>
                        <tbody>
                            @forelse($bloqueos as $bloqueo)
                            <tr>
                                <td class="font-semibold">{{ $bloqueo->fecha->format('d/m/Y') }}</td>
                                <td>{{ $bloqueo->hora_inicio ? substr($bloqueo->hora_inicio, 0, 5) . ' - ' . substr($bloqueo->hora_fin, 0, 5) : 'Todo el dia' }}</td>
                                <td>{{ $bloqueo->motivo ?: 'Sin motivo' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.horarios.bloqueos.destroy', $bloqueo) }}" onsubmit="return confirm('Eliminar bloqueo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-8 text-gray-500">No hay bloqueos configurados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-4">{{ $bloqueos->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
