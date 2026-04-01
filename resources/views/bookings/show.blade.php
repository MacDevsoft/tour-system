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

                @if($pendingBookings->count() === 0 && $approvedBookings->count() === 0 && $cancelledBookings->count() === 0)
                    <p class="text-slate-300">No hay reservas para este tour.</p>
                @endif

                @if($pendingBookings->count() > 0)
                    <div class="mb-8">
                        <h4 class="text-lg font-bold text-yellow-400 mb-3">Pendientes</h4>
                        <div class="space-y-5">
                            @foreach($pendingBookings as $booking)
                                <div class="rounded-2xl border border-yellow-800 bg-slate-900/60 p-4">
                                    <p class="text-base font-bold text-white">Reserva de {{ $booking->passenger_name ?: $booking->user->name }}</p>
                                    <p class="text-xs text-slate-300">{{ $booking->purchase_id }}</p>
                                    @include('bookings.partials.payment-plan', ['booking' => $booking, 'prefix' => 'show-pending-'.$booking->id])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($approvedBookings->count() > 0)
                    <div class="mb-8">
                        <h4 class="text-lg font-bold text-green-400 mb-3">Aprobadas</h4>
                        <div class="space-y-5">
                            @foreach($approvedBookings as $booking)
                                <div class="rounded-2xl border border-green-800 bg-slate-900/60 p-4">
                                    <p class="text-base font-bold text-white">Reserva de {{ $booking->passenger_name ?: $booking->user->name }}</p>
                                    <p class="text-xs text-slate-300">{{ $booking->purchase_id }}</p>
                                    @include('bookings.partials.payment-plan', ['booking' => $booking, 'prefix' => 'show-approved-'.$booking->id])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($cancelledBookings->count() > 0)
                    <div>
                        <h4 class="text-lg font-bold text-red-400 mb-3">Canceladas</h4>
                        <div class="space-y-5">
                            @foreach($cancelledBookings as $booking)
                                <div class="rounded-2xl border border-red-800 bg-slate-900/60 p-4">
                                    <p class="text-base font-bold text-white">Reserva de {{ $booking->passenger_name ?: $booking->user->name }}</p>
                                    <p class="text-xs text-slate-300">{{ $booking->purchase_id }}</p>
                                    @include('bookings.partials.payment-plan', ['booking' => $booking, 'prefix' => 'show-cancelled-'.$booking->id])
                                </div>
                            @endforeach
                        </div>
                    </div>
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
