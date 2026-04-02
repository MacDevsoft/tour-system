<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Detalle del Tour
        </h2>
    </x-slot>

    @php
        $bank = \App\Models\BankAccount::active();
        $bookingsCountForTour = auth()->check() && auth()->user()->role === 'user'
            ? \App\Models\Booking::where('user_id', auth()->id())->where('tour_id', $tour->id)->where('status', '!=', 'rejected')->count()
            : 0;
        $alreadyBooked = $bookingsCountForTour > 0;
        $limitReached = $bookingsCountForTour >= 4;
        $paymentCloseDate = $tour->resolvedPaymentDeadline()?->format('d/m/Y');
        $paymentCloseDateLong = $tour->resolvedPaymentDeadline()?->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y');
        $formatDateLong = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') : null;
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

            <div class="mt-5 grid grid-cols-1 gap-4 text-sm text-slate-300 md:grid-cols-2">
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
                    <p>
                        <span class="font-semibold">Fecha inicio:</span> {{ $tour->fecha_inicio ?? 'N/A' }}
                        @if($tour->fecha_inicio)
                            <span class="block text-xs text-slate-500">{{ $formatDateLong($tour->fecha_inicio) }}</span>
                        @endif
                    </p>
                    <p>
                        <span class="font-semibold">Fecha fin:</span> {{ $tour->fecha_fin ?? 'N/A' }}
                        @if($tour->fecha_fin)
                            <span class="block text-xs text-slate-500">{{ $formatDateLong($tour->fecha_fin) }}</span>
                        @endif
                    </p>
                    @if($paymentCloseDate)
                        <p>
                            <span class="font-semibold">Liquidar antes del:</span> {{ $paymentCloseDate }}
                            <span class="block text-xs text-slate-500">{{ $paymentCloseDateLong }}</span>
                        </p>
                    @endif
                    <p>
                        <span class="font-semibold">Creado:</span> {{ $tour->created_at->format('d/m/Y H:i') }}
                        <span class="block text-xs text-slate-500">{{ $tour->created_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</span>
                    </p>
                    <p>
                        <span class="font-semibold">Actualizado:</span> {{ $tour->updated_at->format('d/m/Y H:i') }}
                        <span class="block text-xs text-slate-500">{{ $tour->updated_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</span>
                    </p>
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
                       class="inline-flex w-full items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold text-white sm:w-auto {{ $tour->is_enabled ? 'bg-red-600 hover:bg-red-500' : 'bg-emerald-600 hover:bg-emerald-500' }} transition">
                        @if($tour->is_enabled) Deshabilitar @else Habilitar @endif
                    </a>
                @else
                    <button type="button"
                            onclick="{{ $limitReached ? 'showReserveLimitModal(4)' : 'openReserveModal()' }}"
                            @if((int) $tour->cupos_disponibles <= 0 || !$tour->is_enabled) disabled @endif
                            class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto {{ $limitReached ? 'opacity-60 cursor-not-allowed bg-slate-700 hover:bg-slate-700' : '' }}">
                        @if((int) $tour->cupos_disponibles <= 0 || !$tour->is_enabled)
                            Sin cupo disponible
                        @elseif($limitReached)
                            Límite alcanzado (4/4)
                        @else
                            Reservar
                        @endif
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'user')
        <div id="reserve-limit-modal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4" style="background: rgba(2,6,23,.82);">
            <div class="w-full max-w-md rounded-2xl border border-amber-500/30 bg-slate-950 p-5 shadow-2xl shadow-black/40">
                <h4 class="text-lg font-bold text-amber-200">Límite alcanzado</h4>
                <p class="mt-2 text-sm text-slate-300">No puedes agregar más personas. El límite es de 4 registros por usuario en este tour.</p>
                <div class="mt-5 flex justify-end">
                    <button type="button" onclick="closeReserveLimitModal()" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-500">Entendido</button>
                </div>
            </div>
        </div>

        <div id="reserve-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(2,6,23,.82);">
            <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-3xl border border-white/10 bg-slate-950 p-4 shadow-2xl shadow-black/40 sm:p-6">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-white">Solicitar reservación</h3>
                        <p class="text-xs text-slate-400">{{ $tour->nombre }}</p>
                    </div>
                    <button type="button" onclick="closeReserveModal()" class="rounded-lg bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-600">
                        Cerrar
                    </button>
                </div>

                <div class="mb-3 rounded-lg border border-cyan-500/30 bg-cyan-500/10 px-3 py-2">
                    <p class="text-xs font-semibold text-cyan-200">Anticipo a pagar</p>
                    <p class="text-lg font-bold text-cyan-100">${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                </div>

                @if($bank)
                    <div class="mb-3 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-xs text-slate-300">
                        <p class="mb-1 font-bold text-white">Datos bancarios</p>
                        <p>{{ $bank->account_type }}</p>
                        <p><span class="font-medium">Banco:</span> {{ $bank->bank_name }}</p>
                        <p><span class="font-medium">Cuenta:</span> {{ $bank->account_number }}</p>
                        <p><span class="font-medium">Titular:</span> {{ $bank->account_holder }}</p>
                    </div>
                @else
                    <div class="mb-3 rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-xs text-amber-200">
                        Sin cuenta bancaria configurada.
                    </div>
                @endif

                @if($alreadyBooked)
                    <div class="mb-3 rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-2">
                        <p class="text-xs font-semibold text-amber-200">Ya te encuentras registrado en este tour.</p>
                        <p class="mt-1 text-xs text-amber-100">Si deseas agregar otra persona, confirma al enviar y escribe su nombre.</p>
                    </div>
                @endif

                <form id="reserve-form-detail" action="{{ route('bookings.store', $tour->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="confirm_additional" id="confirm_additional_detail" value="0">

                    <div class="mb-3">
                        <label class="mb-1 block text-xs font-semibold text-slate-300">Nombre de la persona a registrar (opcional)</label>
                        <input type="text" name="passenger_name" maxlength="120" placeholder="Déjalo vacío para usar tu nombre"
                               class="w-full rounded-lg border border-slate-700 bg-slate-900 px-2 py-1.5 text-xs text-white placeholder:text-slate-500">
                    </div>

                    <div class="mb-3">
                        <label class="mb-1 block text-xs font-semibold text-slate-300">Comprobante de pago</label>
                        <input type="file" name="receipt" accept="image/*" required
                               class="w-full rounded-lg border border-slate-700 bg-slate-900 px-2 py-1.5 text-xs text-slate-300">
                    </div>

                    <p class="mb-4 text-xs leading-4 text-slate-400">
                        Las transferencias pueden tardar hasta <strong>24 horas hábiles</strong>. Después verás tu plan de pagos en <strong>Mis tours</strong>.
                    </p>

                    <div class="flex gap-2">
                        <button type="submit" @if(!$bank) disabled @endif
                                class="flex-1 rounded-lg bg-emerald-600 px-2 py-2 text-xs font-semibold text-white transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50">
                            Solicitar
                        </button>
                        <button type="button" onclick="closeReserveModal()"
                                class="rounded-lg bg-slate-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-600">
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

            function showReserveLimitModal() {
                const modal = document.getElementById('reserve-limit-modal');
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeReserveLimitModal() {
                const modal = document.getElementById('reserve-limit-modal');
                if (!modal) return;
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            document.getElementById('reserve-modal')?.addEventListener('click', function (event) {
                if (event.target.id === 'reserve-modal') {
                    closeReserveModal();
                }
            });

            document.getElementById('reserve-limit-modal')?.addEventListener('click', function (event) {
                if (event.target.id === 'reserve-limit-modal') {
                    closeReserveLimitModal();
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
