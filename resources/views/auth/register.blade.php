<x-guest-layout>
    <div class="mb-6">
        <h2 class="inline-flex items-center gap-2 text-2xl font-bold text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.97 0-9 2.24-9 5v1h18v-1c0-2.76-4.03-5-9-5Zm7-5V7h-2V5h-2v2h-2v2h2v2h2V9h2Z"/>
            </svg>
            <span>Crear cuenta</span>
        </h2>
        <p class="mt-1 text-sm text-slate-300">Regístrate para reservar tours y dar seguimiento a tus pagos.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a class="inline-flex items-center gap-2 text-sm text-cyan-300 transition hover:text-cyan-200" href="{{ route('login') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M10 17v-2h8V9h-8V7l-5 5 5 5Zm-6 3h12v2H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12v2H4v16Z"/>
                </svg>
                <span>{{ __('Already registered?') }}</span>
            </a>

            <x-primary-button class="w-full justify-center sm:w-auto">
                <span class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.97 0-9 2.24-9 5v1h18v-1c0-2.76-4.03-5-9-5Zm7-5V7h-2V5h-2v2h-2v2h2v2h2V9h2Z"/>
                    </svg>
                    <span>{{ __('Register') }}</span>
                </span>
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
