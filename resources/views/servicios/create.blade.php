<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Crear Servicio</h2></x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8">
                <form method="POST" action="{{ route('admin.servicios.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="nombre" value="Nombre del Servicio" />
                            <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre')" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="precio" value="Precio ($)" />
                                <x-text-input id="precio" name="precio" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('precio')" required />
                                <x-input-error :messages="$errors->get('precio')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="duracion" value="Duración (minutos)" />
                                <x-text-input id="duracion" name="duracion" type="number" min="15" max="240" class="mt-1 block w-full" :value="old('duracion', 60)" required />
                                <x-input-error :messages="$errors->get('duracion')" class="mt-2" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-salon-500 focus:ring-salon-500">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.servicios.index') }}" class="btn-secondary btn-sm">Cancelar</a>
                            <button type="submit" class="btn-primary">Crear Servicio</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
