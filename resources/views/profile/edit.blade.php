<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-3xl border border-cyan-500/20 bg-slate-900/80 p-5 shadow-xl shadow-black/20 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300">Mi cuenta</p>
                <h3 class="mt-2 text-2xl font-black text-white">Administra tu perfil</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Actualiza tu información, cambia tu contraseña y protege tu acceso desde cualquier dispositivo.
                </p>
            </section>

            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-4 shadow-xl shadow-black/20 sm:p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-4 shadow-xl shadow-black/20 sm:p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-3xl border border-rose-500/20 bg-slate-900/80 p-4 shadow-xl shadow-black/20 sm:p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
