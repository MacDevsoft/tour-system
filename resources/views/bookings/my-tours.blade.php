<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Mis tours</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @if(session('status'))
                    <div class="rounded-2xl border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800 shadow">
                        {{ session('status') }}
                    </div>
                @endif

                @if($tourGroups->count() === 0)
                    <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-xl shadow-black/20">
                        <h3 class="text-2xl font-bold text-white">Mis tours</h3>
                        <p class="mt-2 text-sm text-slate-400">Aún no tienes reservaciones registradas.</p>
                        <a href="{{ route('dashboard') }}#tours-disponibles" class="mt-5 inline-flex rounded-xl bg-cyan-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                            Explorar tours disponibles
                        </a>
                    </div>
                @else
                    @php
                        $allBookings = $tourGroups->flatten(1);
                        $formatHumanDate = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') : 'Por confirmar';
                        $selectedTourId = (int) request('tour_id', $tourGroups->keys()->first());
                        $selectedGroup = $tourGroups->get($selectedTourId) ?? $tourGroups->first();
                        $selectedTour = optional($selectedGroup->first())->tour;
                        $activeCount = $allBookings->where('status', '!=', 'rejected')->count();
                        $approvedCount = $allBookings->where('status', 'approved')->count();
                        $pendingCount = $allBookings->where('status', 'pending')->count();
                        $cancelledCount = $allBookings->where('status', 'rejected')->count();
                        $pendingBalance = $allBookings->sum(fn ($booking) => $booking->remainingAmount());
                        $nextDuePayment = $allBookings
                            ->flatMap(fn ($booking) => $booking->payments ?? collect())
                            ->whereIn('status', ['pending', 'late', 'submitted'])
                            ->sortBy('due_date')
                            ->first();
                    @endphp

                    <section class="relative overflow-hidden rounded-3xl border border-cyan-500/20 bg-gradient-to-r from-slate-950 via-cyan-950/60 to-slate-950 px-6 py-7 shadow-2xl shadow-cyan-950/20">
                        <div class="absolute -right-8 top-0 h-40 w-40 rounded-full bg-cyan-500/20 blur-3xl"></div>
                        <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">
                                    Mis reservaciones
                                </span>
                                <h3 class="mt-3 text-3xl font-black text-white">Consulta tus viajes y pagos</h3>
                                <p class="mt-2 max-w-2xl text-sm text-slate-300 md:text-base">
                                    Revisa el estado de cada persona registrada, el avance de pagos y las fechas límite de tus tours.
                                </p>
                            </div>

                            <a href="{{ route('dashboard') }}#tours-disponibles" class="inline-flex rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                                Ver más tours
                            </a>
                        </div>
                    </section>

                    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Tours reservados</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ $tourGroups->count() }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $activeCount }} activos</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Reservaciones</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ $allBookings->count() }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $approvedCount }} aprobadas · {{ $pendingCount }} pendientes</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Próximo pago</p>
                            <p class="mt-2 text-xl font-black text-white">{{ $nextDuePayment ? '$' . number_format($nextDuePayment->amount, 2) : 'Sin pagos' }}</p>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $nextDuePayment ? 'Vence ' . $nextDuePayment->due_date->format('d/m/Y') : 'Sin pendientes' }}
                                @if($nextDuePayment)
                                    <span class="block text-[11px] text-slate-500">{{ $nextDuePayment->due_date->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Saldo pendiente</p>
                            <p class="mt-2 text-2xl font-black text-white">${{ number_format($pendingBalance, 2) }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $cancelledCount > 0 ? $cancelledCount . ' cancelada(s)' : 'Sin cancelaciones' }}</p>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-white">Selecciona un tour</h4>
                                <p class="text-sm text-slate-400">Cada tarjeta agrupa tus personas registradas dentro de ese viaje.</p>
                            </div>
                            <span class="text-xs text-slate-400">{{ $tourGroups->count() }} tour(es)</span>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($tourGroups as $tourGroup)
                                @php
                                    $booking = $tourGroup->first();
                                    $tour = $booking->tour;
                                    $isSelected = (int) $tour->id === (int) optional($selectedTour)->id;
                                @endphp
                                <a href="{{ route('bookings.my-tours', ['tour_id' => $tour->id]) }}"
                                   class="rounded-2xl border p-4 transition duration-200 {{ $isSelected ? 'border-emerald-400/50 bg-emerald-500/10 shadow-lg shadow-emerald-950/20' : 'border-white/10 bg-slate-950/70 hover:border-cyan-400/40 hover:bg-slate-950' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-lg font-bold text-white">{{ $tour->nombre }}</p>
                                            <p class="mt-1 text-sm text-slate-300">{{ $tour->ubicacion ?: 'Ubicación por confirmar' }}</p>
                                        </div>
                                        <span class="rounded-full bg-cyan-500/15 px-2.5 py-1 text-[11px] font-semibold text-cyan-300">
                                            {{ $tourGroup->count() }} persona(s)
                                        </span>
                                    </div>
                                    <div class="mt-4 grid gap-2 text-sm text-slate-300">
                                        <div class="rounded-xl border border-white/5 bg-slate-900/80 px-3 py-2">
                                            <p>📅 {{ $tour->fecha_inicio ?? 'Sin fecha' }} {{ $tour->fecha_fin ? '→ ' . $tour->fecha_fin : '' }}</p>
                                            @if($tour->fecha_inicio)
                                                <p class="text-[11px] text-slate-500">{{ $formatHumanDate($tour->fecha_inicio) }}{{ $tour->fecha_fin ? ' → ' . $formatHumanDate($tour->fecha_fin) : '' }}</p>
                                            @endif
                                        </div>
                                        <div class="rounded-xl border border-white/5 bg-slate-900/80 px-3 py-2">👥 Cupos: {{ $tour->cupos_disponibles }}/{{ $tour->cupos_totales }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    @if($selectedTour)
                        @php
                            $selectedBookingId = (int) request('booking_id', optional($selectedGroup->first())->id);
                            $selectedBooking = $selectedGroup->firstWhere('id', $selectedBookingId) ?? $selectedGroup->first();
                            $paymentCloseDate = $selectedTour->resolvedPaymentDeadline()?->format('d/m/Y') ?? 'N/D';
                        @endphp

                        <section class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                            <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-2xl border border-white/10 bg-slate-950/70 p-4">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Tour seleccionado</p>
                                    <p class="mt-2 text-lg font-bold text-white">{{ $selectedTour->nombre }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-slate-950/70 p-4">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Precio total</p>
                                    <p class="mt-2 text-lg font-bold text-cyan-300">${{ number_format($selectedTour->precio_total ?? 0, 2) }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-slate-950/70 p-4">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Anticipo</p>
                                    <p class="mt-2 text-lg font-bold text-emerald-300">${{ number_format($selectedTour->anticipo ?? 0, 2) }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-slate-950/70 p-4">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Liquidar antes del</p>
                                    <p class="mt-2 text-lg font-bold text-amber-300">{{ $paymentCloseDate }}</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h4 class="text-2xl font-bold text-white">Personas registradas</h4>
                                        <p class="text-sm text-slate-400">Selecciona una reservación para revisar su esquema de pago.</p>
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $selectedGroup->count() }} registro(s)</span>
                                </div>

                                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach($selectedGroup as $bookingOption)
                                        @php
                                            $isActiveBooking = optional($selectedBooking)->id === $bookingOption->id;
                                            $statusClasses = match($bookingOption->status) {
                                                'approved' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-400/20',
                                                'rejected' => 'bg-red-500/15 text-red-300 border border-red-400/20',
                                                default => 'bg-amber-500/15 text-amber-300 border border-amber-400/20',
                                            };
                                            $statusLabel = match($bookingOption->status) {
                                                'approved' => 'Aprobada',
                                                'rejected' => 'Cancelada',
                                                default => 'Pendiente',
                                            };
                                        @endphp
                                        <a href="{{ route('bookings.my-tours', ['tour_id' => $selectedTour->id, 'booking_id' => $bookingOption->id]) }}"
                                           class="rounded-2xl border p-4 transition duration-200 {{ $isActiveBooking ? 'border-emerald-400/50 bg-emerald-500/10 shadow-lg shadow-emerald-950/20' : 'border-white/10 bg-slate-950/70 hover:border-cyan-400/40' }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-bold text-white">{{ $bookingOption->passenger_name ?: $bookingOption->user->name }}</p>
                                                    <p class="mt-1 text-[11px] text-slate-300">{{ $bookingOption->purchase_id }}</p>
                                                </div>
                                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">{{ $statusLabel }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            @if($selectedBooking)
                                @php
                                    $headerClasses = match($selectedBooking->status) {
                                        'approved' => 'border-emerald-500/30',
                                        'rejected' => 'border-red-500/30',
                                        default => 'border-amber-500/30',
                                    };
                                    $badgeClasses = match($selectedBooking->status) {
                                        'approved' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-400/20',
                                        'rejected' => 'bg-red-500/15 text-red-300 border border-red-400/20',
                                        default => 'bg-amber-500/15 text-amber-300 border border-amber-400/20',
                                    };
                                    $badgeLabel = match($selectedBooking->status) {
                                        'approved' => 'Aprobada',
                                        'rejected' => 'Cancelada',
                                        default => 'Pendiente',
                                    };
                                @endphp

                                <div class="rounded-3xl border {{ $headerClasses }} bg-slate-950/70 p-4 shadow-inner shadow-black/20">
                                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-white">Detalle de {{ $selectedBooking->passenger_name ?: $selectedBooking->user->name }}</p>
                                            <p class="text-xs text-slate-300">{{ $selectedBooking->purchase_id }} · registrada el {{ $selectedBooking->created_at->format('d/m/Y H:i') }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $selectedBooking->created_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</p>
                                        </div>
                                        <span class="inline-block rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClasses }}">{{ $badgeLabel }}</span>
                                    </div>

                                    @include('bookings.partials.payment-plan', ['booking' => $selectedBooking, 'prefix' => 'selected-'.$selectedBooking->id])
                                </div>
                            @endif
                        </section>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div id="receipt-modal" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background: rgba(2,6,23,.82);">
        <div class="relative flex w-full max-w-md flex-col items-center justify-center rounded-3xl border border-white/10 bg-slate-950 p-4 shadow-2xl shadow-black/40">
            <button type="button" onclick="closeReceiptModal()" class="absolute right-3 top-3 rounded-lg bg-red-600 px-2.5 py-1 text-xs font-semibold text-white">
                Cerrar
            </button>
            <h4 class="mb-3 text-base font-bold text-white">Comprobante</h4>
            <img id="receipt-modal-image" src="" alt="Comprobante" class="mx-auto rounded-xl border border-slate-700 bg-slate-900" style="width:220px;height:320px;object-fit:contain;display:block;">
        </div>
    </div>

    <script>
        function openReceiptModal(imageUrl) {
            const modal = document.getElementById('receipt-modal');
            const img = document.getElementById('receipt-modal-image');
            img.src = imageUrl;
            modal.style.display = 'flex';
        }

        function closeReceiptModal() {
            const modal = document.getElementById('receipt-modal');
            const img = document.getElementById('receipt-modal-image');
            modal.style.display = 'none';
            img.src = '';
        }

        document.getElementById('receipt-modal').addEventListener('click', function (e) {
            if (e.target.id === 'receipt-modal') {
                closeReceiptModal();
            }
        });
    </script>
</x-app-layout>
