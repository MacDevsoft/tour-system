<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ $tour->nombre }}</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">Detalle de mis reservas</h3>
                        <p class="text-sm text-slate-400">Revisa el estado y pagos de este tour.</p>
                    </div>
                    <a href="{{ route('bookings.my-tours', ['tour_id' => $tour->id]) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-black md:w-auto">Volver</a>
                </div>

                @if(session('status'))
                    <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                        {{ session('status') }}
                    </div>
                @endif

                @php
                    $allBookings = $pendingBookings->concat($approvedBookings)->concat($cancelledBookings)->values();
                    $selectedBookingId = (int) request('booking_id', optional($allBookings->first())->id);
                    $selectedBooking = $allBookings->firstWhere('id', $selectedBookingId) ?? $allBookings->first();
                @endphp

                @if($allBookings->isEmpty())
                    <p class="text-slate-300">No hay reservas para este tour.</p>
                @else
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-2xl font-bold text-white">Mis personas registradas</h4>
                            <span class="text-xs text-slate-400">{{ $allBookings->count() }} reservación(es)</span>
                        </div>

                        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($allBookings as $bookingOption)
                                @php
                                    $isActiveBooking = optional($selectedBooking)->id === $bookingOption->id;
                                    $statusClasses = match($bookingOption->status) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default => 'bg-yellow-100 text-yellow-700',
                                    };
                                    $statusLabel = match($bookingOption->status) {
                                        'approved' => 'Aprobada',
                                        'rejected' => 'Cancelada',
                                        default => 'Pendiente',
                                    };
                                @endphp
                                <a href="{{ route('bookings.show-tour', ['tour' => $tour->id, 'booking_id' => $bookingOption->id]) }}"
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
                                'approved' => 'border-green-800',
                                'rejected' => 'border-red-800',
                                default => 'border-yellow-800',
                            };
                            $badgeClasses = match($selectedBooking->status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-yellow-100 text-yellow-700',
                            };
                            $badgeLabel = match($selectedBooking->status) {
                                'approved' => 'Aprobada',
                                'rejected' => 'Cancelada',
                                default => 'Pendiente',
                            };
                        @endphp

                        <div class="rounded-2xl border {{ $headerClasses }} bg-slate-900/60 p-4">
                            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-base font-bold text-white">Esquema de pago de {{ $selectedBooking->passenger_name ?: $selectedBooking->user->name }}</p>
                                    <p class="text-xs text-slate-300">{{ $selectedBooking->purchase_id }}</p>
                                </div>
                                <span class="inline-block rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClasses }}">{{ $badgeLabel }}</span>
                            </div>

                            @include('bookings.partials.payment-plan', ['booking' => $selectedBooking, 'prefix' => 'show-selected-'.$selectedBooking->id])
                        </div>
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
