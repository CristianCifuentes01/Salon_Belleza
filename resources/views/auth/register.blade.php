<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Crear Cuenta</h2>
        <p class="text-gray-500 text-sm mt-1">Únete a AppSalon y reserva tus citas</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="apellido" value="Apellido" />
                <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido')" required />
                <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
            </div>
        </div>
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="telefono" value="Teléfono (opcional)" />
            <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" maxlength="10" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-salon-600 hover:text-salon-800" href="{{ route('login') }}">¿Ya tienes cuenta?</a>
            <x-primary-button class="!bg-salon-600 hover:!bg-salon-700">Registrarse</x-primary-button>
        </div>
    </form>
</x-guest-layout>
