<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Comprobante de reserva</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Reserva realizada con éxito</h3>
                <p class="text-gray-600 mb-6">Guarda este comprobante o tómale captura de pantalla.</p>

                <div class="space-y-2 text-sm text-gray-700">
                    <p><span class="font-semibold">Tour:</span> {{ $booking->tour->nombre }}</p>
                    <p><span class="font-semibold">ID de compra:</span> {{ $booking->purchase_id }}</p>
                    <p><span class="font-semibold">Cantidad (anticipo):</span> ${{ number_format($booking->amount_paid, 2) }}</p>
                    <p><span class="font-semibold">Fecha:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Estado:</span> Pendiente de aprobación</p>
                </div>

                <div class="mt-5">
                    <p class="text-sm font-semibold mb-1">Comprobante subido:</p>
                    <img src="{{ route('bookings.receipt-image', $booking->id) }}" alt="Comprobante"
                         class="w-60 h-36 object-cover border rounded-lg">
                </div>

                <div class="mt-6">
                    <a href="{{ route('bookings.my-tours') }}" class="bg-green-600 text-white px-4 py-2 rounded">Aceptar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
