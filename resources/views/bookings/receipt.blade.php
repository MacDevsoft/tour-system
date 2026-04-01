<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Comprobante de reserva</h2>
            <p class="text-sm text-slate-300">Tu comprobante digital ahora mantiene mejor proporción en celular y tablet.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <div id="booking-digital-receipt" class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg">
                <div class="relative h-36 overflow-hidden border-b border-gray-900" style="background: linear-gradient(125deg, #162046 0%, #233b7a 45%, #271247 100%);">
                    <div class="absolute -left-10 top-8 h-24 w-24 rounded-full bg-cyan-400/20 blur-2xl"></div>
                    <div class="absolute right-0 top-0 h-20 w-20 rounded-full bg-fuchsia-400/20 blur-2xl"></div>
                    <div class="relative z-10 px-6 py-5 text-white">
                        <p class="text-xs font-semibold uppercase tracking-widest text-cyan-200">Comprobante digital</p>
                        <h3 class="mt-1 text-2xl font-extrabold leading-tight text-white">Reserva realizada con exito</h3>
                        <p class="mt-1 text-sm text-slate-100">Guarda este comprobante o tomale captura de pantalla.</p>
                    </div>
                </div>

                <div class="absolute left-1/2 top-36 z-20 h-20 w-20 -translate-x-1/2 -translate-y-1/2 rounded-full border-4 border-white bg-slate-900 p-1.5 shadow-xl">
                    <img src="{{ asset('images/ravers-logo.png') }}" alt="Ravers" class="h-full w-full rounded-full object-cover">
                </div>

                <div class="px-6 pb-6 pt-14">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[1.3fr_.7fr]">
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tour</h4>
                            <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $booking->tour->nombre }}</p>

                            <div class="mt-3 space-y-1.5 text-sm text-slate-700">
                                <p><span class="font-semibold">ID de compra:</span> {{ $booking->purchase_id }}</p>
                                <p><span class="font-semibold">Código digital:</span> {{ $booking->digital_receipt_code ?? 'Pendiente' }}</p>
                                @if(!empty($booking->passenger_name))
                                    <p><span class="font-semibold">Persona registrada:</span> {{ $booking->passenger_name }}</p>
                                @endif
                                <p><span class="font-semibold">Cantidad (anticipo):</span> ${{ number_format($booking->amount_paid, 2) }}</p>
                                <p><span class="font-semibold">Saldo por liquidar:</span> ${{ number_format(max(0, ($booking->tour->precio_total ?? 0) - $booking->totalApprovedPayments()), 2) }}</p>
                                <p><span class="font-semibold">Fecha:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                                @if($booking->paymentDeadline())
                                    <p><span class="font-semibold">Liquidar antes del:</span> {{ $booking->paymentDeadline()->format('d/m/Y') }}</p>
                                @endif
                                <p>
                                    <span class="font-semibold">Estado:</span>
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                                        Pendiente de aprobacion
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="mb-1.5 text-xs font-semibold text-slate-600">Comprobante subido</p>
                            <img src="{{ route('bookings.receipt-image', $booking->id) }}" alt="Comprobante"
                                 class="h-36 w-full rounded-lg border border-slate-200 object-contain bg-white">
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        @if($booking->payments->count() > 0)
                            <div class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm text-cyan-900">
                                Se generó tu <strong>plan de pagos quincenal</strong>. Entra a <strong>Mis tours</strong> para ver próximas fechas y subir cada comprobante.
                            </div>
                        @endif

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-sm font-semibold text-slate-700">Descargar o compartir comprobante</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Configurado para compartir al número <strong>55 3156 6578</strong>.</p>
                            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                <button type="button" onclick="downloadDigitalReceiptImage('booking-digital-receipt', 'comprobante-digital-reserva-{{ $booking->purchase_id }}.png')"
                                        class="rounded-xl bg-slate-800 px-4 py-2 text-center text-sm font-bold text-white">
                                    Descargar comprobante
                                </button>
                                <button type="button" onclick="shareBookingReceiptViaWhatsApp()"
                                        class="rounded-xl bg-green-600 px-4 py-2 text-sm font-bold text-white">
                                    Compartir por WhatsApp
                                </button>
                            </div>
                        </div>

                        <a href="{{ route('bookings.my-tours', ['tour_id' => $booking->tour_id]) }}"
                           class="block w-full rounded-xl px-4 py-2.5 text-center text-base font-bold text-white shadow-lg"
                           style="background: linear-gradient(90deg, #34d399 0%, #3b82f6 100%);">
                            Ver plan de pagos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        async function buildDigitalReceiptFile(elementId, filename) {
            const element = document.getElementById(elementId);
            if (!element || typeof html2canvas === 'undefined') {
                throw new Error('No se pudo generar la imagen del comprobante digital.');
            }

            const canvas = await html2canvas(element, {
                backgroundColor: '#ffffff',
                scale: 2,
                useCORS: true,
            });

            return new Promise((resolve, reject) => {
                canvas.toBlob((blob) => {
                    if (!blob) {
                        reject(new Error('No se pudo crear la imagen del comprobante.'));
                        return;
                    }

                    resolve(new File([blob], filename, { type: 'image/png' }));
                }, 'image/png');
            });
        }

        async function downloadDigitalReceiptImage(elementId, filename) {
            try {
                const file = await buildDigitalReceiptFile(elementId, filename);
                const url = URL.createObjectURL(file);
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
                URL.revokeObjectURL(url);
            } catch (error) {
                alert('No se pudo descargar la imagen del comprobante digital.');
            }
        }

        async function shareBookingReceiptViaWhatsApp() {
            const whatsappNumber = '525531566578';
            const message = @json("Comprobante digital de reserva\nTour: " . $booking->tour->nombre . "\nID de compra: " . $booking->purchase_id . "\nCódigo digital: " . ($booking->digital_receipt_code ?? 'N/D') . "\nAnticipo: $" . number_format($booking->amount_paid, 2) . "\nFecha: " . $booking->created_at->format('d/m/Y H:i') . "\nEstado: Pendiente de aprobación");

            if (navigator.share && navigator.canShare) {
                try {
                    const file = await buildDigitalReceiptFile('booking-digital-receipt', 'comprobante-digital-reserva-{{ $booking->purchase_id }}.png');

                    if (navigator.canShare({ files: [file] })) {
                        await navigator.share({
                            title: 'Comprobante de reserva',
                            text: message,
                            files: [file],
                        });
                        return;
                    }
                } catch (error) {
                    // fallback a WhatsApp con texto
                }
            }

            const shareText = `${message}\n${window.location.href}`;
            window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(shareText)}`, '_blank');
        }
    </script>
</x-app-layout>
