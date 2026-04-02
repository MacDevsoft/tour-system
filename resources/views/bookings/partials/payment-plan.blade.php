@php
    $tour = $booking->tour;
    $payments = $booking->payments ?? collect();
    $deadline = $booking->paymentDeadline();
    $totalPaid = $booking->totalApprovedPayments();
    $remaining = max(0, (float) ($tour->precio_total ?? 0) - $totalPaid);
    $activeBank = \App\Models\BankAccount::active();
    $statusMap = [
        'pending' => ['Pendiente', 'bg-slate-100 text-slate-700'],
        'late' => ['En atraso', 'bg-amber-100 text-amber-800'],
        'submitted' => ['Pendiente de revisar', 'bg-blue-100 text-blue-700'],
        'approved' => ['Aprobado', 'bg-green-100 text-green-700'],
        'cancelled' => ['Cancelado', 'bg-red-100 text-red-700'],
    ];
    $formatHumanDate = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') : null;
@endphp

<div class="mt-5 rounded-2xl border border-slate-700 bg-slate-950/60 p-4">
    <div class="grid gap-3 md:grid-cols-4">
        <div class="rounded-xl bg-slate-900 p-3 border border-slate-800">
            <p class="text-[11px] uppercase tracking-wide text-slate-400">Total del tour</p>
            <p class="mt-1 text-lg font-bold text-white">${{ number_format($tour->precio_total ?? 0, 2) }}</p>
        </div>
        <div class="rounded-xl bg-slate-900 p-3 border border-slate-800">
            <p class="text-[11px] uppercase tracking-wide text-slate-400">Anticipo inicial</p>
            <p class="mt-1 text-lg font-bold text-cyan-300">${{ number_format($booking->amount_paid, 2) }}</p>
        </div>
        <div class="rounded-xl bg-slate-900 p-3 border border-slate-800">
            <p class="text-[11px] uppercase tracking-wide text-slate-400">Liquidado</p>
            <p class="mt-1 text-lg font-bold text-emerald-300">${{ number_format($totalPaid, 2) }}</p>
        </div>
        <div class="rounded-xl bg-slate-900 p-3 border border-slate-800">
            <p class="text-[11px] uppercase tracking-wide text-slate-400">Saldo pendiente</p>
            <p class="mt-1 text-lg font-bold text-amber-300">${{ number_format($remaining, 2) }}</p>
        </div>
    </div>

    <div class="mt-4 rounded-xl border border-slate-800 bg-slate-900 p-4">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-white">Anticipo registrado</p>
                <p class="text-xs text-slate-300">ID {{ $booking->purchase_id }} · {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-[11px] text-slate-500">{{ $booking->created_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">
                    Ver comprobante
                </button>
                @if($booking->status === 'approved')
                    <span class="inline-block rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Reserva aprobada</span>
                @elseif($booking->status === 'rejected')
                    <span class="inline-block rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Reserva cancelada</span>
                @else
                    <span class="inline-block rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">Pendiente de aprobación</span>
                @endif
            </div>
        </div>
    </div>

    @if($booking->status === 'rejected')
        <div class="mt-4 rounded-xl border border-red-800 bg-red-950/40 px-4 py-3 text-sm text-red-100">
            <p class="font-semibold">Reserva cancelada</p>
            <p>{{ $booking->cancellation_reason ?: 'La reserva fue cancelada por falta de pago.' }}</p>
        </div>
    @else
        <div class="mt-4 rounded-xl border border-cyan-900 bg-cyan-950/30 px-4 py-3 text-sm text-cyan-100">
            <p class="font-semibold">Plan de liquidación {{ $tour->payment_installments ? 'configurado por administración' : 'quincenal' }}</p>
            <p>
                @if($tour->payment_installments)
                    El administrador configuró <strong>{{ $tour->payment_installments }} pago(s)</strong>
                    @if($deadline)
                        con fecha límite de liquidación el <strong>{{ $deadline->format('d/m/Y') }}</strong> ({{ $deadline->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}).
                    @endif
                @else
                    El saldo restante se divide en pagos el <strong>día 1 y 15</strong> de cada mes.
                    @if($deadline)
                        Todo debe quedar liquidado a más tardar el <strong>{{ $deadline->format('d/m/Y') }}</strong> ({{ $deadline->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}).
                    @endif
                @endif
                Cada fecha tiene <strong>3 días de tolerancia</strong>; después la reserva se cancela.
            </p>
        </div>
    @endif

    @if($payments->count() > 0)
        <div class="mt-4 overflow-x-auto rounded-xl border border-slate-800">
            <table class="w-full text-sm text-slate-200">
                <thead class="bg-slate-800 text-slate-100">
                    <tr>
                        <th class="px-3 py-3 text-center font-semibold">Pago</th>
                        <th class="px-3 py-3 text-center font-semibold">ID pago</th>
                        <th class="px-3 py-3 text-center font-semibold">Fecha límite</th>
                        <th class="px-3 py-3 text-center font-semibold">Tolerancia</th>
                        <th class="px-3 py-3 text-center font-semibold">Monto</th>
                        <th class="px-3 py-3 text-center font-semibold">Estado</th>
                        <th class="px-3 py-3 text-center font-semibold">Comprobante</th>
                        <th class="px-3 py-3 text-center font-semibold">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-950/50">
                    @foreach($payments as $payment)
                        @php
                            [$statusLabel, $statusClasses] = $statusMap[$payment->status] ?? [ucfirst($payment->status), 'bg-slate-100 text-slate-700'];
                        @endphp
                        <tr>
                            <td class="px-3 py-3 text-center align-middle font-medium text-white">Pago {{ $payment->payment_number }}</td>
                            <td class="px-3 py-3 text-center align-middle text-xs font-semibold text-cyan-200">{{ $payment->reference }}</td>
                            <td class="px-3 py-3 text-center align-middle">
                                <span class="block">{{ $payment->due_date->format('d/m/Y') }}</span>
                                <span class="block text-[11px] text-slate-500">{{ $payment->due_date->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</span>
                            </td>
                            <td class="px-3 py-3 text-center align-middle">
                                <span class="block">{{ $payment->grace_until->format('d/m/Y') }}</span>
                                <span class="block text-[11px] text-slate-500">{{ $payment->grace_until->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</span>
                            </td>
                            <td class="px-3 py-3 text-center align-middle font-semibold">${{ number_format($payment->amount, 2) }}</td>
                            <td class="px-3 py-3 text-center align-middle">
                                <span class="inline-block rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-3 py-3 text-center align-middle">
                                @if($payment->receipt_path)
                                    <button type="button" onclick="openReceiptModal('{{ route('bookings.payments.image', $payment->id) }}')" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">
                                        Ver comprobante
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400">Sin enviar</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center align-middle">
                                @if(in_array($payment->status, ['pending', 'late'], true) && $booking->status !== 'rejected')
                                    <button type="button" onclick="openPaymentUploadModal('payment-modal-{{ $prefix }}-{{ $payment->id }}')" class="rounded-lg {{ $payment->status === 'late' ? 'bg-amber-600' : 'bg-sky-600' }} px-3 py-1.5 text-xs font-semibold text-white">
                                        Pagar
                                    </button>
                                @elseif($payment->status === 'submitted')
                                    <span class="text-xs font-semibold text-sky-300">En revisión</span>
                                @elseif($payment->status === 'approved')
                                    <span class="text-xs font-semibold text-emerald-300">Aprobado</span>
                                @else
                                    <span class="text-xs font-semibold text-red-300">Cancelado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-4 text-sm text-slate-300">Esta reserva no requiere pagos adicionales.</p>
    @endif
</div>

@foreach($payments as $payment)
    @if(in_array($payment->status, ['pending', 'late'], true) && $booking->status !== 'rejected')
        <div id="payment-modal-{{ $prefix }}-{{ $payment->id }}" class="fixed inset-0 z-[80] hidden items-center justify-center p-4" style="background: rgba(0,0,0,.75);">
            <div class="w-full max-w-md rounded-2xl border border-slate-700 bg-slate-900 p-5 shadow-2xl">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h5 class="text-lg font-bold text-white">Registrar pago {{ $payment->payment_number }}</h5>
                        <p class="text-xs text-slate-300">ID de pago: {{ $payment->reference }} · Monto sugerido: ${{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <button type="button" onclick="closePaymentUploadModal('payment-modal-{{ $prefix }}-{{ $payment->id }}')" class="rounded-lg bg-red-600 px-2 py-1 text-xs font-semibold text-white">
                        Cerrar
                    </button>
                </div>

                @if($activeBank)
                    <div class="mb-4 rounded-xl border border-slate-700 bg-slate-950 px-3 py-3 text-sm text-slate-200">
                        <p class="font-semibold text-white mb-1">Datos bancarios</p>
                        <p><span class="font-semibold">Tipo:</span> {{ $activeBank->account_type }}</p>
                        <p><span class="font-semibold">Banco:</span> {{ $activeBank->bank_name }}</p>
                        <p><span class="font-semibold">Cuenta:</span> {{ $activeBank->account_number }}</p>
                        <p><span class="font-semibold">Titular:</span> {{ $activeBank->account_holder }}</p>
                    </div>
                @else
                    <div class="mb-4 rounded-xl border border-yellow-700 bg-yellow-950/40 px-3 py-3 text-sm text-yellow-100">
                        No hay cuenta bancaria activa configurada por el administrador.
                    </div>
                @endif

                <div class="mb-4 rounded-xl border border-cyan-900 bg-cyan-950/30 px-3 py-2 text-sm text-cyan-100">
                    <p><span class="font-semibold">Fecha límite:</span> {{ $payment->due_date->format('d/m/Y') }} <span class="text-xs text-cyan-200">({{ $payment->due_date->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }})</span></p>
                    <p><span class="font-semibold">Tolerancia máxima:</span> {{ $payment->grace_until->format('d/m/Y') }} <span class="text-xs text-cyan-200">({{ $payment->grace_until->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }})</span></p>
                </div>

                <form action="{{ route('bookings.payments.submit', $payment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="mb-2 block text-sm font-semibold text-slate-200">Adjuntar comprobante</label>
                    <input type="file" name="receipt" accept="image/*" required class="mb-4 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-200">

                    <button type="submit" @if(!$activeBank) disabled @endif class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white disabled:cursor-not-allowed disabled:opacity-50">
                        Enviar pago a revisión
                    </button>
                </form>
            </div>
        </div>
    @endif
@endforeach

@once
    <script>
        function openPaymentUploadModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePaymentUploadModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
@endonce
