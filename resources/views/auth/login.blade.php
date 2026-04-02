<x-guest-layout>
    <div class="mb-6">
        <h2 class="inline-flex items-center gap-2 text-2xl font-bold text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M10 17v-2h8V9h-8V7l-5 5 5 5Zm-6 3h12v2H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12v2H4v16Z"/>
            </svg>
            <span>Iniciar sesión</span>
        </h2>
        <p class="mt-1 text-sm text-slate-300">Accede para administrar tours o revisar tus reservaciones.</p>
    </div>

    <x-auth-session-status class="mb-4 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-200" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-300">
                <input id="remember_me" type="checkbox" class="rounded border-slate-600 bg-slate-900 text-cyan-500 shadow-sm focus:ring-cyan-500" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="inline-flex items-center gap-2 text-sm text-cyan-300 transition hover:text-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2" href="{{ route('password.request') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 2a10 10 0 1 0 10 10A10.01 10.01 0 0 0 12 2Zm1 17h-2v-2h2Zm1.07-7.75-.9.92A1.49 1.49 0 0 0 12.75 13h-1.5v-.5a2.99 2.99 0 0 1 .88-2.12l1.24-1.26A1.5 1.5 0 1 0 10.5 8H9a3 3 0 1 1 5.07 2.25Z"/>
                    </svg>
                    <span>{{ __('Forgot your password?') }}</span>
                </a>
            @endif

            <x-primary-button class="w-full justify-center sm:w-auto">
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M10 17v-2h8V9h-8V7l-5 5 5 5Zm-6 3h12v2H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12v2H4v16Z"/>
                    </svg>
                    <span>{{ __('Log in') }}</span>
                </span>
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-300 sm:text-left">
                ¿Aún no tienes cuenta?
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1 font-semibold text-cyan-300 transition hover:text-cyan-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.97 0-9 2.24-9 5v1h18v-1c0-2.76-4.03-5-9-5Zm7-5V7h-2V5h-2v2h-2v2h2v2h2V9h2Z"/>
                    </svg>
                    <span>Regístrate</span>
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
