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
    <body class="font-sans antialiased bg-slate-950 text-white">
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.12),_transparent_0,_transparent_42%),linear-gradient(180deg,_#020617_0%,_#030712_55%,_#020617_100%)]">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-16 top-20 h-56 w-56 rounded-full bg-emerald-500/10 blur-3xl"></div>
                <div class="absolute right-0 top-0 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
            </div>

            <div class="relative z-10">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="border-b border-white/10 bg-slate-950/70 shadow-lg backdrop-blur-xl">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="relative z-10">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <div id="global-loading-overlay" class="pointer-events-none fixed inset-0 z-[999] hidden items-center justify-center bg-slate-950/70 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-4 rounded-3xl border border-white/10 bg-slate-900/90 px-8 py-6 shadow-2xl shadow-black/40">
                <div class="h-12 w-12 animate-spin rounded-full border-4 border-cyan-400/30 border-t-cyan-400"></div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-white">Cargando...</p>
                    <p class="text-xs text-slate-300">Espera un momento</p>
                </div>
            </div>
        </div>

        <script>
            const globalLoadingOverlay = document.getElementById('global-loading-overlay');

            function showGlobalLoader() {
                if (!globalLoadingOverlay) {
                    return;
                }

                globalLoadingOverlay.classList.remove('hidden');
                globalLoadingOverlay.classList.add('flex');
            }

            function hideGlobalLoader() {
                if (!globalLoadingOverlay) {
                    return;
                }

                globalLoadingOverlay.classList.remove('flex');
                globalLoadingOverlay.classList.add('hidden');
            }

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');

                if (!link || link.hasAttribute('data-no-loader')) {
                    return;
                }

                const href = link.getAttribute('href') || '';

                if (
                    href === '' ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    link.target === '_blank' ||
                    event.ctrlKey ||
                    event.metaKey ||
                    event.shiftKey ||
                    event.altKey
                ) {
                    return;
                }

                showGlobalLoader();
            });

            document.addEventListener('submit', (event) => {
                const form = event.target;

                if (!(form instanceof HTMLFormElement) || form.hasAttribute('data-no-loader')) {
                    return;
                }

                showGlobalLoader();
            });

            window.addEventListener('pageshow', hideGlobalLoader);
            window.addEventListener('load', hideGlobalLoader);
        </script>
    </body>
</html>
