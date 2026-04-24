<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Actualiza tu información personal y dirección de email.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $user->nombre)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
            </div>
            <div>
                <x-input-label for="apellido" value="Apellido" />
                <x-text-input id="apellido" name="apellido" type="text" class="mt-1 block w-full" :value="old('apellido', $user->apellido)" required />
                <x-input-error class="mt-2" :messages="$errors->get('apellido')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Tu email no ha sido verificado.
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-salon-500">
                            Click aquí para re-enviar la verificación.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">Se ha enviado un nuevo enlace de verificación.</p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="telefono" value="Teléfono" />
            <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" :value="old('telefono', $user->telefono)" maxlength="10" />
            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="!bg-salon-600 hover:!bg-salon-700">Guardar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Guardado.</p>
            @endif
        </div>
    </form>
</section>
