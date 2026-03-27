<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Comprobante de reserva</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg">
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
                                @if(!empty($booking->passenger_name))
                                    <p><span class="font-semibold">Persona registrada:</span> {{ $booking->passenger_name }}</p>
                                @endif
                                <p><span class="font-semibold">Cantidad (anticipo):</span> ${{ number_format($booking->amount_paid, 2) }}</p>
                                <p><span class="font-semibold">Fecha:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
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

                    <div class="mt-5">
                        <a href="{{ route('bookings.my-tours') }}"
                           class="block w-full rounded-xl px-4 py-2.5 text-center text-base font-bold text-white shadow-lg"
                           style="background: linear-gradient(90deg, #34d399 0%, #3b82f6 100%);">
                            Aceptar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
