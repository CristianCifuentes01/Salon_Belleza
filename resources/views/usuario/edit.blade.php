<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-gray-800">Editar Usuario</h2></x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8">
                <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="nombre" value="Nombre" />
                                <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" :value="old('nombre', $usuario->nombre)" required />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="apellido" value="Apellido" />
                                <x-text-input id="apellido" name="apellido" class="mt-1 block w-full" :value="old('apellido', $usuario->apellido)" required />
                                <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $usuario->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="telefono" value="Teléfono" />
                            <x-text-input id="telefono" name="telefono" class="mt-1 block w-full" :value="old('telefono', $usuario->telefono)" maxlength="10" />
                        </div>
                        <div>
                            <x-input-label for="password" value="Nueva Contraseña (dejar vacío para no cambiar)" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="admin" name="admin" value="1" {{ $usuario->admin ? 'checked' : '' }} class="rounded border-gray-300 text-salon-600 focus:ring-salon-500">
                            <x-input-label for="admin" value="Es Administrador" />
                        </div>
                        <div class="flex justify-end gap-3 pt-4">
                            <a href="{{ route('admin.usuarios') }}" class="btn-secondary btn-sm">Cancelar</a>
                            <button type="submit" class="btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
