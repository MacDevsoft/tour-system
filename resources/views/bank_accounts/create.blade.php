<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Agregar Cuenta Bancaria</h2>
            <p class="text-sm text-slate-300">Captura los datos de depósito desde un formulario optimizado para cualquier tamaño de pantalla.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl rounded-3xl border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/30 sm:p-6 lg:p-8">
            <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm text-cyan-50">
                <p class="font-semibold">Recomendación</p>
                <p class="mt-1 text-cyan-100/90">Usa los datos exactos del banco para que los comprobantes de los usuarios coincidan correctamente.</p>
            </div>

            <form action="{{ route('bank_accounts.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-white">Tipo de transferencia</label>
                        <input type="text" name="account_type" value="TRANSFERENCIA ESPEI"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-white">Banco</label>
                        <input type="text" name="bank_name" placeholder="Ej: Banco Pichincha"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-white">Número de cuenta</label>
                        <input type="text" name="account_number" placeholder="Ej: 2200123456789"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-white">Nombre del titular</label>
                        <input type="text" name="account_holder" placeholder="Ej: Juan Pérez"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500 sm:w-auto">
                        Guardar cuenta
                    </button>
                    <a href="{{ route('bank_accounts.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm font-semibold text-slate-100 transition hover:bg-slate-800 sm:w-auto">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
