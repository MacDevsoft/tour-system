<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Detalle de pagos del usuario</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color:#111827;">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">{{ $booking->user->name }}</h3>
                        <p class="text-sm text-slate-300">{{ $booking->user->email }} · Tour: {{ $booking->tour->nombre }}</p>
                    </div>
                    <a href="{{ route('admin.index', ['tour_id' => $booking->tour_id, 'status' => $booking->status === 'rejected' ? 'rejected' : ($booking->status === 'approved' ? 'approved' : 'pending')]) }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-black">
                        Volver
                    </a>
                </div>

                @if(session('status'))
                    <div class="mt-4 rounded-lg bg-green-100 px-4 py-2 text-green-700">{{ session('status') }}</div>
                @endif

                @php
                    $deadline = $booking->paymentDeadline();
                    $totalPaid = $booking->totalApprovedPayments();
                    $remaining = max(0, (float) ($booking->tour->precio_total ?? 0) - $totalPaid);
                @endphp

                <div class="mt-5 grid gap-3 md:grid-cols-4">
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Total tour</p>
                        <p class="mt-1 text-lg font-bold text-white">${{ number_format($booking->tour->precio_total ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Liquidado</p>
                        <p class="mt-1 text-lg font-bold text-emerald-300">${{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Saldo pendiente</p>
                        <p class="mt-1 text-lg font-bold text-amber-300">${{ number_format($remaining, 2) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-3">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Límite final</p>
                        <p class="mt-1 text-lg font-bold text-cyan-300">{{ $deadline ? $deadline->format('d/m/Y') : 'N/D' }}</p>
                    </div>
                </div>

                @if($booking->status === 'rejected')
                    <div class="mt-4 rounded-xl border border-red-700 bg-red-950/40 px-4 py-3 text-sm text-red-100">
                        <p class="font-semibold">Reserva cancelada</p>
                        <p>{{ $booking->cancellation_reason ?: 'La reserva fue cancelada por falta de pago.' }}</p>
                    </div>
                @endif

                <div class="mt-6 rounded-xl border border-slate-700 bg-slate-900 p-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-lg font-bold text-white">Anticipo inicial</p>
                            <p class="text-sm text-slate-300">ID {{ $booking->purchase_id }} · Registrado el {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">
                                Ver comprobante
                            </button>
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white">Aprobar reserva</button>
                                </form>
                            @elseif($booking->status === 'approved')
                                <span class="inline-block rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Reserva aprobada</span>
                            @else
                                <span class="inline-block rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Cancelada</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto rounded-xl border border-slate-700">
                    <table class="w-full text-sm text-slate-200">
                        <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="px-3 py-3 text-center font-semibold">Pago</th>
                                <th class="px-3 py-3 text-center font-semibold">Monto</th>
                                <th class="px-3 py-3 text-center font-semibold">Fecha límite</th>
                                <th class="px-3 py-3 text-center font-semibold">Tolerancia</th>
                                <th class="px-3 py-3 text-center font-semibold">Estado</th>
                                <th class="px-3 py-3 text-center font-semibold">Comprobante</th>
                                <th class="px-3 py-3 text-center font-semibold">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 bg-slate-950/50">
                            @forelse($booking->payments as $payment)
                                @php
                                    $statusClasses = match($payment->status) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'submitted' => 'bg-blue-100 text-blue-700',
                                        'late' => 'bg-amber-100 text-amber-800',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                    $statusLabel = match($payment->status) {
                                        'approved' => 'Aprobado',
                                        'submitted' => 'Pendiente de revisar',
                                        'late' => 'En atraso',
                                        'cancelled' => 'Cancelado',
                                        default => 'Pendiente',
                                    };
                                @endphp
                                <tr>
                                    <td class="px-3 py-3 text-center align-middle font-medium text-white">Pago {{ $payment->payment_number }}</td>
                                    <td class="px-3 py-3 text-center align-middle font-semibold">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-3 py-3 text-center align-middle">{{ $payment->due_date->format('d/m/Y') }}</td>
                                    <td class="px-3 py-3 text-center align-middle">{{ $payment->grace_until->format('d/m/Y') }}</td>
                                    <td class="px-3 py-3 text-center align-middle">
                                        <span class="inline-block rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center align-middle">
                                        @if($payment->receipt_path)
                                            <button type="button" onclick="openReceiptModal('{{ route('bookings.payments.image', $payment->id) }}')" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">
                                                Ver comprobante
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400">Sin comprobante</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center align-middle">
                                        @if($payment->status === 'submitted' && $booking->status !== 'rejected')
                                            <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="rounded-lg bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white">Aprobar pago</button>
                                            </form>
                                        @elseif($payment->status === 'approved')
                                            <span class="text-xs font-semibold text-emerald-300">Listo</span>
                                        @elseif($payment->status === 'submitted')
                                            <span class="text-xs font-semibold text-sky-300">En revisión</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-300">Sin acción</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-slate-300">Esta reserva no tiene pagos adicionales generados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="receipt-modal" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background: rgba(0,0,0,.75);">
        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-xl relative" style="width:min(92vw, 420px); padding:14px 12px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <button type="button" onclick="closeReceiptModal()" style="position:absolute; top:10px; right:10px; z-index:10; background-color:#dc2626;color:#ffffff;" class="px-2 py-1 rounded text-xs font-semibold">
                Cerrar
            </button>
            <h4 class="text-base font-bold mb-3 text-white">Comprobante</h4>
            <img id="receipt-modal-image" src="" alt="Comprobante" class="mx-auto rounded border border-gray-700 bg-gray-800" style="width:220px;height:320px;object-fit:contain;display:block;">
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
