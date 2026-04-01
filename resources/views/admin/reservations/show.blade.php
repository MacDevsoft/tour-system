<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Detalle de pagos del usuario</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">{{ $booking->user->name }}</h3>
                        <p class="text-sm text-slate-300">{{ $booking->user->email }} · Tour: {{ $booking->tour->nombre }}</p>
                    </div>
                    <a href="{{ route('admin.index', ['tour_id' => $booking->tour_id, 'status' => $booking->status === 'rejected' ? 'rejected' : ($booking->status === 'approved' ? 'approved' : 'pending')]) }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-black">
                        Volver
                    </a>
                </div>

                <div class="mt-5 grid gap-3 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Usuario titular</p>
                        <p class="mt-1 text-lg font-bold text-white">{{ $booking->user->name }}</p>
                        <p class="text-sm text-slate-300">{{ $booking->user->email }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Persona registrada</p>
                        <p class="mt-1 text-lg font-bold text-white">{{ $booking->passenger_name ?: $booking->user->name }}</p>
                        <p class="text-sm text-slate-300">ID de compra: {{ $booking->purchase_id }}</p>
                    </div>
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

                            @if($booking->status !== 'rejected')
                                <button type="button" onclick="openCancelBookingModal()" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white">
                                    Cancelar reservación
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <h4 class="text-lg font-bold text-white">Pagos programados</h4>
                    <input id="admin-payment-detail-filter" type="text" placeholder="Filtrar por ID de pago"
                           class="w-full rounded-xl border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white md:w-72">
                </div>

                <div class="mt-3 overflow-x-auto rounded-xl border border-slate-700">
                    <table class="w-full text-sm text-slate-200">
                        <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="px-3 py-3 text-center font-semibold">Pago</th>
                                <th class="px-3 py-3 text-center font-semibold">ID pago</th>
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
                                <tr class="admin-payment-detail-row" data-search="{{ strtolower($payment->reference . ' ' . $payment->payment_number . ' ' . $payment->status) }}">
                                    <td class="px-3 py-3 text-center align-middle font-medium text-white">Pago {{ $payment->payment_number }}</td>
                                    <td class="px-3 py-3 text-center align-middle text-xs font-semibold text-cyan-200">{{ $payment->reference }}</td>
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
                                    <td colspan="8" class="px-4 py-4 text-center text-slate-300">Esta reserva no tiene pagos adicionales generados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="cancel-booking-modal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4" style="background: rgba(0,0,0,.75);">
        <div class="w-full max-w-lg rounded-2xl border border-slate-700 bg-slate-900 p-6 shadow-2xl">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h4 class="text-lg font-bold text-white">Cancelar reservación</h4>
                    <p class="text-sm text-slate-300">¿Seguro que quieres cancelar el tour de <strong>{{ $booking->passenger_name ?: $booking->user->name }}</strong>?</p>
                </div>
                <button type="button" onclick="closeCancelBookingModal()" class="rounded-lg bg-slate-700 px-2 py-1 text-xs font-semibold text-white">Cerrar</button>
            </div>

            <div class="mb-4 rounded-xl border border-red-800 bg-red-950/40 px-4 py-3 text-sm text-red-100">
                Esta acción marcará la reservación como <strong>cancelada</strong> y, si ya estaba aprobada, <strong>liberará un cupo</strong> automáticamente.
            </div>

            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-200">Motivo de cancelación (opcional)</label>
                    <textarea name="cancel_reason" rows="3" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white" placeholder="Ej. El usuario ya no asistirá al tour."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCancelBookingModal()" class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white">No, volver</button>
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white">Sí, cancelar</button>
                </div>
            </form>
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
        function openCancelBookingModal() {
            const modal = document.getElementById('cancel-booking-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCancelBookingModal() {
            const modal = document.getElementById('cancel-booking-modal');
            if (!modal) return;
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

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

        document.getElementById('cancel-booking-modal')?.addEventListener('click', function (e) {
            if (e.target.id === 'cancel-booking-modal') {
                closeCancelBookingModal();
            }
        });

        const adminPaymentDetailFilter = document.getElementById('admin-payment-detail-filter');
        if (adminPaymentDetailFilter) {
            adminPaymentDetailFilter.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                document.querySelectorAll('.admin-payment-detail-row').forEach((row) => {
                    const haystack = row.dataset.search || '';
                    row.style.display = haystack.includes(term) ? '' : 'none';
                });
            });
        }
    </script>
</x-app-layout>
