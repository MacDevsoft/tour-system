<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tours by Ravers') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/ravers-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/ravers-logo.png') }}">

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

            <footer class="border-t border-white/10 bg-slate-950/85 px-4 py-5 backdrop-blur-xl sm:px-6 lg:px-8">
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-3 text-sm text-slate-300 md:flex-row md:items-center md:justify-between">
                    <p>
                        © {{ now()->year }} Tours by Ravers. Desarrollado por <span class="font-semibold text-cyan-300">MacDevSoft</span>.
                    </p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="https://www.instagram.com/toursbyravers?igsh=MWp6MG5zd2JmcGczYQ==" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-white/10 px-3 py-1.5 text-slate-200 transition hover:border-cyan-400/40 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5Zm9.1 1.15a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/>
                            </svg>
                            <span>Instagram</span>
                        </a>
                        <a href="https://wa.me/525518936972" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-white/10 px-3 py-1.5 text-slate-200 transition hover:border-emerald-400/40 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2.02a9.97 9.97 0 0 1 8.62 15.02l1.24 4.53-4.64-1.22A9.98 9.98 0 1 1 12 2.02Zm0 1.5a8.48 8.48 0 0 0-7.43 12.55l.18.33-.75 2.74 2.81-.73.31.18A8.48 8.48 0 1 0 12 3.52Zm-4.4 3.95c.16-.37.34-.38.5-.39h.42c.14 0 .34.05.52.44.18.39.6 1.46.65 1.57.05.11.08.24 0 .39-.08.16-.12.25-.24.39-.12.14-.26.32-.37.43-.12.12-.24.25-.1.49.14.24.62 1.03 1.34 1.67.92.82 1.7 1.07 1.94 1.19.24.12.37.1.5-.06.14-.16.58-.67.73-.91.16-.24.32-.2.54-.12.22.08 1.39.66 1.62.78.23.12.39.18.45.28.06.1.06.58-.13 1.14-.19.56-1.1 1.07-1.52 1.14-.39.06-.89.09-1.43-.09-.33-.11-.74-.24-1.27-.47-2.24-.97-3.72-3.31-3.84-3.48-.12-.17-.91-1.21-.91-2.31 0-1.1.58-1.64.79-1.86Z"/>
                            </svg>
                            <span>WhatsApp: +52 55 1893 6972</span>
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
