<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Tour System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-white antialiased">
    <header class="w-full px-6 py-6 md:px-10">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-3 text-sm">
                @auth
                    <a href="{{ url('/dashboard') }}" class="border border-white/30 px-4 py-2 rounded-md hover:bg-white hover:text-black transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md border border-transparent hover:border-white/30 transition">
                        Iniciar sesion
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="border border-white/30 px-4 py-2 rounded-md hover:bg-white hover:text-black transition">
                            Registrarse
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="w-full h-[calc(100vh-80px)]">
        <section class="mx-auto flex w-full h-full items-center justify-center px-2">
            <div class="w-full">
                <img
                    src="{{ asset('images/ravers-logo-sinfondo.png') }}"
                    alt="Ravers Software"
                    class="mx-auto h-[95vh] object-contain"
                    onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='block';"
                >
                <div id="logo-fallback" style="display:none;" class="text-center">
                    <p class="text-lg text-white/80">No se encontro la imagen del logo.</p>
                    <p class="text-sm text-white/60 mt-2">Guarda tu archivo en public/images/ravers-logo-sinfondo.png</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
