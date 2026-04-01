<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ $tour->nombre }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color:#111827;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white">Detalle de mis reservas</h3>
                    <a href="{{ route('bookings.my-tours', ['tour_id' => $tour->id]) }}" class="bg-gray-200 text-black px-4 py-2 rounded">Volver</a>
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

                        <div class="flex flex-wrap gap-3">
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
                                   class="rounded-xl border px-4 py-3 transition"
                                   style="border-color: {{ $isActiveBooking ? '#22c55e' : '#475569' }}; background-color: {{ $isActiveBooking ? '#0b1220' : '#111827' }}; min-width: 13rem;">
                                    <p class="text-sm font-bold text-white">{{ $bookingOption->passenger_name ?: $bookingOption->user->name }}</p>
                                    <p class="text-[11px] text-slate-300">{{ $bookingOption->purchase_id }}</p>
                                    <span class="mt-2 inline-block rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">{{ $statusLabel }}</span>
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

    <div id="receipt-modal" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background: rgba(0,0,0,.75);">
        <div class="bg-white rounded-xl shadow-xl relative" style="width:min(92vw, 420px); padding:14px 12px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <button type="button" onclick="closeReceiptModal()" style="position:absolute; top:10px; right:10px; z-index:10; background-color:#dc2626;color:#ffffff;" class="px-2 py-1 rounded text-xs font-semibold">
                Cerrar
            </button>
            <h4 class="text-base font-bold mb-3">Comprobante</h4>
            <img id="receipt-modal-image" src="" alt="Comprobante" class="mx-auto rounded border bg-gray-50" style="width:220px;height:320px;object-fit:contain;display:block;">
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
