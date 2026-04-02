<x-app-layout>
    <x-slot name="header">
        <h2 class="inline-flex items-center gap-2 font-semibold text-xl text-white leading-tight">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 2 3 6v6c0 5 3.8 9.7 9 10 5.2-.3 9-5 9-10V6l-9-4Zm0 2.2 7 3.1V12c0 4-2.9 7.8-7 8.2-4.1-.4-7-4.2-7-8.2V7.3l7-3.1Z"/>
            </svg>
            <span>Administración</span>
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto w-full max-w-[96rem] px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                @if(session('status'))
                    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 shadow">
                        {{ session('status') }}
                    </div>
                @endif

                @php
                    $totalTours = $tours->count();
                    $formatHumanDate = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') : null;
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

                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
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
                                        @if($tour->fecha_inicio)
                                            <p class="text-[11px] text-slate-500">{{ $formatHumanDate($tour->fecha_inicio) }}{{ $tour->fecha_fin ? ' → ' . $formatHumanDate($tour->fecha_fin) : '' }}</p>
                                        @endif
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
                            </div>

                            <form method="GET" action="{{ route('admin.index') }}#reservations-panel" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                                <input type="hidden" name="tour_id" value="{{ $selectedTour->id }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <input id="payment-filter-input" type="text" name="payment_ref" value="{{ $paymentSearch ?? '' }}"
                                       placeholder="Buscar por ID de pago o compra"
                                       class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white sm:w-80">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M10 2a8 8 0 1 0 5.3 14l4.35 4.35 1.4-1.4-4.35-4.35A8 8 0 0 0 10 2Zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12Z"/>
                                    </svg>
                                    <span>Buscar</span>
                                </button>
                            </form>
                        </div>

                        @if($bookings->isEmpty())
                            <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/60 px-4 py-6 text-sm text-slate-400">
                                No hay solicitudes en esta sección.
                            </div>
                        @else
                            <div class="overflow-x-auto overflow-y-hidden rounded-2xl border border-white/10 pb-1">
                                <table class="min-w-[1280px] w-full text-sm text-slate-200">
                                    <thead class="bg-slate-800 text-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-center font-semibold">#</th>
                                            <th class="px-4 py-3 text-center font-semibold">Usuario</th>
                                            <th class="px-4 py-3 text-center font-semibold">Persona registrada</th>
                                            <th class="px-4 py-3 text-center font-semibold">Correo</th>
                                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">ID compra</th>
                                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">ID pago</th>
                                            <th class="px-4 py-3 text-center font-semibold">Monto</th>
                                            <th class="px-4 py-3 text-center font-semibold">Fecha</th>
                                            <th class="px-4 py-3 text-center font-semibold whitespace-nowrap">Comprobante</th>
                                            <th class="px-4 py-3 text-center font-semibold whitespace-nowrap">Acción</th>
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
                                                <td class="px-4 py-3 text-right align-middle font-medium text-white whitespace-nowrap">{{ $booking->purchase_id }}</td>
                                                <td class="px-4 py-3 text-right align-middle text-xs whitespace-nowrap">
                                                    @if($initialPaymentRef)
                                                        <div class="font-semibold text-cyan-200">{{ $initialPaymentRef }}</div>
                                                    @else
                                                        <span class="text-slate-400">Sin pagos</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center align-middle font-semibold text-white">${{ number_format($booking->amount_paid, 2) }}</td>
                                                <td class="px-4 py-3 text-center align-middle text-slate-300">
                                                    <span class="block">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                                                    <span class="block text-[11px] text-slate-500">{{ $booking->created_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-center align-middle">
                                                    <button type="button" onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')" class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                            <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7Zm0 12a5 5 0 1 1 5-5 5 5 0 0 1-5 5Z"/>
                                                        </svg>
                                                        <span>Ver comprobante</span>
                                                    </button>
                                                </td>
                                                <td class="px-4 py-3 text-center align-middle">
                                                    <div class="flex flex-col items-center gap-2">
                                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center gap-1 rounded-xl bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-sky-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                                <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7Zm0 12a5 5 0 1 1 5-5 5 5 0 0 1-5 5Z"/>
                                                            </svg>
                                                            <span>Ver detalle</span>
                                                        </a>

                                                        @if($booking->status === 'pending')
                                                            <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="inline-block">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                                        <path d="m9.55 18-5.7-5.7 1.4-1.4 4.3 4.3 9.2-9.2 1.4 1.4L9.55 18Z"/>
                                                                    </svg>
                                                                    <span>Aprobar</span>
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
