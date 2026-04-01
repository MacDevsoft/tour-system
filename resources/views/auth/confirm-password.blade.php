<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white">Confirmar identidad</h2>
        <p class="mt-1 text-sm text-slate-300">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center sm:w-auto">
            {{ __('Confirm') }}
        </x-primary-button>
    </form>
</x-guest-layout>
