<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Detalle del Tour
        </h2>
    </x-slot>

    @php
        $bank = \App\Models\BankAccount::active();
        $alreadyBooked = auth()->check() && auth()->user()->role === 'user'
            ? \App\Models\Booking::where('user_id', auth()->id())->where('tour_id', $tour->id)->exists()
            : false;
        $paymentCloseDate = $tour->resolvedPaymentDeadline()?->format('d/m/Y');
    @endphp

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-4 shadow-xl shadow-black/20 sm:p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white sm:text-3xl">{{ $tour->nombre }}</h1>
                    <p class="mt-2 text-sm text-slate-300 sm:text-base">{{ $tour->descripcion }}</p>
                </div>
                <span class="inline-flex w-fit rounded-full bg-cyan-500/15 px-3 py-1 text-xs font-semibold text-cyan-300">
                    {{ (int) ($tour->cupos_disponibles ?? 0) > 0 ? 'Disponible' : 'Sin cupo' }}
                </span>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 text-sm text-gray-300 md:grid-cols-2">
                <div class="space-y-2">
                    <p><span class="font-semibold">Precio total:</span> ${{ number_format($tour->precio_total, 2) }}</p>
                    <p><span class="font-semibold">Anticipo:</span> ${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                    <p><span class="font-semibold">Capacidad total:</span> {{ $tour->capacidad ?? $tour->cupos_totales }}</p>
                    <p><span class="font-semibold">Cupos disponibles:</span> {{ $tour->cupos_disponibles ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Transporte:</span> {{ $tour->transporte ?? 'No especificado' }}</p>
                    <p><span class="font-semibold">Hora de salida:</span> {{ $tour->hora_salida ?? 'No especificada' }}</p>
                </div>

                <div class="space-y-2">
                    <p><span class="font-semibold">Ubicación:</span> {{ $tour->ubicacion ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Punto de encuentro:</span> {{ $tour->punto_encuentro ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Fecha inicio:</span> {{ $tour->fecha_inicio ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Fecha fin:</span> {{ $tour->fecha_fin ?? 'N/A' }}</p>
                    @if($paymentCloseDate)
                        <p><span class="font-semibold">Liquidar antes del:</span> {{ $paymentCloseDate }}</p>
                    @endif
                    <p><span class="font-semibold">Creado:</span> {{ $tour->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Actualizado:</span> {{ $tour->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->role === 'user')
                <div class="mt-5 rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 text-sm text-slate-200">
                    <p class="font-semibold text-white mb-1">Esquema de pagos</p>
                    <p>
                        Apartas con <strong>${{ number_format($tour->anticipo ?? 0, 2) }}</strong>.
                        @if($tour->payment_installments)
                            El administrador configuró <strong>{{ $tour->payment_installments }} pago(s)</strong>
                            @if($paymentCloseDate)
                                para liquidar antes del <strong>{{ $paymentCloseDate }}</strong>.
                            @endif
                        @else
                            El resto se divide automáticamente en pagos quincenales los días <strong>1 y 15</strong>.
                            @if($paymentCloseDate)
                                Todo debe quedar liquidado antes del <strong>{{ $paymentCloseDate }}</strong>.
                            @endif
                        @endif
                    </p>
                </div>
            @endif

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="/tours" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-black sm:w-auto">Volver</a>
                @else
                    <a href="/dashboard" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-black sm:w-auto">Volver</a>
                @endif

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('tours.edit', $tour->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-yellow-400 px-4 py-2 text-sm font-semibold text-black sm:w-auto">Editar</a>
                    <a href="{{ route('tours.toggle', $tour->id) }}"
                       style="@if($tour->is_enabled) background-color: #dc2626; @else background-color: #16a34a; @endif"
                       class="inline-flex w-full items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold text-white sm:w-auto">
                        @if($tour->is_enabled) Deshabilitar @else Habilitar @endif
                    </a>
                @else
                    <button type="button"
                            onclick="openReserveModal()"
                            @if((int) $tour->cupos_disponibles <= 0 || !$tour->is_enabled) disabled @endif
                            style="background-color: #22c55e;"
                            class="inline-flex w-full items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                        @if((int) $tour->cupos_disponibles <= 0 || !$tour->is_enabled)
                            Sin cupo disponible
                        @else
                            Reservar
                        @endif
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'user')
        <div id="reserve-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(2,6,23,.82);">
            <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-3xl border border-white/10 bg-white p-4 shadow-2xl sm:p-6">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Solicitar reservación</h3>
                        <p class="text-xs text-gray-500">{{ $tour->nombre }}</p>
                    </div>
                    <button type="button" onclick="closeReserveModal()" class="rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white">
                        Cerrar
                    </button>
                </div>

                <div class="mb-3 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2">
                    <p class="text-xs font-semibold text-blue-600">Anticipo a pagar</p>
                    <p class="text-lg font-bold text-blue-700">${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                </div>

                @if($bank)
                    <div class="mb-3 rounded-lg border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-gray-600">
                        <p class="mb-1 font-bold">Datos bancarios</p>
                        <p>{{ $bank->account_type }}</p>
                        <p><span class="font-medium">Banco:</span> {{ $bank->bank_name }}</p>
                        <p><span class="font-medium">Cuenta:</span> {{ $bank->account_number }}</p>
                        <p><span class="font-medium">Titular:</span> {{ $bank->account_holder }}</p>
                    </div>
                @else
                    <div class="mb-3 rounded-lg border border-yellow-100 bg-yellow-50 px-3 py-2 text-xs text-yellow-700">
                        ⚠️ Sin cuenta bancaria configurada.
                    </div>
                @endif

                @if($alreadyBooked)
                    <div class="mb-3 rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2">
                        <p class="text-xs font-semibold text-yellow-700">Ya te encuentras registrado en este tour.</p>
                        <p class="mt-1 text-xs text-yellow-700">Si deseas agregar otra persona, confirma al enviar y escribe su nombre.</p>
                    </div>
                @endif

                <form id="reserve-form-detail" action="{{ route('bookings.store', $tour->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="confirm_additional" id="confirm_additional_detail" value="0">

                    <div class="mb-3">
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Nombre de la persona a registrar (opcional)</label>
                        <input type="text" name="passenger_name" maxlength="120" placeholder="Déjalo vacío para usar tu nombre"
                               class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-xs text-gray-700">
                    </div>

                    <div class="mb-3">
                        <label class="mb-1 block text-xs font-semibold text-gray-600">📎 Comprobante de pago</label>
                        <input type="file" name="receipt" accept="image/*" required
                               class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-xs text-gray-500">
                    </div>

                    <p class="mb-4 text-xs leading-4 text-gray-500">
                        Las transferencias pueden tardar hasta <strong>24 horas hábiles</strong>. Después verás tu plan de pagos en <strong>Mis tours</strong>.
                    </p>

                    <div class="flex gap-2">
                        <button type="submit" @if(!$bank) disabled @endif style="background-color: #22c55e;"
                                class="flex-1 rounded-lg px-2 py-2 text-xs font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50">
                            Solicitar
                        </button>
                        <button type="button" onclick="closeReserveModal()"
                                class="rounded-lg bg-gray-100 px-3 py-2 text-xs text-gray-600">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openReserveModal() {
                const modal = document.getElementById('reserve-modal');
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeReserveModal() {
                const modal = document.getElementById('reserve-modal');
                if (!modal) return;
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            document.getElementById('reserve-modal')?.addEventListener('click', function (event) {
                if (event.target.id === 'reserve-modal') {
                    closeReserveModal();
                }
            });

            document.getElementById('reserve-form-detail')?.addEventListener('submit', function (event) {
                const alreadyBooked = @json($alreadyBooked);
                const confirmInput = document.getElementById('confirm_additional_detail');

                if (alreadyBooked && confirmInput && confirmInput.value !== '1') {
                    const accepted = window.confirm('Ya te encuentras registrado en este tour. ¿Deseas agregar otra persona a tu nombre?');
                    if (!accepted) {
                        event.preventDefault();
                        return;
                    }

                    confirmInput.value = '1';
                }
            });
        </script>
    @endif
</x-app-layout>
