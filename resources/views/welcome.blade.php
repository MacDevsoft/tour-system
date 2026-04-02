<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Tours by Ravers') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ravers-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/ravers-logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-slate-950 text-white antialiased">
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(34,211,238,0.16),transparent_45%),radial-gradient(circle_at_85%_10%,rgba(16,185,129,0.14),transparent_40%),linear-gradient(to_bottom,#020617,#0f172a)]"></div>

    <header class="relative z-10 w-full px-6 py-5 md:px-10">
        @if (Route::has('login'))
            <nav class="mx-auto flex w-full max-w-6xl items-center justify-end gap-3 rounded-2xl border border-white/10 bg-slate-900/65 px-4 py-3 text-sm backdrop-blur">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-cyan-400/40 bg-cyan-500/10 px-4 py-2 font-semibold text-cyan-200 transition hover:bg-cyan-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-slate-950/70 px-4 py-2 font-semibold text-slate-200 transition hover:border-cyan-400/40 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M10 17v-2h8V9h-8V7l-5 5 5 5Zm-6 3h12v2H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12v2H4v16Z"/>
                        </svg>
                        <span>Iniciar sesion</span>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 font-semibold text-white transition hover:bg-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.97 0-9 2.24-9 5v1h12v-2h2v2h4v-1c0-2.76-4.03-5-9-5Zm7-5V7h-2V5h-2v2h-2v2h2v2h2V9h2Z"/>
                            </svg>
                            <span>Registrarse</span>
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="relative z-10 h-[calc(100vh-88px)] w-full pt-2">
        <section class="mx-auto flex h-full w-full max-w-6xl items-center justify-center px-4 pb-4">
            <div class="w-full rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl shadow-black/25 backdrop-blur sm:p-6">
                <img
                    src="{{ asset('images/ravers-logo-sinfondo.png') }}"
                    alt="Ravers Software"
                    class="mx-auto max-h-[calc(100vh-170px)] w-auto object-contain"
                    onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='block';"
                >
                <div id="logo-fallback" style="display:none;" class="rounded-2xl border border-amber-500/20 bg-amber-500/10 p-6 text-center">
                    <p class="text-lg font-semibold text-amber-100">No se encontro la imagen del logo.</p>
                    <p class="mt-2 text-sm text-amber-200/80">Guarda tu archivo en public/images/ravers-logo-sinfondo.png</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
