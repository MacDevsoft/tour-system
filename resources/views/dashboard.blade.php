<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Inicio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg" style="background-color: #111827;">
                <div class="p-6 text-gray-900">
    
   <h2 class="text-2xl text-white font-bold mb-4">
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
        <h3 class="text-2xl font-bold mb-6 text-center w-full text-white">Tours Disponibles</h3>

        @if(session('status'))
            <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                {{ session('status') }}
            </div>
        @endif
        
        @php
            $tours = \App\Models\Tour::where('is_enabled', true)->get();
            $myBookedTourIds = \App\Models\Booking::where('user_id', auth()->id())->pluck('tour_id')->flip();
        @endphp

        @if($tours->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
                @foreach($tours as $tour)
                    @php $alreadyBooked = isset($myBookedTourIds[$tour->id]); @endphp
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md p-4 text-center">

                        <h3 class="text-base font-bold mb-1">
                            {{ $tour->nombre }}
                        </h3>

                        <p class="text-gray-600 text-xs mb-2 truncate">
                            {{ $tour->descripcion }}
                        </p>

                        <div class="text-xs text-gray-700 space-y-1 flex flex-col items-center">
                            <p>📍 {{ $tour->ubicacion }}</p>
                            <p>📅 {{ $tour->fecha_inicio }} → {{ $tour->fecha_fin }}</p>
                            <p class="text-green-600 font-bold">💰 ${{ number_format($tour->precio_total, 2) }}</p>
                            <p>👥 {{ $tour->cupos_disponibles }} / {{ $tour->cupos_totales }}</p>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('tours.show', $tour->id) }}" 
                               class="bg-blue-500 text-black px-3 py-1.5 rounded text-xs inline-block">
                                Ver detalles
                            </a>
                            <br>
                            <button onclick="openModal('modal-{{ $tour->id }}')"
                               style="background-color: #22c55e;"
                               class="!text-white px-3 py-1.5 rounded-xl shadow-md inline-block mt-1.5 w-full text-center font-semibold text-xs">
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

                                <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 mb-3">
                                    <p class="text-[11px] text-slate-600 leading-4">
                                        @if($tour->payment_installments)
                                            Este tour está configurado a <strong>{{ $tour->payment_installments }} pago(s)</strong>
                                            @if($tour->resolvedPaymentDeadline())
                                                con liquidación máxima al <strong>{{ $tour->resolvedPaymentDeadline()->format('d/m/Y') }}</strong>.
                                            @endif
                                        @else
                                            El resto se dividirá automáticamente en pagos quincenales el <strong>día 1 y 15</strong>.
                                            Todo debe quedar liquidado <strong>15 días antes del tour</strong> y cada fecha tiene <strong>3 días de tolerancia</strong>.
                                        @endif
                                    </p>
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

                                <form action="{{ route('bookings.store', $tour->id) }}" method="POST" enctype="multipart/form-data" class="reserve-form" data-already-booked="{{ $alreadyBooked ? '1' : '0' }}">
                                    @csrf
                                    <input type="hidden" name="confirm_additional" value="0" class="confirm-additional-input">

                                    @if($alreadyBooked)
                                        <div class="mb-3 rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2">
                                            <p class="text-xs text-yellow-700 font-semibold">
                                                Ya te encuentras registrado en este tour.
                                            </p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                Si deseas agregar otra persona, confirma al enviar y escribe su nombre (opcional).
                                            </p>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold mb-1 text-gray-600">Nombre de la persona a registrar (opcional)</label>
                                        <input type="text" name="passenger_name" maxlength="120" placeholder="Déjalo vacío para usar tu nombre"
                                               class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-gray-700">
                                    </div>

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

    <div id="booking-confirm-overlay" class="fixed inset-0 z-[70] hidden items-center justify-center p-4" style="background: rgba(0,0,0,.7);">
        <div class="w-full max-w-md rounded-2xl border border-gray-700 bg-gray-900 p-6 shadow-2xl">
            <h4 class="text-lg font-bold text-white mb-2">Confirmar reservacion</h4>
            <p class="text-sm text-gray-300 mb-6">
                Ya te encuentras registrado en este tour. ¿Deseas agregar otra persona a tu nombre?
            </p>
            <div class="flex justify-end gap-3">
                <button id="booking-confirm-cancel" type="button" class="rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                    No, cancelar
                </button>
                <button id="booking-confirm-accept" type="button" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                    Si, continuar
                </button>
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
    const bookingConfirmOverlay = document.getElementById('booking-confirm-overlay');
    const bookingConfirmAccept = document.getElementById('booking-confirm-accept');
    const bookingConfirmCancel = document.getElementById('booking-confirm-cancel');
    let bookingConfirmResolver = null;

    function showBookingConfirmModal() {
        return new Promise((resolve) => {
            bookingConfirmResolver = resolve;
            bookingConfirmOverlay.classList.remove('hidden');
            bookingConfirmOverlay.classList.add('flex');
        });
    }

    function closeBookingConfirmModal(result) {
        bookingConfirmOverlay.classList.remove('flex');
        bookingConfirmOverlay.classList.add('hidden');

        if (bookingConfirmResolver) {
            bookingConfirmResolver(result);
            bookingConfirmResolver = null;
        }
    }

    bookingConfirmAccept.addEventListener('click', () => closeBookingConfirmModal(true));
    bookingConfirmCancel.addEventListener('click', () => closeBookingConfirmModal(false));

    bookingConfirmOverlay.addEventListener('click', (event) => {
        if (event.target === bookingConfirmOverlay) {
            closeBookingConfirmModal(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !bookingConfirmOverlay.classList.contains('hidden')) {
            closeBookingConfirmModal(false);
        }
    });

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

    document.querySelectorAll('.reserve-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            const alreadyBooked = form.dataset.alreadyBooked === '1';
            const confirmInput = form.querySelector('.confirm-additional-input');

            if (!alreadyBooked || !confirmInput || confirmInput.value === '1') {
                return;
            }

            event.preventDefault();

            const proceed = await showBookingConfirmModal();

            if (!proceed) {
                const overlay = form.closest('.modal-overlay');
                if (overlay) {
                    closeModal(overlay.id);
                }
                return;
            }

            confirmInput.value = '1';
            form.requestSubmit();
        });
    });
</script>
</x-app-layout>
