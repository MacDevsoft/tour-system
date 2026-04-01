<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Inicio
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @if(auth()->user()->role === 'admin')
                    @php
                        $totalTours = \App\Models\Tour::count();
                        $activeTours = \App\Models\Tour::where('is_enabled', true)->count();
                        $totalBookings = \App\Models\Booking::count();
                        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
                        $approvedBookings = \App\Models\Booking::where('status', 'approved')->count();
                        $pendingPayments = \App\Models\BookingPayment::whereIn('status', ['pending', 'late', 'submitted'])->count();
                        $submittedPayments = \App\Models\BookingPayment::where('status', 'submitted')->count();
                        $confirmedRevenue = (float) \App\Models\Booking::where('status', 'approved')->sum('amount_paid')
                            + (float) \App\Models\BookingPayment::where('status', 'approved')->sum('amount');
                        $upcomingTours = \App\Models\Tour::orderBy('fecha_inicio')->take(4)->get();
                        $recentBookings = \App\Models\Booking::with(['tour', 'user'])->latest()->take(5)->get();
                        $bank = \App\Models\BankAccount::active();
                        $formatDate = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') : 'Por confirmar';
                    @endphp

                    <section class="relative overflow-hidden rounded-3xl border border-emerald-500/20 bg-gradient-to-r from-slate-950 via-emerald-950/60 to-slate-950 px-6 py-7 shadow-2xl shadow-emerald-950/20">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-emerald-500/20 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 h-28 w-28 rounded-full bg-cyan-500/10 blur-2xl"></div>
                        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <span class="inline-flex items-center rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                                    Centro de control
                                </span>
                                <h2 class="mt-3 text-3xl font-black text-white md:text-4xl">
                                    Bienvenido, {{ auth()->user()->name }}
                                </h2>
                                <p class="mt-2 max-w-2xl text-sm text-slate-300 md:text-base">
                                    Monitorea reservaciones, pagos pendientes y próximos tours desde un solo lugar.
                                </p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <a href="/tours" class="rounded-2xl border border-cyan-400/30 bg-cyan-500/10 px-4 py-3 text-left transition hover:bg-cyan-500/15">
                                    <p class="text-xs uppercase tracking-[0.2em] text-cyan-300">Operación</p>
                                    <p class="mt-1 text-base font-bold text-white">Ver / editar tours</p>
                                </a>
                                <a href="{{ route('admin.index') }}" class="rounded-2xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-left transition hover:bg-amber-500/15">
                                    <p class="text-xs uppercase tracking-[0.2em] text-amber-300">Revisión</p>
                                    <p class="mt-1 text-base font-bold text-white">Reservaciones y pagos</p>
                                </a>
                                <a href="{{ route('bank_accounts.index') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left transition hover:bg-white/10 sm:col-span-2">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Finanzas</p>
                                    <p class="mt-1 text-base font-bold text-white">
                                        {{ $bank ? 'Cuenta bancaria activa configurada' : 'Configurar cuentas bancarias' }}
                                    </p>
                                </a>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Tours activos</p>
                            <div class="mt-2 flex items-end justify-between">
                                <p class="text-3xl font-black text-white">{{ $activeTours }}</p>
                                <span class="rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-300">de {{ $totalTours }}</span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Reservaciones</p>
                            <div class="mt-2 flex items-end justify-between">
                                <p class="text-3xl font-black text-white">{{ $totalBookings }}</p>
                                <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-xs font-semibold text-amber-300">{{ $pendingBookings }} pendientes</span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Pagos por revisar</p>
                            <div class="mt-2 flex items-end justify-between">
                                <p class="text-3xl font-black text-white">{{ $pendingPayments }}</p>
                                <span class="rounded-full bg-cyan-500/15 px-2.5 py-1 text-xs font-semibold text-cyan-300">{{ $submittedPayments }} enviados</span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Ingreso confirmado</p>
                            <div class="mt-2 flex items-end justify-between gap-3">
                                <p class="text-2xl font-black text-white">${{ number_format($confirmedRevenue, 2) }}</p>
                                <span class="rounded-full bg-green-500/15 px-2.5 py-1 text-xs font-semibold text-green-300">{{ $approvedBookings }} aprobadas</span>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                        <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-xl font-bold text-white">Reservaciones recientes</h3>
                                    <p class="text-sm text-slate-400">Última actividad registrada dentro del sistema.</p>
                                </div>
                                <a href="{{ route('admin.index') }}" class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-slate-200 transition hover:border-emerald-400/40 hover:text-white">
                                    Ver todo
                                </a>
                            </div>

                            <div class="space-y-3">
                                @forelse($recentBookings as $booking)
                                    <div class="flex flex-col gap-2 rounded-2xl border border-white/5 bg-slate-950/70 px-4 py-3 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <p class="font-semibold text-white">{{ $booking->passenger_name ?: optional($booking->user)->name }}</p>
                                            <p class="text-xs text-slate-400">{{ optional($booking->tour)->nombre ?? 'Tour sin asignar' }} · {{ $booking->purchase_id }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @php
                                                $statusClasses = match($booking->status) {
                                                    'approved' => 'bg-emerald-500/15 text-emerald-300',
                                                    'rejected' => 'bg-red-500/15 text-red-300',
                                                    default => 'bg-amber-500/15 text-amber-300',
                                                };
                                                $statusLabel = match($booking->status) {
                                                    'approved' => 'Aprobada',
                                                    'rejected' => 'Cancelada',
                                                    default => 'Pendiente',
                                                };
                                            @endphp
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">{{ $statusLabel }}</span>
                                            <span class="text-xs text-slate-500">{{ $booking->created_at->format('d/m H:i') }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/60 px-4 py-6 text-sm text-slate-400">
                                        Aún no hay movimientos recientes por mostrar.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                                <h3 class="text-xl font-bold text-white">Próximos tours</h3>
                                <p class="mt-1 text-sm text-slate-400">Mantén visibilidad sobre tus siguientes salidas.</p>

                                <div class="mt-4 space-y-3">
                                    @forelse($upcomingTours as $tour)
                                        <div class="rounded-2xl border border-white/5 bg-slate-950/70 px-4 py-3">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="font-semibold text-white">{{ $tour->nombre }}</p>
                                                    <p class="text-xs text-slate-400">{{ $tour->ubicacion ?: 'Ubicación por confirmar' }}</p>
                                                </div>
                                                <span class="rounded-full bg-cyan-500/15 px-2.5 py-1 text-[11px] font-semibold text-cyan-300">
                                                    {{ $formatDate($tour->fecha_inicio) }}
                                                </span>
                                            </div>
                                            <p class="mt-2 text-xs text-slate-300">${{ number_format($tour->precio_total ?? 0, 2) }} · {{ $tour->cupos_disponibles }} cupos disponibles</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-400">No hay tours próximos por ahora.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="rounded-3xl border border-emerald-500/20 bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 p-6 shadow-xl shadow-emerald-950/20">
                                <p class="text-xs uppercase tracking-[0.22em] text-emerald-300">Resumen</p>
                                <h3 class="mt-2 text-xl font-bold text-white">Visión general</h3>
                                <p class="mt-2 text-sm text-slate-200">Consulta rápidamente el estado operativo del sistema y los próximos movimientos.</p>
                            </div>
                        </div>
                    </section>
                @else
                    @php
                        $tours = \App\Models\Tour::where('is_enabled', true)->orderBy('fecha_inicio')->get();
                        $myBookings = \App\Models\Booking::with(['tour', 'payments'])->where('user_id', auth()->id())->latest()->get();
                        $myBookedTourIds = $myBookings->pluck('tour_id')->flip();
                        $activeBookings = $myBookings->where('status', '!=', 'rejected')->count();
                        $approvedBookings = $myBookings->where('status', 'approved')->count();
                        $pendingBookings = $myBookings->where('status', 'pending')->count();
                        $cancelledBookings = $myBookings->where('status', 'rejected')->count();
                        $totalPendingBalance = $myBookings->sum(fn ($booking) => $booking->remainingAmount());
                        $nextPayment = \App\Models\BookingPayment::with('booking.tour')
                            ->whereHas('booking', fn ($query) => $query->where('user_id', auth()->id()))
                            ->whereIn('status', ['pending', 'late', 'submitted'])
                            ->orderBy('due_date')
                            ->first();
                        $nextTrip = $myBookings
                            ->filter(fn ($booking) => $booking->tour && $booking->status !== 'rejected')
                            ->sortBy(fn ($booking) => $booking->tour->fecha_inicio ?? '9999-12-31')
                            ->first();
                        $bank = \App\Models\BankAccount::active();
                        $formatDate = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') : 'Por confirmar';
                    @endphp

                    <section class="relative overflow-hidden rounded-3xl border border-cyan-500/20 bg-gradient-to-r from-slate-950 via-cyan-950/60 to-slate-950 px-6 py-7 shadow-2xl shadow-cyan-950/20">
                        <div class="absolute -right-10 top-0 h-44 w-44 rounded-full bg-cyan-500/20 blur-3xl"></div>
                        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">
                                    Experiencia premium
                                </span>
                                <h2 class="mt-3 text-3xl font-black text-white md:text-4xl">Bienvenido {{ auth()->user()->name }}</h2>
                                <p class="mt-2 max-w-2xl text-sm text-slate-300 md:text-base">
                                    Encuentra tu próximo viaje, revisa tu avance de pagos y administra tus reservaciones en un solo lugar.
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="#tours-disponibles" class="rounded-xl bg-cyan-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                                    Explorar tours
                                </a>
                                <a href="{{ route('bookings.my-tours') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                                    Ver mis tours
                                </a>
                            </div>
                        </div>
                    </section>

                    @if(session('status'))
                        <div class="rounded-2xl border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800 shadow">
                            {{ session('status') }}
                        </div>
                    @endif

                    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Tours disponibles</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ $tours->count() }}</p>
                            <p class="mt-1 text-sm text-slate-400">Listos para reservar</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Mis reservaciones activas</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ $activeBookings }}</p>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $approvedBookings }} aprobadas · {{ $pendingBookings }} pendientes
                                @if($cancelledBookings > 0)
                                    · {{ $cancelledBookings }} canceladas
                                @endif
                            </p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Próximo pago</p>
                            <p class="mt-2 text-xl font-black text-white">{{ $nextPayment ? '$' . number_format($nextPayment->amount, 2) : 'Sin pagos' }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $nextPayment ? 'Vence ' . $nextPayment->due_date->format('d/m/Y') : 'Todo al corriente' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-5 shadow-lg shadow-black/20">
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Saldo pendiente</p>
                            <p class="mt-2 text-2xl font-black text-white">${{ number_format($totalPendingBalance, 2) }}</p>
                            <p class="mt-1 text-sm text-slate-400">Pendiente por liquidar</p>
                        </div>
                    </section>

                    @if($nextTrip && $nextTrip->tour)
                        @php
                            $tourTotal = (float) ($nextTrip->tour->precio_total ?? 0);
                            $paidAmount = round($tourTotal - $nextTrip->remainingAmount(), 2);
                            $progress = $tourTotal > 0 ? min(100, max(0, (int) round(($paidAmount / $tourTotal) * 100))) : 0;
                        @endphp
                        <section class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
                            <div class="rounded-3xl border border-emerald-500/20 bg-gradient-to-br from-slate-950 via-emerald-950/50 to-slate-950 p-6 shadow-2xl shadow-emerald-950/20">
                                <p class="text-xs uppercase tracking-[0.22em] text-emerald-300">Tu próxima aventura</p>
                                <h3 class="mt-2 text-2xl font-bold text-white">{{ $nextTrip->tour->nombre }}</h3>
                                <p class="mt-1 text-sm text-slate-300">{{ $nextTrip->tour->ubicacion ?: 'Ubicación por confirmar' }} · {{ $formatDate($nextTrip->tour->fecha_inicio) }}</p>

                                <div class="mt-5 space-y-2">
                                    <div class="flex items-center justify-between text-sm text-slate-300">
                                        <span>Avance de pago</span>
                                        <span class="font-semibold text-white">{{ $progress }}%</span>
                                    </div>
                                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-800">
                                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-4 text-sm text-slate-300">
                                        <span>Pagado: <strong class="text-white">${{ number_format($paidAmount, 2) }}</strong></span>
                                        <span>Restante: <strong class="text-white">${{ number_format($nextTrip->remainingAmount(), 2) }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-xl shadow-black/20">
                                <h3 class="text-xl font-bold text-white">Acciones rápidas</h3>
                                <div class="mt-4 space-y-3">
                                    <a href="{{ route('bookings.my-tours', ['tour_id' => $nextTrip->tour_id, 'booking_id' => $nextTrip->id]) }}" class="block rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm font-semibold text-white transition hover:border-emerald-400/40 hover:bg-slate-900">
                                        Ver esquema de pagos
                                    </a>
                                    <a href="{{ route('tours.show', $nextTrip->tour->id) }}" class="block rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm font-semibold text-white transition hover:border-cyan-400/40 hover:bg-slate-900">
                                        Revisar detalle del tour
                                    </a>
                                </div>
                            </div>
                        </section>
                    @endif

                    <section id="tours-disponibles" class="rounded-3xl border border-white/10 bg-slate-900/70 p-6 shadow-xl shadow-black/20">
                        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-white">Tours disponibles</h3>
                                <p class="text-sm text-slate-400">Una vista más atractiva, clara y confiable para que reservar sea más fácil.</p>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-slate-300">
                                <span class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1">Pagos programados</span>
                                <span class="rounded-full border border-cyan-400/30 bg-cyan-500/10 px-3 py-1">Recibos digitales</span>
                                <span class="rounded-full border border-amber-400/30 bg-amber-500/10 px-3 py-1">Confirmación manual</span>
                            </div>
                        </div>

                        @if($tours->count() > 0)
                            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                                @foreach($tours as $tour)
                                    @php
                                        $alreadyBooked = isset($myBookedTourIds[$tour->id]);
                                        $isFull = (int) ($tour->cupos_disponibles ?? 0) <= 0;
                                        $deadline = $tour->resolvedPaymentDeadline();
                                    @endphp

                                    <div class="group relative rounded-3xl border border-white/10 bg-slate-950/80 p-5 shadow-lg shadow-black/20 transition duration-300 hover:border-cyan-400/40 hover:shadow-cyan-950/20">
                                        <div class="absolute right-0 top-0 h-20 w-20 rounded-full bg-cyan-500/10 blur-2xl transition duration-300 group-hover:bg-cyan-500/20"></div>

                                        <div class="relative">
                                            <div class="mb-4 flex items-start justify-between gap-3">
                                                <div>
                                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $isFull && !$alreadyBooked ? 'bg-red-500/15 text-red-300' : 'bg-emerald-500/15 text-emerald-300' }}">
                                                        {{ $isFull && !$alreadyBooked ? 'Sin cupo' : ($alreadyBooked ? 'Ya reservado' : 'Disponible') }}
                                                    </span>
                                                    <h4 class="mt-3 text-xl font-bold text-white">{{ $tour->nombre }}</h4>
                                                    <p class="mt-1 text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($tour->descripcion, 90) }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Total</p>
                                                    <p class="text-xl font-black text-emerald-300">${{ number_format($tour->precio_total ?? 0, 2) }}</p>
                                                </div>
                                            </div>

                                            <div class="grid gap-2 text-sm text-slate-300">
                                                <div class="rounded-2xl border border-white/5 bg-slate-900/80 px-3 py-2">📍 {{ $tour->ubicacion ?: 'Ubicación por confirmar' }}</div>
                                                <div class="rounded-2xl border border-white/5 bg-slate-900/80 px-3 py-2">📅 {{ $formatDate($tour->fecha_inicio) }} {{ $tour->fecha_fin ? '→ ' . $formatDate($tour->fecha_fin) : '' }}</div>
                                                <div class="rounded-2xl border border-white/5 bg-slate-900/80 px-3 py-2">👥 {{ $tour->cupos_disponibles }} / {{ $tour->cupos_totales }} lugares</div>
                                                <div class="rounded-2xl border border-white/5 bg-slate-900/80 px-3 py-2">💳 Anticipo desde ${{ number_format($tour->anticipo ?? 0, 2) }}</div>
                                                <div class="rounded-2xl border border-white/5 bg-slate-900/80 px-3 py-2">⏳ Liquidación máxima: {{ $deadline ? $deadline->format('d/m/Y') : 'Por definir' }}</div>
                                            </div>

                                            <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                                <a href="{{ route('tours.show', $tour->id) }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-white/10">
                                                    Ver detalles
                                                </a>

                                                @if($isFull && !$alreadyBooked)
                                                    <button type="button" disabled class="cursor-not-allowed rounded-xl bg-slate-700 px-3 py-2 text-sm font-semibold text-slate-300">
                                                        Sin disponibilidad
                                                    </button>
                                                @else
                                                    <button onclick="openModal('modal-{{ $tour->id }}')" type="button" class="rounded-xl bg-gradient-to-r from-emerald-400 to-cyan-400 px-3 py-2 text-sm font-semibold text-slate-950 shadow-lg shadow-emerald-900/20 transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-cyan-300/60">
                                                        Reservar ahora
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <div id="modal-{{ $tour->id }}" class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center p-8">
                                            <div class="modal-box w-full max-w-md rounded-3xl border border-white/10 bg-white p-6 shadow-2xl">
                                                <h3 class="text-lg font-bold text-slate-900">Solicitar reservación</h3>
                                                <p class="text-slate-500 text-sm mb-4">{{ $tour->nombre }}</p>

                                                <div class="rounded-2xl border border-cyan-100 bg-cyan-50 px-4 py-3 mb-3">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">Anticipo a pagar</p>
                                                    <p class="text-2xl font-black text-cyan-800">${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                                                </div>

                                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 mb-3">
                                                    <p class="text-[11px] leading-5 text-slate-700">
                                                        @if($tour->payment_installments)
                                                            Este tour está configurado a <strong>{{ $tour->payment_installments }} pago(s)</strong>
                                                            @if($tour->resolvedPaymentDeadline())
                                                                con liquidación máxima al <strong>{{ $tour->resolvedPaymentDeadline()->format('d/m/Y') }}</strong>.
                                                            @endif
                                                        @else
                                                            El resto se dividirá automáticamente en pagos quincenales el <strong>día 1 y 15</strong>.
                                                            Todo debe quedar liquidado <strong>15 días antes del tour</strong> y cada fecha tiene <strong>3 días de tolerancia</strong>.
                                                        @endif
                                                    </p>
                                                </div>

                                                @if($bank)
                                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 mb-3 space-y-1">
                                                        <p class="text-xs font-bold text-slate-700 mb-1">Datos bancarios</p>
                                                        <p class="text-xs text-slate-600">{{ $bank->account_type }}</p>
                                                        <p class="text-xs text-slate-600"><span class="font-medium">Banco:</span> {{ $bank->bank_name }}</p>
                                                        <p class="text-xs text-slate-600"><span class="font-medium">Cuenta:</span> {{ $bank->account_number }}</p>
                                                        <p class="text-xs text-slate-600"><span class="font-medium">Titular:</span> {{ $bank->account_holder }}</p>
                                                    </div>
                                                @else
                                                    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3 mb-3">
                                                        <p class="text-xs text-yellow-700">⚠️ Sin cuenta bancaria configurada.</p>
                                                    </div>
                                                @endif

                                                <form action="{{ route('bookings.store', $tour->id) }}" method="POST" enctype="multipart/form-data" class="reserve-form" data-already-booked="{{ $alreadyBooked ? '1' : '0' }}">
                                                    @csrf
                                                    <input type="hidden" name="confirm_additional" value="0" class="confirm-additional-input">

                                                    @if($alreadyBooked)
                                                        <div class="mb-3 rounded-2xl border border-yellow-200 bg-yellow-50 px-3 py-3">
                                                            <p class="text-xs font-semibold text-yellow-700">Ya te encuentras registrado en este tour.</p>
                                                            <p class="mt-1 text-xs text-yellow-700">Si deseas agregar otra persona, confirma al enviar y escribe su nombre.</p>
                                                        </div>
                                                    @endif

                                                    <div class="mb-3">
                                                        <label class="mb-1 block text-xs font-semibold text-slate-700">Nombre de la persona a registrar (opcional)</label>
                                                        <input type="text" name="passenger_name" maxlength="120" placeholder="Déjalo vacío para usar tu nombre" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-800 focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="mb-1 block text-xs font-semibold text-slate-700">📎 Comprobante de pago</label>
                                                        <input type="file" name="receipt" accept="image/*" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-600">
                                                    </div>

                                                    <p class="mb-4 text-xs leading-5 text-slate-500">
                                                        Las transferencias pueden tardar hasta <strong>24 horas en días hábiles</strong>. El equipo verificará tu pago y confirmará tu reserva.
                                                    </p>

                                                    <div class="flex gap-2">
                                                        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-500 px-3 py-2 text-sm font-semibold text-white shadow-lg">
                                                            Solicitar
                                                        </button>
                                                        <button type="button" onclick="closeModal('modal-{{ $tour->id }}')" class="rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700">
                                                            Cancelar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/60 px-4 py-6 text-sm text-slate-400">
                                No hay tours disponibles en este momento.
                            </div>
                        @endif
                    </section>
                @endif
            </div>
        </div>
    </div>

    <div id="booking-confirm-overlay" class="fixed inset-0 z-[70] hidden items-center justify-center p-4" style="background: rgba(0,0,0,.7);">
        <div class="w-full max-w-md rounded-2xl border border-gray-700 bg-gray-900 p-6 shadow-2xl">
            <h4 class="text-lg font-bold text-white mb-2">Confirmar reservacion</h4>
            <p class="text-sm text-gray-300 mb-6">
                Ya te encuentras registrado en este tour. ¿Deseas agregar otra persona a tu nombre?
            </p>
            <div class="flex justify-end gap-3">
                <button id="booking-confirm-cancel" type="button" class="rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                    No, cancelar
                </button>
                <button id="booking-confirm-accept" type="button" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                    Si, continuar
                </button>
            </div>
        </div>
    </div>

<style>
    .modal-overlay {
        background-color: rgba(0,0,0,0);
        transition: background-color 0.25s ease;
    }
    .modal-overlay.open {
        background-color: rgba(0,0,0,0.75);
    }
    .modal-box {
        transform: scale(0.85) translateY(20px);
        opacity: 0;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .modal-overlay.open .modal-box {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
</style>

<script>
    const bookingConfirmOverlay = document.getElementById('booking-confirm-overlay');
    const bookingConfirmAccept = document.getElementById('booking-confirm-accept');
    const bookingConfirmCancel = document.getElementById('booking-confirm-cancel');
    let bookingConfirmResolver = null;

    function showBookingConfirmModal() {
        return new Promise((resolve) => {
            bookingConfirmResolver = resolve;
            bookingConfirmOverlay.classList.remove('hidden');
            bookingConfirmOverlay.classList.add('flex');
        });
    }

    function closeBookingConfirmModal(result) {
        bookingConfirmOverlay.classList.remove('flex');
        bookingConfirmOverlay.classList.add('hidden');

        if (bookingConfirmResolver) {
            bookingConfirmResolver(result);
            bookingConfirmResolver = null;
        }
    }

    bookingConfirmAccept.addEventListener('click', () => closeBookingConfirmModal(true));
    bookingConfirmCancel.addEventListener('click', () => closeBookingConfirmModal(false));

    bookingConfirmOverlay.addEventListener('click', (event) => {
        if (event.target === bookingConfirmOverlay) {
            closeBookingConfirmModal(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !bookingConfirmOverlay.classList.contains('hidden')) {
            closeBookingConfirmModal(false);
        }
    });

    function openModal(id) {
        const overlay = document.getElementById(id);

        if (!overlay) {
            return;
        }

        document.body.classList.add('overflow-hidden');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

        requestAnimationFrame(() => {
            overlay.classList.add('open');
        });
    }
    function closeModal(id) {
        const overlay = document.getElementById(id);

        if (!overlay) {
            return;
        }

        overlay.classList.remove('open');
        setTimeout(() => {
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 250);
    }

    document.querySelectorAll('.reserve-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            const alreadyBooked = form.dataset.alreadyBooked === '1';
            const confirmInput = form.querySelector('.confirm-additional-input');

            if (!alreadyBooked || !confirmInput || confirmInput.value === '1') {
                return;
            }

            event.preventDefault();

            const proceed = await showBookingConfirmModal();

            if (!proceed) {
                const overlay = form.closest('.modal-overlay');
                if (overlay) {
                    closeModal(overlay.id);
                }
                return;
            }

            confirmInput.value = '1';
            form.requestSubmit();
        });
    });
</script>
</x-app-layout>
