<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Mis tours</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color: #111827;">
                <h3 class="text-2xl font-bold mb-6 text-white">Mis reservas</h3>

                @if(session('status'))
                    <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if($tourGroups->count() === 0)
                    <p class="text-gray-500">Aún no has reservado ningún tour.</p>
                @else
                    @php
                        $selectedTourId = (int) request('tour_id', $tourGroups->keys()->first());
                        $selectedGroup = $tourGroups->get($selectedTourId) ?? $tourGroups->first();
                        $selectedTour = optional($selectedGroup->first())->tour;
                    @endphp

                    <div class="mb-8">
                        <div class="flex flex-wrap justify-center gap-6">
                            @foreach($tourGroups as $tourGroup)
                                @php
                                    $booking = $tourGroup->first();
                                    $tour = $booking->tour;
                                    $isSelected = (int) $tour->id === (int) optional($selectedTour)->id;
                                @endphp
                                <a
                                    href="{{ route('bookings.my-tours', ['tour_id' => $tour->id]) }}"
                                    class="rounded-2xl transition block"
                                    style="width:13rem;padding:1rem;border:1px solid {{ $isSelected ? '#22c55e' : '#334155' }};background-color:#0f172a;box-shadow:{{ $isSelected ? '0 0 0 3px rgba(34,197,94,.35)' : '0 4px 12px rgba(0,0,0,.20)' }};"
                                >
                                    <h4 class="text-lg font-bold text-white text-center mb-2 uppercase leading-tight">
                                        {{ $tour->nombre }}
                                    </h4>
                                    <p class="text-sm text-gray-200 text-center mb-1 leading-tight">
                                        {{ $tour->fecha_inicio ?? 'Sin fecha' }} {{ $tour->fecha_fin ? '→ ' . $tour->fecha_fin : '' }}
                                    </p>
                                    <p class="text-sm text-white font-semibold text-center leading-tight">
                                        Cupos: {{ $tour->cupos_disponibles }}/{{ $tour->cupos_totales }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if($selectedTour)
                        @php
                            $pendingBookings = $selectedGroup->where('status', 'pending')->values();
                            $approvedBookings = $selectedGroup->where('status', 'approved')->values();
                            $cancelledBookings = $selectedGroup->where('status', 'rejected')->values();
                            $paymentCloseDate = filled($selectedTour->fecha_inicio)
                                ? \Illuminate\Support\Carbon::parse($selectedTour->fecha_inicio)->subDays(15)->format('d/m/Y')
                                : 'N/D';
                        @endphp

                        <div class="rounded-2xl border border-gray-700 p-6" style="background-color:#0f172a;">
                            <div class="mb-6 grid gap-3 md:grid-cols-4">
                                <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Tour seleccionado</p>
                                    <p class="mt-1 text-lg font-bold text-white">{{ $selectedTour->nombre }}</p>
                                </div>
                                <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Precio total</p>
                                    <p class="mt-1 text-lg font-bold text-cyan-300">${{ number_format($selectedTour->precio_total ?? 0, 2) }}</p>
                                </div>
                                <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Anticipo</p>
                                    <p class="mt-1 text-lg font-bold text-emerald-300">${{ number_format($selectedTour->anticipo ?? 0, 2) }}</p>
                                </div>
                                <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Liquidar antes del</p>
                                    <p class="mt-1 text-lg font-bold text-amber-300">{{ $paymentCloseDate }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-2xl font-bold text-white">Detalle de mis reservas</h4>
                            </div>

                            @if($pendingBookings->count() === 0 && $approvedBookings->count() === 0 && $cancelledBookings->count() === 0)
                                <p class="text-slate-300">No hay reservas registradas para este tour.</p>
                            @endif

                            @if($pendingBookings->count() > 0)
                                <div class="mb-8">
                                    <h5 class="text-lg font-bold text-yellow-400 mb-3">Pendientes de aprobación</h5>
                                    <div class="space-y-5">
                                        @foreach($pendingBookings as $booking)
                                            <div class="rounded-2xl border border-yellow-800 bg-slate-900/60 p-4">
                                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                    <div>
                                                        <p class="text-base font-bold text-white">Reserva de {{ $booking->passenger_name ?: $booking->user->name }}</p>
                                                        <p class="text-xs text-slate-300">{{ $booking->purchase_id }} · registrada el {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                                                    </div>
                                                    <span class="inline-block rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">Pendiente</span>
                                                </div>

                                                @include('bookings.partials.payment-plan', ['booking' => $booking, 'prefix' => 'pending-'.$booking->id])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($approvedBookings->count() > 0)
                                <div class="mb-8">
                                    <h5 class="text-lg font-bold text-green-400 mb-3">Aprobadas</h5>
                                    <div class="space-y-5">
                                        @foreach($approvedBookings as $booking)
                                            <div class="rounded-2xl border border-green-800 bg-slate-900/60 p-4">
                                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                    <div>
                                                        <p class="text-base font-bold text-white">Reserva de {{ $booking->passenger_name ?: $booking->user->name }}</p>
                                                        <p class="text-xs text-slate-300">{{ $booking->purchase_id }} · aprobada el {{ optional($booking->approved_at)->format('d/m/Y H:i') ?: 'Pendiente' }}</p>
                                                    </div>
                                                    <span class="inline-block rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Aprobada</span>
                                                </div>

                                                @include('bookings.partials.payment-plan', ['booking' => $booking, 'prefix' => 'approved-'.$booking->id])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($cancelledBookings->count() > 0)
                                <div>
                                    <h5 class="text-lg font-bold text-red-400 mb-3">Canceladas</h5>
                                    <div class="space-y-5">
                                        @foreach($cancelledBookings as $booking)
                                            <div class="rounded-2xl border border-red-800 bg-slate-900/60 p-4">
                                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                    <div>
                                                        <p class="text-base font-bold text-white">Reserva de {{ $booking->passenger_name ?: $booking->user->name }}</p>
                                                        <p class="text-xs text-slate-300">{{ $booking->purchase_id }} · {{ $booking->cancellation_reason ?: 'Cancelada por falta de pago' }}</p>
                                                    </div>
                                                    <span class="inline-block rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Cancelada</span>
                                                </div>

                                                @include('bookings.partials.payment-plan', ['booking' => $booking, 'prefix' => 'cancelled-'.$booking->id])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
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
