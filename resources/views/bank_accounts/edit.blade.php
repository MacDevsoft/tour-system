<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Editar Cuenta Bancaria
        </h2>
    </x-slot>

    <div class="mt-6 p-6 max-w-xl mx-auto">
        <div class="border border-gray-700 rounded-xl shadow-md p-6" style="background-color:#111827;">
            <form action="{{ route('bank_accounts.update', $bank_account->id) }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-semibold mb-1 text-white">Tipo de transferencia</label>
                    <input type="text" name="account_type" value="{{ $bank_account->account_type }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-white">Banco</label>
                    <input type="text" name="bank_name" value="{{ $bank_account->bank_name }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-white">Número de cuenta</label>
                    <input type="text" name="account_number" value="{{ $bank_account->account_number }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-white">Nombre del titular</label>
                    <input type="text" name="account_holder" value="{{ $bank_account->account_holder }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" style="background-color: #16a34a;" class="!text-white px-4 py-2 rounded text-sm">Actualizar</button>
                    <a href="{{ route('bank_accounts.index') }}" class="bg-gray-200 text-black px-4 py-2 rounded text-sm">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
