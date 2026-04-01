<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Administración</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @if(session('status'))
                    <div class="rounded-2xl border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800 shadow">
                        {{ session('status') }}
                    </div>
                @endif

                @php
                    $totalTours = $tours->count();
                    $selectedCountTotal = $selectedTour ? array_sum($statusCounts) : 0;
                @endphp

                <section class="relative overflow-hidden rounded-3xl border border-emerald-500/20 bg-gradient-to-r from-slate-950 via-emerald-950/60 to-slate-950 px-6 py-7 shadow-2xl shadow-emerald-950/20">
                    <div class="absolute -right-10 top-0 h-40 w-40 rounded-full bg-emerald-500/20 blur-3xl"></div>
                    <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <span class="inline-flex items-center rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                                Administración
                            </span>
                            <h3 class="mt-3 text-3xl font-black text-white">Gestión de reservaciones</h3>
                            <p class="mt-2 max-w-2xl text-sm text-slate-300 md:text-base">
                                Consulta solicitudes, filtra por estatus y revisa rápidamente la actividad de cada tour.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                            {{ $selectedTour ? 'Tour seleccionado: ' . $selectedTour->nombre : 'Selecciona un tour para comenzar' }}
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Tours visibles</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $totalTours }}</p>
                        <p class="mt-1 text-sm text-slate-400">Disponibles para revisión</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Reservadas</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $statusCounts['pending'] ?? 0 }}</p>
                        <p class="mt-1 text-sm text-slate-400">Pendientes de confirmar</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Aprobadas</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $statusCounts['approved'] ?? 0 }}</p>
                        <p class="mt-1 text-sm text-slate-400">Reservas confirmadas</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Canceladas</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $statusCounts['rejected'] ?? 0 }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ $selectedCountTotal }} registros del tour</p>
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                    <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-white">Tours disponibles</h4>
                            <p class="text-sm text-slate-400">Selecciona un tour para revisar sus solicitudes.</p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @forelse($tours as $tour)
                            <a href="{{ route('admin.index', ['tour_id' => $tour->id, 'status' => 'pending']) }}"
                               class="rounded-2xl border p-4 text-left transition duration-200 {{ optional($selectedTour)->id === $tour->id ? 'border-emerald-400/50 bg-emerald-500/10 shadow-lg shadow-emerald-950/20' : 'border-white/10 bg-slate-950/70 hover:border-cyan-400/40 hover:bg-slate-950' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-lg font-bold text-white">{{ $tour->nombre }}</p>
                                        <p class="mt-1 text-sm text-slate-300">{{ $tour->fecha_inicio ?? 'Sin fecha' }} {{ $tour->fecha_fin ? '→ ' . $tour->fecha_fin : '' }}</p>
                                    </div>
                                    <span class="rounded-full bg-cyan-500/15 px-2.5 py-1 text-[11px] font-semibold text-cyan-300">
                                        {{ $tour->cupos_disponibles }}/{{ $tour->cupos_totales }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-slate-300">No hay tours disponibles.</p>
                        @endforelse
                    </div>
                </section>

                @if($selectedTour)
                    <section id="reservations-panel" class="scroll-mt-24 rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                        <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.index', ['tour_id' => $selectedTour->id, 'status' => 'pending']) }}#reservations-panel"
                                   class="relative rounded-xl px-4 py-2 text-sm font-semibold transition {{ $status === 'pending' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-950/20' : 'border border-white/10 bg-slate-950/70 text-slate-200 hover:border-emerald-400/40' }}">
                                    Reservadas
                                    @if(($statusCounts['pending'] ?? 0) > 0)
                                        <span class="absolute -right-2 -top-2 inline-flex min-w-[1.35rem] justify-center rounded-full bg-red-600 px-1.5 py-0.5 text-[11px] font-bold text-white">{{ $statusCounts['pending'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.index', ['tour_id' => $selectedTour->id, 'status' => 'approved']) }}#reservations-panel"
                                   class="relative rounded-xl px-4 py-2 text-sm font-semibold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-950/20' : 'border border-white/10 bg-slate-950/70 text-slate-200 hover:border-emerald-400/40' }}">
                                    Aprobadas
                                    @if(($statusCounts['approved'] ?? 0) > 0)
                                        <span class="absolute -right-2 -top-2 inline-flex min-w-[1.35rem] justify-center rounded-full bg-emerald-600 px-1.5 py-0.5 text-[11px] font-bold text-white">{{ $statusCounts['approved'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.index', ['tour_id' => $selectedTour->id, 'status' => 'rejected']) }}#reservations-panel"
                                   class="relative rounded-xl px-4 py-2 text-sm font-semibold transition {{ $status === 'rejected' ? 'bg-red-600 text-white shadow-lg shadow-red-950/20' : 'border border-white/10 bg-slate-950/70 text-slate-200 hover:border-red-400/40' }}">
                                    Canceladas
                                    @if(($statusCounts['rejected'] ?? 0) > 0)
                                        <span class="absolute -right-2 -top-2 inline-flex min-w-[1.35rem] justify-center rounded-full bg-slate-600 px-1.5 py-0.5 text-[11px] font-bold text-white">{{ $statusCounts['rejected'] }}</span>
                                    @endif
                                </a>
                            </div>

                            <form method="GET" action="{{ route('admin.index') }}#reservations-panel" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                                <input type="hidden" name="tour_id" value="{{ $selectedTour->id }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <input id="payment-filter-input" type="text" name="payment_ref" value="{{ $paymentSearch ?? '' }}"
                                       placeholder="Buscar por ID de pago o compra"
                                       class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white sm:w-80">
                                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">Buscar</button>
                            </form>
                        </div>

                        @if($bookings->isEmpty())
                            <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/60 px-4 py-6 text-sm text-slate-400">
                                No hay solicitudes en esta sección.
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-2xl border border-white/10">
                                <table class="w-full text-sm text-slate-200">
                                    <thead class="bg-slate-800 text-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-center font-semibold">#</th>
                                            <th class="px-4 py-3 text-center font-semibold">Usuario</th>
                                            <th class="px-4 py-3 text-center font-semibold">Persona registrada</th>
                                            <th class="px-4 py-3 text-center font-semibold">Correo</th>
                                            <th class="px-4 py-3 text-center font-semibold">ID compra</th>
                                            <th class="px-4 py-3 text-center font-semibold">ID pago</th>
                                            <th class="px-4 py-3 text-center font-semibold">Monto</th>
                                            <th class="px-4 py-3 text-center font-semibold">Fecha</th>
                                            <th class="px-4 py-3 text-center font-semibold">Comprobante</th>
                                            <th class="px-4 py-3 text-center font-semibold">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800 bg-slate-950/60">
                                        @foreach($bookings as $booking)
                                            @php
                                                $paymentRefs = $booking->payments->pluck('reference')->implode(' · ');
                                                $initialPaymentRef = optional($booking->payments->sortBy('payment_number')->first())->reference;
                                                $searchText = strtolower(trim($booking->purchase_id . ' ' . $paymentRefs . ' ' . $booking->user->name . ' ' . $booking->user->email));
                                            @endphp
                                            <tr class="admin-booking-row" data-search="{{ $searchText }}">
                                                <td class="px-4 py-3 text-center align-middle font-medium text-white">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-3 text-center align-middle">
                                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="font-semibold text-sky-300 hover:underline">
                                                        {{ $booking->user->name }}
                                                    </a>
                                                    @if(($booking->payments->where('status', 'submitted')->count() ?? 0) > 0)
                                                        <p class="text-[11px] text-cyan-300">{{ $booking->payments->where('status', 'submitted')->count() }} pago(s) por revisar</p>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center align-middle text-slate-200">{{ $booking->passenger_name ?: $booking->user->name }}</td>
                                                <td class="px-4 py-3 text-center align-middle text-slate-300">{{ $booking->user->email }}</td>
                                                <td class="px-4 py-3 text-center align-middle font-medium text-white">{{ $booking->purchase_id }}</td>
                                                <td class="px-4 py-3 text-center align-middle text-xs">
                                                    @if($initialPaymentRef)
                                                        <div class="font-semibold text-cyan-200">{{ $initialPaymentRef }}</div>
                                                    @else
                                                        <span class="text-slate-400">Sin pagos</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center align-middle font-semibold text-white">${{ number_format($booking->amount_paid, 2) }}</td>
                                                <td class="px-4 py-3 text-center align-middle text-slate-300">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="px-4 py-3 text-center align-middle">
                                                    <button type="button" onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')" class="rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-500">
                                                        Ver comprobante
                                                    </button>
                                                </td>
                                                <td class="px-4 py-3 text-center align-middle">
                                                    <div class="flex flex-col items-center gap-2">
                                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="rounded-xl bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-sky-500">
                                                            Ver detalle
                                                        </a>

                                                        @if($booking->status === 'pending')
                                                            <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="inline-block">
                                                                @csrf
                                                                <button type="submit" class="rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-500">
                                                                    Aprobar
                                                                </button>
                                                            </form>
                                                        @elseif($booking->status === 'approved')
                                                            <span class="inline-block rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-300">Aprobada</span>
                                                        @else
                                                            <span class="inline-block rounded-full bg-red-500/15 px-2.5 py-1 text-xs font-semibold text-red-300">Cancelada</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>
                @else
                    <div class="rounded-3xl border border-dashed border-white/10 bg-slate-900/70 px-4 py-8 text-center text-sm text-slate-300 shadow-xl shadow-black/20">
                        Selecciona un tour para ver sus solicitudes.
                    </div>
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

        const paymentFilterInput = document.getElementById('payment-filter-input');
        if (paymentFilterInput) {
            paymentFilterInput.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                document.querySelectorAll('.admin-booking-row').forEach((row) => {
                    const haystack = row.dataset.search || '';
                    row.style.display = haystack.includes(term) ? '' : 'none';
                });
            });
        }
    </script>
</x-app-layout>
