<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Bienvenido de nuevo</h2>
        <p class="text-gray-500 text-sm mt-1">Inicia sesión en tu cuenta AppSalon</p>
    </div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-salon-600 shadow-sm focus:ring-salon-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recuérdame</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-salon-600 hover:text-salon-800" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </div>
        <div class="mt-6">
            <x-primary-button class="w-full justify-center !bg-salon-600 hover:!bg-salon-700">Iniciar Sesión</x-primary-button>
        </div>
        <p class="text-center text-sm text-gray-500 mt-4">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-salon-600 hover:text-salon-800 font-semibold">Regístrate</a></p>
    </form>
</x-guest-layout>
