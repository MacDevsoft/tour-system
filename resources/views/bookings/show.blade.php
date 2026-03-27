<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ $tour->nombre }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color:#111827;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white">Detalle de mis reservas</h3>
                    <a href="{{ route('bookings.my-tours') }}" class="bg-gray-200 text-black px-4 py-2 rounded">Volver</a>
                </div>

                @if($pendingBookings->count() > 0)
                    <div class="mb-8">
                        <h4 class="text-lg font-bold text-yellow-700 mb-3">Pendientes</h4>
                        <div class="w-full overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-sm text-gray-700">
                                <thead class="bg-gray-100 text-gray-800">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-center">#</th>
                                        <th class="px-4 py-3 font-semibold text-center">ID compra</th>
                                        <th class="px-4 py-3 font-semibold text-center">Monto</th>
                                        <th class="px-4 py-3 font-semibold text-center">Fecha</th>
                                        <th class="px-4 py-3 font-semibold text-center">Comprobante</th>
                                        <th class="px-4 py-3 font-semibold text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($pendingBookings as $booking)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-center align-middle">{{ $booking->purchase_id }}</td>
                                            <td class="px-4 py-3 text-center align-middle">${{ number_format($booking->amount_paid, 2) }}</td>
                                            <td class="px-4 py-3 text-center align-middle">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                <button type="button" onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')" style="background-color:#16a34a;" class="text-white px-3 py-1.5 rounded text-xs">
                                                    Ver comprobante
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                <span class="inline-block px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">Pendiente</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($approvedBookings->count() > 0)
                    <div>
                        <h4 class="text-lg font-bold text-green-700 mb-3">Aprobadas</h4>
                        <div class="w-full overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-sm text-gray-700">
                                <thead class="bg-gray-100 text-gray-800">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-center">#</th>
                                        <th class="px-4 py-3 font-semibold text-center">ID compra</th>
                                        <th class="px-4 py-3 font-semibold text-center">Monto</th>
                                        <th class="px-4 py-3 font-semibold text-center">Fecha</th>
                                        <th class="px-4 py-3 font-semibold text-center">Comprobante</th>
                                        <th class="px-4 py-3 font-semibold text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($approvedBookings as $booking)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-center align-middle">{{ $booking->purchase_id }}</td>
                                            <td class="px-4 py-3 text-center align-middle">${{ number_format($booking->amount_paid, 2) }}</td>
                                            <td class="px-4 py-3 text-center align-middle">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                <button type="button" onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')" style="background-color:#16a34a;" class="text-white px-3 py-1.5 rounded text-xs">
                                                    Ver comprobante
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                <span class="inline-block px-2 py-1 text-xs rounded bg-green-100 text-green-700">Aprobada</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="receipt-modal" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background: rgba(0,0,0,.75);">
        <div class="bg-white rounded-xl shadow-xl relative" style="width:min(92vw, 420px); padding:14px 12px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <button type="button" onclick="closeReceiptModal()" style="position:absolute; top:10px; right:10px; z-index:10; background-color:#dc2626;color:#ffffff;" class="px-2 py-1 rounded text-xs font-semibold">
                Cerrar
            </button>
            <h4 class="text-base font-bold mb-3">Comprobante</h4>
            <img id="receipt-modal-image" src="" alt="Comprobante" class="mx-auto rounded border bg-gray-50" style="width:220px;height:320px;object-fit:contain;display:block;">
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
    </script>
</x-app-layout>
