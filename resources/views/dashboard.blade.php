<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    
   <h2 class="text-2xl font-bold mb-4">
    Bienvenido {{ auth()->user()->name }}
</h2>

@if(auth()->user()->role === 'admin')
    <div class="p-6 bg-green-200 rounded space-y-4">
        <p>👑 Eres ADMIN - aquí irá tu panel de control</p>

        <a href="/tours" class="bg-blue-600 !text-white px-4 py-2 rounded shadow inline-block">
            Ver / Editar Tours
        </a>
        <a href="{{ route('bank_accounts.index') }}" class="bg-gray-700 !text-white px-4 py-2 rounded shadow inline-block">
            🏦 Cuentas Bancarias
        </a>
    </div>
@else
    <div class="p-6">
        <h3 class="text-2xl font-bold mb-6">Tours Disponibles</h3>
        
        @php
            $tours = \App\Models\Tour::where('is_enabled', true)->get();
        @endphp

        @if($tours->count() > 0)
            <div class="flex flex-wrap justify-center gap-12 max-w-6xl">
                @foreach($tours as $tour)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-80 text-center">

                        <h3 class="text-xl font-bold mb-2">
                            {{ $tour->nombre }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-4">
                            {{ $tour->descripcion }}
                        </p>

                        <div class="text-sm text-gray-700 space-y-2 flex flex-col items-center">
                            <p>📍 {{ $tour->ubicacion }}</p>
                            <p>📅 {{ $tour->fecha_inicio }} → {{ $tour->fecha_fin }}</p>
                            <p class="text-green-600 font-bold">💰 ${{ number_format($tour->precio_total, 2) }}</p>
                            <p>👥 {{ $tour->cupos_disponibles }} / {{ $tour->cupos_totales }}</p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('tours.show', $tour->id) }}" 
                               class="bg-blue-500 text-black px-4 py-2 rounded mt-2 inline-block">
                                Ver detalles
                            </a>
                            <br>
                            <button onclick="openModal('modal-{{ $tour->id }}')"
                               style="background-color: #22c55e;"
                               class="!text-white px-4 py-2 rounded-xl shadow-md inline-block mt-2 w-full text-center font-semibold">
                                Reservar
                            </button>
                        </div>

                        {{-- Modal de Reserva --}}
                        @php $bank = \App\Models\BankAccount::active(); @endphp
                        <div id="modal-{{ $tour->id }}" class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center p-8">
                            <div class="modal-box bg-white rounded-2xl shadow-xl p-6 w-72">
                                <h3 class="text-base font-bold text-gray-800">Solicitar Reservación</h3>
                                <p class="text-gray-400 text-xs mb-3">{{ $tour->nombre }}</p>

                                <div class="bg-blue-50 border border-blue-100 rounded-lg px-3 py-2 mb-3">
                                    <p class="text-xs text-blue-600 font-semibold">Anticipo a pagar</p>
                                    <p class="text-lg font-bold text-blue-700">${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                                </div>

                                @if($bank)
                                <div class="bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 mb-3 space-y-0.5">
                                    <p class="text-xs font-bold text-gray-600 mb-1">Datos bancarios</p>
                                    <p class="text-xs text-gray-500">{{ $bank->account_type }}</p>
                                    <p class="text-xs text-gray-500"><span class="font-medium">Banco:</span> {{ $bank->bank_name }}</p>
                                    <p class="text-xs text-gray-500"><span class="font-medium">Cuenta:</span> {{ $bank->account_number }}</p>
                                    <p class="text-xs text-gray-500"><span class="font-medium">Titular:</span> {{ $bank->account_holder }}</p>
                                </div>
                                @else
                                <div class="bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-2 mb-3">
                                    <p class="text-xs text-yellow-600">⚠️ Sin cuenta bancaria configurada.</p>
                                </div>
                                @endif

                                <form action="{{ route('bookings.store', $tour->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold mb-1 text-gray-600">📎 Comprobante de pago</label>
                                        <input type="file" name="receipt" accept="image/*" required
                                               class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-gray-500">
                                    </div>

                                    <p class="text-xs text-gray-400 mb-4 leading-4">
                                        Las transferencias pueden tardar hasta <strong>24 horas en días hábiles</strong>. El equipo verificará tu pago y confirmará tu reserva.
                                    </p>

                                    <div class="flex gap-2">
                                        <button type="submit" style="background-color: #22c55e;"
                                                class="!text-white px-2 py-1 rounded-lg text-xs font-semibold flex-1">
                                            Solicitar
                                        </button>
                                        <button type="button" onclick="closeModal('modal-{{ $tour->id }}')"
                                                class="bg-gray-100 text-gray-600 px-2 py-1 rounded-lg text-xs">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No hay tours disponibles en este momento.</p>
        @endif
    </div>
@endif

</div>
            </div>
        </div>
    </div>

<style>
    .modal-overlay {
        background-color: rgba(0,0,0,0);
        transition: background-color 0.25s ease;
    }
    .modal-overlay.open {
        background-color: rgba(0,0,0,0.75);
    }
    .modal-box {
        transform: scale(0.85) translateY(20px);
        opacity: 0;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .modal-overlay.open .modal-box {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
</style>

<script>
    function openModal(id) {
        const overlay = document.getElementById(id);
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        requestAnimationFrame(() => overlay.classList.add('open'));
    }
    function closeModal(id) {
        const overlay = document.getElementById(id);
        overlay.classList.remove('open');
        setTimeout(() => {
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
        }, 250);
    }
</script>
</x-app-layout>
