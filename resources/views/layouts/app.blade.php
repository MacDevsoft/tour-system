<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <title>{{ config('app.name', 'Tours by Ravers') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/ravers-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/ravers-logo.png') }}">

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

            <div class="relative z-10 flex min-h-screen flex-col pt-16">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="sticky top-16 z-30 border-b border-white/10 bg-slate-950/85 shadow-lg backdrop-blur-xl">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="relative z-10 flex-1">
                    {{ $slot }}
                </main>

                <footer class="border-t border-white/10 bg-slate-950/85 px-4 py-5 backdrop-blur-xl sm:px-6 lg:px-8">
                    <div class="mx-auto flex w-full max-w-7xl flex-col gap-3 text-sm text-slate-300 md:flex-row md:items-center md:justify-between">
                        <p>
                            © {{ now()->year }} Tours by Ravers. Desarrollado por <span class="font-semibold text-cyan-300">MacDevSoft</span>.
                        </p>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="https://www.instagram.com/toursbyravers?igsh=MWp6MG5zd2JmcGczYQ==" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-white/10 px-3 py-1.5 text-slate-200 transition hover:border-cyan-400/40 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5Zm9.1 1.15a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/>
                                </svg>
                                <span>Instagram</span>
                            </a>
                            <a href="https://wa.me/525518936972" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-white/10 px-3 py-1.5 text-slate-200 transition hover:border-emerald-400/40 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.02a9.97 9.97 0 0 1 8.62 15.02l1.24 4.53-4.64-1.22A9.98 9.98 0 1 1 12 2.02Zm0 1.5a8.48 8.48 0 0 0-7.43 12.55l.18.33-.75 2.74 2.81-.73.31.18A8.48 8.48 0 1 0 12 3.52Zm-4.4 3.95c.16-.37.34-.38.5-.39h.42c.14 0 .34.05.52.44.18.39.6 1.46.65 1.57.05.11.08.24 0 .39-.08.16-.12.25-.24.39-.12.14-.26.32-.37.43-.12.12-.24.25-.1.49.14.24.62 1.03 1.34 1.67.92.82 1.7 1.07 1.94 1.19.24.12.37.1.5-.06.14-.16.58-.67.73-.91.16-.24.32-.2.54-.12.22.08 1.39.66 1.62.78.23.12.39.18.45.28.06.1.06.58-.13 1.14-.19.56-1.1 1.07-1.52 1.14-.39.06-.89.09-1.43-.09-.33-.11-.74-.24-1.27-.47-2.24-.97-3.72-3.31-3.84-3.48-.12-.17-.91-1.21-.91-2.31 0-1.1.58-1.64.79-1.86Z"/>
                                </svg>
                                <span>WhatsApp: +52 55 1893 6972</span>
                            </a>
                        </div>
                    </div>
                </footer>
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
            const AUTO_REFRESH_MS = 45000;
            const PAYMENT_NOTIFICATION_POLL_MS = 8000;
            let skipAutoRefresh = false;

            function showGlobalLoader() {
                return;
            }

            function hideGlobalLoader() {
                return;
            }

            // Avoid stale pages when navigating back/forward from browser cache.
            window.addEventListener('pageshow', (event) => {
                const navEntries = performance.getEntriesByType('navigation');
                const isBackForward = Array.isArray(navEntries) && navEntries[0]?.type === 'back_forward';

                if (event.persisted || isBackForward) {
                    window.location.reload();
                }
            });

            document.addEventListener('submit', () => {
                skipAutoRefresh = true;
            });

            // Keep data fresh while avoiding reloads during active editing.
            setInterval(() => {
                if (skipAutoRefresh || document.visibilityState !== 'visible') {
                    return;
                }

                const activeElement = document.activeElement;
                const isEditing = activeElement instanceof HTMLElement
                    && ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeElement.tagName)
                    && !activeElement.hasAttribute('readonly')
                    && !activeElement.hasAttribute('disabled');

                if (isEditing) {
                    return;
                }

                if (document.querySelector('.modal-overlay.open')) {
                    return;
                }

                window.location.reload();
            }, AUTO_REFRESH_MS);

            const paymentApprovedAlerts = @json($paymentApprovedAlertsData);
            const paymentNotificationPollUrl = @json(route('notifications.payments.poll'));

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

            async function pollPaymentApprovedNotifications() {
                if (!paymentNotificationPollUrl || document.visibilityState !== 'visible') {
                    return;
                }

                try {
                    const response = await fetch(paymentNotificationPollUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Cache-Control': 'no-cache',
                        },
                        credentials: 'same-origin',
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    const alerts = Array.isArray(payload?.alerts) ? payload.alerts : [];

                    alerts.forEach((alert, index) => {
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
                } catch (error) {
                    // keep silent in case of transient network issues
                }
            }

            setInterval(pollPaymentApprovedNotifications, PAYMENT_NOTIFICATION_POLL_MS);
        </script>
    </body>
</html>
