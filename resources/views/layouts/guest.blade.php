<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-white">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.12),_transparent_0,_transparent_40%),linear-gradient(180deg,_#020617_0%,_#030712_55%,_#020617_100%)]">
            <div class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-6 sm:px-6 lg:px-8">
                <div class="grid w-full gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <div class="hidden lg:block">
                        <a href="/" class="inline-flex items-center gap-4">
                            <img src="{{ asset('images/ravers-logo-sinfondo.png') }}" alt="Ravers Software" class="h-20 w-auto object-contain">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300">Ravers Tour System</p>
                                <h1 class="mt-1 text-4xl font-black text-white">Acceso al sistema</h1>
                            </div>
                        </a>

                        <p class="mt-6 max-w-xl text-base leading-7 text-slate-300">
                            Inicia sesión para gestionar tours, revisar pagos o seguir el avance de tus reservaciones desde cualquier dispositivo.
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-cyan-300">Reservaciones</p>
                                <p class="mt-2 text-sm text-slate-100">Consulta viajes y confirma tus movimientos fácilmente.</p>
                            </div>
                            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-emerald-300">Pagos</p>
                                <p class="mt-2 text-sm text-slate-100">Da seguimiento a anticipos, saldos y comprobantes digitales.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-6 flex justify-center lg:hidden">
                            <a href="/" class="inline-flex flex-col items-center gap-3 text-center">
                                <img src="{{ asset('images/ravers-logo-sinfondo.png') }}" alt="Ravers Software" class="h-20 w-auto object-contain">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300">Ravers Tour System</p>
                                    <h1 class="mt-1 text-2xl font-black text-white">Acceso al sistema</h1>
                                </div>
                            </a>
                        </div>

                        <div class="w-full rounded-3xl border border-white/10 bg-slate-900/90 p-4 shadow-2xl shadow-black/30 sm:p-6">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
