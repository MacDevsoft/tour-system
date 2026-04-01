<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Cuentas Bancarias</h2>
            <p class="text-sm text-slate-300">Administra las cuentas disponibles con una vista clara para celular, tablet y escritorio.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl rounded-3xl border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/30 sm:p-6 lg:p-8">
            @if(session('status'))
                <div id="bank-status-alert" class="mb-5 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-200 transition-opacity duration-500">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-cyan-300">Panel financiero</p>
                    <h3 class="text-2xl font-bold text-white">Cuentas registradas</h3>
                </div>

                <a href="{{ route('bank_accounts.create') }}"
                   class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500 sm:w-auto">
                    + Agregar Cuenta
                </a>
            </div>

            @if($accounts->count() > 0)
                <div class="space-y-4">
                    @foreach($accounts as $account)
                        <article class="rounded-2xl border p-4 shadow-lg shadow-slate-950/5 sm:p-5 {{ $account->is_active ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 bg-white' }}">
                            <div class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-start">
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <p class="text-lg font-bold text-slate-900">{{ $account->bank_name }}</p>
                                        <p class="text-sm text-slate-600">{{ $account->account_type }}</p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 px-3 py-2">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500">Cuenta</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $account->account_number }}</p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 px-3 py-2">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500">Titular</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $account->account_holder }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 lg:min-w-[220px]">
                                    <span class="inline-flex items-center justify-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $account->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        <span class="h-2.5 w-2.5 rounded-full {{ $account->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                        {{ $account->is_active ? 'Activa' : 'Desactivada' }}
                                    </span>

                                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap lg:flex-col">
                                        @if($account->is_active)
                                            <a href="{{ route('bank_accounts.deactivate', $account->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-500">
                                                Desactivar
                                            </a>
                                        @else
                                            <a href="{{ route('bank_accounts.activate', $account->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-500">
                                                Activar
                                            </a>
                                        @endif

                                        <a href="{{ route('bank_accounts.edit', $account->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-800 transition hover:bg-amber-200">
                                            Editar
                                        </a>

                                        <form action="{{ route('bank_accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cuenta?')" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-red-100 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-200">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/40 p-8 text-center text-slate-300">
                    <p class="text-lg font-semibold text-white">No hay cuentas bancarias registradas.</p>
                    <p class="mt-2 text-sm">Agrega una cuenta para que los usuarios puedan subir comprobantes de pago.</p>
                </div>
            @endif

            <div class="mt-6">
                <a href="/dashboard" class="text-sm text-slate-300 transition hover:text-white hover:underline">← Volver al dashboard</a>
            </div>
        </div>
    </div>

    <script>
        const alertBox = document.getElementById('bank-status-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('opacity-0');
                setTimeout(() => alertBox.remove(), 500);
            }, 2200);
        }
    </script>
</x-app-layout>
