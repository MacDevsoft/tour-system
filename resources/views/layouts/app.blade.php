<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-white">
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.12),_transparent_0,_transparent_42%),linear-gradient(180deg,_#020617_0%,_#030712_55%,_#020617_100%)]">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-16 top-20 h-56 w-56 rounded-full bg-emerald-500/10 blur-3xl"></div>
                <div class="absolute right-0 top-0 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
            </div>

            <div class="relative z-10">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="border-b border-white/10 bg-slate-950/70 shadow-lg backdrop-blur-xl">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="relative z-10">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @php
            $paymentApprovedAlertsData = collect();

            if (auth()->check()) {
                if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                    $paymentApprovedAlerts = auth()->user()
                        ->unreadNotifications()
                        ->where('type', \App\Notifications\PaymentApprovedNotification::class)
                        ->latest()
                        ->take(4)
                        ->get();

                    $paymentApprovedAlertsData = $paymentApprovedAlerts->map(function ($item) {
                        return array_merge($item->data ?? [], [
                            'notification_id' => $item->id,
                        ]);
                    });

                    if ($paymentApprovedAlerts->isNotEmpty()) {
                        $paymentApprovedAlerts->markAsRead();
                    }
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('booking_payments')) {
                    $fallbackApprovedPayments = \App\Models\BookingPayment::with('booking.tour')
                        ->where('status', 'approved')
                        ->whereNotNull('approved_at')
                        ->whereHas('booking', fn ($query) => $query->where('user_id', auth()->id()))
                        ->latest('approved_at')
                        ->take(4)
                        ->get()
                        ->map(function ($payment) {
                            return [
                                'title' => 'Tu pago fue aceptado',
                                'message' => 'Se aprobó tu comprobante y tu avance se actualizó correctamente.',
                                'payment_id' => $payment->id,
                                'payment_reference' => $payment->reference,
                                'amount' => (float) $payment->amount,
                                'tour_name' => optional(optional($payment->booking)->tour)->nombre,
                                'approved_at' => optional($payment->approved_at)->format('d/m/Y H:i'),
                                'url' => $payment->booking
                                    ? route('bookings.my-tours', ['tour_id' => $payment->booking->tour_id, 'booking_id' => $payment->booking->id])
                                    : route('bookings.my-tours'),
                            ];
                        });

                    $paymentApprovedAlertsData = $paymentApprovedAlertsData
                        ->concat($fallbackApprovedPayments)
                        ->unique(fn ($item) => ($item['payment_reference'] ?? '') . '|' . ($item['approved_at'] ?? ''))
                        ->take(4)
                        ->values();
                }
            }
        @endphp

        <div id="payment-toast-stack" class="pointer-events-none fixed right-4 top-4 z-[1200] flex w-[min(92vw,380px)] flex-col gap-3"></div>

        <script>
            function showGlobalLoader() {
                return;
            }

            function hideGlobalLoader() {
                return;
            }

            const paymentApprovedAlerts = @json($paymentApprovedAlertsData);

            function renderPaymentToast(alert, index) {
                const stack = document.getElementById('payment-toast-stack');
                if (!stack) {
                    return;
                }

                const toast = document.createElement('a');
                toast.href = alert.url || '#';
                toast.className = 'pointer-events-auto block overflow-hidden rounded-2xl border border-emerald-400/30 bg-slate-900/95 p-4 text-white shadow-2xl shadow-emerald-950/30 backdrop-blur-xl transition duration-300 hover:border-emerald-300/60 hover:bg-slate-900';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px) scale(0.98)';

                const approvedAt = alert.approved_at ? `<p class="mt-1 text-[11px] text-slate-300">Aprobado: ${alert.approved_at}</p>` : '';
                const amount = typeof alert.amount === 'number' ? alert.amount.toFixed(2) : alert.amount;

                toast.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 h-8 w-8 shrink-0 rounded-full bg-emerald-400/20 text-center text-lg leading-8">✓</div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-black tracking-wide text-emerald-300">${alert.title || 'Pago aprobado'}</p>
                            <p class="mt-1 text-sm text-slate-100">${alert.message || 'Tu pago fue aceptado.'}</p>
                            <div class="mt-2 rounded-xl border border-white/10 bg-slate-950/60 px-3 py-2 text-xs text-slate-200">
                                <p><span class="text-slate-400">Tour:</span> ${alert.tour_name || 'N/A'}</p>
                                <p><span class="text-slate-400">Referencia:</span> ${alert.payment_reference || 'N/A'}</p>
                                <p><span class="text-slate-400">Monto:</span> $${amount || '0.00'}</p>
                                ${approvedAt}
                            </div>
                        </div>
                    </div>
                `;

                stack.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0) scale(1)';
                }, 80 + (index * 120));

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-8px) scale(0.98)';
                    setTimeout(() => toast.remove(), 280);
                }, 10000 + (index * 500));
            }

            function pushBrowserPaymentNotification(alert) {
                if (!('Notification' in window)) {
                    return;
                }

                const showNotification = () => {
                    if (Notification.permission !== 'granted') {
                        return;
                    }

                    const body = `${alert.tour_name || 'Tour'} · Ref ${alert.payment_reference || 'N/A'} · $${(typeof alert.amount === 'number' ? alert.amount.toFixed(2) : alert.amount || '0.00')}`;
                    const notification = new Notification(alert.title || 'Tu pago fue aceptado', {
                        body,
                        icon: '/favicon.ico',
                    });

                    notification.onclick = () => {
                        window.focus();
                        if (alert.url) {
                            window.location.href = alert.url;
                        }
                    };
                };

                if (Notification.permission === 'default') {
                    Notification.requestPermission().then(showNotification);
                    return;
                }

                showNotification();
            }

            function paymentAlertSeenKey(alert) {
                return `payment-ok-${alert.payment_reference || alert.payment_id || 'unknown'}-${alert.approved_at || 'unknown'}`;
            }

            if (Array.isArray(paymentApprovedAlerts) && paymentApprovedAlerts.length > 0) {
                paymentApprovedAlerts.forEach((alert, index) => {
                    const seenKey = paymentAlertSeenKey(alert);
                    let alreadySeen = false;

                    try {
                        alreadySeen = localStorage.getItem(seenKey) === '1';
                    } catch (error) {
                        alreadySeen = false;
                    }

                    if (alreadySeen) {
                        return;
                    }

                    renderPaymentToast(alert, index);

                    try {
                        localStorage.setItem(seenKey, '1');
                    } catch (error) {
                        // Ignore storage restrictions in private browsing modes.
                    }

                    if (index === 0) {
                        pushBrowserPaymentNotification(alert);
                    }
                });
            }
        </script>
    </body>
</html>
