<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cuentas Bancarias
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">

        @if(session('status'))
            <div id="bank-status-alert" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 transition-opacity duration-500">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center gap-3 mb-6">
            <h3 class="text-xl font-bold">Cuentas registradas</h3>
            <a href="{{ route('bank_accounts.create') }}" style="background-color: #16a34a;" class="!text-white px-4 py-1 rounded">+ Agregar Cuenta</a>
        </div>

        @if($accounts->count() > 0)
            <div class="space-y-4">
                @foreach($accounts as $account)
                    <div class="rounded-xl shadow-sm p-4 {{ $account->is_active ? 'bg-green-50 border border-green-300' : 'bg-white border border-gray-200' }}">
                        <div>
                            <p class="font-bold text-gray-800">{{ $account->bank_name }}</p>
                            <p class="text-sm text-gray-600">{{ $account->account_type }}</p>
                            <p class="text-sm text-gray-600">Cuenta: {{ $account->account_number }}</p>
                            <p class="text-sm text-gray-600">Titular: {{ $account->account_holder }}</p>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 text-xs font-semibold {{ $account->is_active ? 'text-green-700' : 'text-red-700' }}">
                                <span class="w-2.5 h-2.5 rounded-full {{ $account->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $account->is_active ? 'Activa' : 'Desactivada' }}
                            </span>
                        </div>

                        <div class="mt-3 flex items-center gap-2">
                            @if($account->is_active)
                                <a href="{{ route('bank_accounts.deactivate', $account->id) }}" style="background-color: #dc2626;" class="text-xs text-white px-3 py-1 rounded">Desactivar</a>
                            @else
                                <a href="{{ route('bank_accounts.activate', $account->id) }}" style="background-color: #16a34a;" class="text-xs text-white px-3 py-1 rounded">Activar</a>
                            @endif
                            <a href="{{ route('bank_accounts.edit', $account->id) }}" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded">Editar</a>
                            <form action="{{ route('bank_accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cuenta?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded">Eliminar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No hay cuentas bancarias registradas.</p>
        @endif

        <div class="mt-6">
            <a href="/dashboard" class="text-gray-500 hover:underline text-sm">← Volver al dashboard</a>
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
