<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AppSalon - Tu salón de belleza de confianza. Reserva tu cita online.">
    <title>AppSalon - Salón de Belleza</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-salon-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-salon-500 to-salon-700 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-salon-600 to-salon-800 bg-clip-text text-transparent">AppSalon</span>
                </div>
                <div class="hidden sm:flex items-center space-x-4">
                    <a href="#servicios" class="text-gray-600 hover:text-salon-600 font-medium transition-colors">Servicios</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary btn-sm">Mi Cuenta</a>
                    @else
                        <a href="{{ route('login') }}" class="text-salon-600 hover:text-salon-700 font-semibold">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16">
        <div class="absolute inset-0 bg-gradient-to-br from-salon-900 via-salon-800 to-salon-950"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-salon-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-gold-500/15 rounded-full blur-3xl animate-pulse" style="animation-delay:1s"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center animate-fade-in-up">
            <span class="inline-block px-4 py-1.5 bg-salon-500/20 text-salon-200 text-sm font-medium rounded-full mb-6 border border-salon-400/30">✨ Tu belleza, nuestra pasión</span>
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold text-white leading-tight mb-6">
                Reserva tu cita<br>
                <span class="bg-gradient-to-r from-salon-300 via-gold-300 to-salon-400 bg-clip-text text-transparent">en segundos</span>
            </h1>
            <p class="text-lg sm:text-xl text-salon-200 max-w-2xl mx-auto mb-10">Descubre nuestros servicios premium de belleza. Agenda online y déjate consentir.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('citas.create') }}" class="btn-gold text-lg px-8 py-4 animate-pulse-glow">Reservar Cita Ahora</a>
                @else
                    <a href="{{ route('register') }}" class="btn-gold text-lg px-8 py-4 animate-pulse-glow">Comienza Ahora</a>
                @endauth
                <a href="#servicios" class="btn-secondary text-lg px-8 py-4 !bg-white/10 !text-white !border-white/30 hover:!bg-white/20">Ver Servicios</a>
            </div>
            <div class="mt-16 grid grid-cols-3 gap-8 max-w-lg mx-auto">
                <div class="text-center"><div class="text-3xl font-bold text-white">500+</div><div class="text-salon-300 text-sm">Clientes</div></div>
                <div class="text-center"><div class="text-3xl font-bold text-white">8+</div><div class="text-salon-300 text-sm">Servicios</div></div>
                <div class="text-center"><div class="text-3xl font-bold text-white">5★</div><div class="text-salon-300 text-sm">Rating</div></div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="servicios" class="py-24 bg-gradient-to-b from-white to-salon-50/50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-salon-100 text-salon-700 text-sm font-semibold rounded-full mb-4">Nuestros Servicios</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Tratamientos de <span class="bg-gradient-to-r from-salon-600 to-salon-800 bg-clip-text text-transparent">Belleza Premium</span></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($servicios as $servicio)
                <div class="card-gradient p-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-salon-400 to-salon-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $servicio->nombre }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $servicio->descripcion }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-salon-100">
                        <span class="text-2xl font-bold text-salon-600">{{ $servicio->precio_formateado }}</span>
                        <span class="text-gray-500 text-sm">{{ $servicio->duracion_formateada }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12"><p class="text-gray-500">Próximamente.</p></div>
                @endforelse
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('servicios.index') }}" class="btn-primary">Ver Todos los Servicios →</a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-salon-600 via-salon-500 to-gold-500">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">¿Lista para lucir increíble?</h2>
            <p class="text-white/90 text-lg mb-8">Reserva tu cita ahora y déjate consentir por nuestros expertos.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-salon-700 font-bold text-lg rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">Crear Cuenta Gratis →</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="text-xl font-bold text-white">AppSalon</span>
                    <p class="text-sm mt-2">Tu salón de belleza de confianza.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-salon-400">Iniciar Sesión</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-salon-400">Registrarse</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Horario</h4>
                    <p class="text-sm">Lun - Sáb: 9:00 AM - 6:00 PM</p>
                    <p class="text-sm">Domingo: Cerrado</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">&copy; {{ date('Y') }} AppSalon.</div>
        </div>
    </footer>
</body>
</html>
