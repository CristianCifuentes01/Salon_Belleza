<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AppSalon') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-salon-900 via-salon-800 to-salon-950">
            <!-- Decorative elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-salon-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gold-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <a href="/" class="flex flex-col items-center space-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-salon-400 to-salon-600 rounded-2xl flex items-center justify-center shadow-xl animate-pulse-glow">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white">AppSalon</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-salon-300 text-sm relative z-10">
                &copy; {{ date('Y') }} AppSalon. Todos los derechos reservados.
            </p>
        </div>
    </body>
</html>
