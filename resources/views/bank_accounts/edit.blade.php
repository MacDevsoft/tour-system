<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Editar Cuenta Bancaria</h2>
            <p class="text-sm text-slate-300">Actualiza la cuenta seleccionada desde una vista más ordenada y responsiva.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl rounded-3xl border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/30 sm:p-6 lg:p-8">
            <div class="mb-6 rounded-2xl border border-violet-500/20 bg-violet-500/10 p-4 text-sm text-violet-50">
                <p class="font-semibold">Cuenta en edición</p>
                <p class="mt-1 text-violet-100/90">Revisa banco, número y titular antes de guardar para evitar rechazos en los pagos.</p>
            </div>

            <form action="{{ route('bank_accounts.update', $bank_account->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-white">Tipo de transferencia</label>
                        <input type="text" name="account_type" value="{{ $bank_account->account_type }}"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-white">Banco</label>
                        <input type="text" name="bank_name" value="{{ $bank_account->bank_name }}"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-white">Número de cuenta</label>
                        <input type="text" name="account_number" value="{{ $bank_account->account_number }}"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-white">Nombre del titular</label>
                        <input type="text" name="account_holder" value="{{ $bank_account->account_holder }}"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500 sm:w-auto">
                        Actualizar cuenta
                    </button>
                    <a href="{{ route('bank_accounts.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm font-semibold text-slate-100 transition hover:bg-slate-800 sm:w-auto">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
