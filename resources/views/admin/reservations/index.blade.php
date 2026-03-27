<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Administración</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color:#111827;">
                <h3 class="text-2xl font-bold mb-6 text-white">Tours disponibles</h3>

                @if(session('status'))
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('status') }}</div>
                @endif

                <div class="max-w-6xl mx-auto px-6 mb-6">
                    <div class="flex flex-wrap justify-center" style="column-gap:32px; row-gap:24px;">
                    @forelse($tours as $tour)
                        <a href="{{ route('admin.index', ['tour_id' => $tour->id, 'status' => 'pending']) }}"
                           class="rounded-xl text-center transition"
                           style="{{ optional($selectedTour)->id === $tour->id ? 'width:16rem;padding:1.5rem;border:2px solid #16a34a;background-color:#1f2937;box-shadow:0 0 0 3px rgba(34,197,94,.35), 0 10px 20px rgba(0,0,0,.35);transform:scale(1.05);' : 'width:13rem;padding:1rem;border:1px solid #374151;background-color:#111827;box-shadow:0 4px 10px rgba(0,0,0,.25);' }}">
                            <p class="text-lg font-bold text-white mb-2">{{ $tour->nombre }}</p>
                            <p class="text-sm text-gray-300 mb-1">{{ $tour->fecha_inicio ?? 'Sin fecha' }} {{ $tour->fecha_fin ? '→ ' . $tour->fecha_fin : '' }}</p>
                            <p class="text-sm text-gray-200 font-semibold">Cupos: {{ $tour->cupos_disponibles }}/{{ $tour->cupos_totales }}</p>
                        </a>
                    @empty
                        <p class="text-gray-300">No hay tours disponibles.</p>
                    @endforelse
                    </div>
                </div>

                @if($selectedTour)
                    <div class="flex items-center gap-2 mb-4">
                        <a href="{{ route('admin.index', ['tour_id' => $selectedTour->id, 'status' => 'pending']) }}"
                           style="{{ $status === 'pending' ? 'background-color:#15803d;color:#ffffff;box-shadow:0 4px 8px rgba(0,0,0,.25);transform:scale(1.03);font-weight:600;' : 'background-color:#1f2937;color:#e5e7eb;border:1px solid #374151;' }}"
                           class="text-sm px-3 py-2 rounded inline-block transition">
                            Reservadas
                        </a>
                        <a href="{{ route('admin.index', ['tour_id' => $selectedTour->id, 'status' => 'approved']) }}"
                           style="{{ $status === 'approved' ? 'background-color:#15803d;color:#ffffff;box-shadow:0 4px 8px rgba(0,0,0,.25);transform:scale(1.03);font-weight:600;' : 'background-color:#1f2937;color:#e5e7eb;border:1px solid #374151;' }}"
                           class="text-sm px-3 py-2 rounded inline-block transition">
                            Aprobadas
                        </a>
                    </div>

                    @if($bookings->isEmpty())
                        <p class="text-gray-300">No hay solicitudes en esta sección.</p>
                    @else
                        <div class="w-full overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-sm text-gray-700">
                                <thead class="bg-gray-100 text-gray-800">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-center">#</th>
                                        <th class="px-4 py-3 font-semibold text-center">Usuario</th>
                                        <th class="px-4 py-3 font-semibold text-center">Persona registrada</th>
                                        <th class="px-4 py-3 font-semibold text-center">Correo</th>
                                        <th class="px-4 py-3 font-semibold text-center">ID compra</th>
                                        <th class="px-4 py-3 font-semibold text-center">Monto</th>
                                        <th class="px-4 py-3 font-semibold text-center">Fecha</th>
                                        <th class="px-4 py-3 font-semibold text-center">Comprobante</th>
                                        <th class="px-4 py-3 font-semibold text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-4 py-3 text-center align-middle font-medium">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 text-center align-middle">{{ $booking->user->name }}</td>
                                            <td class="px-4 py-3 text-center align-middle">{{ $booking->passenger_name ?: $booking->user->name }}</td>
                                            <td class="px-4 py-3 text-center align-middle">{{ $booking->user->email }}</td>
                                            <td class="px-4 py-3 font-medium text-center align-middle">{{ $booking->purchase_id }}</td>
                                            <td class="px-4 py-3 text-center align-middle">${{ number_format($booking->amount_paid, 2) }}</td>
                                            <td class="px-4 py-3 text-center align-middle">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                <button type="button"
                                                        onclick="openReceiptModal('{{ route('bookings.receipt-image', $booking->id) }}')"
                                                        style="background-color:#16a34a;"
                                                        class="text-white px-3 py-1.5 rounded text-xs">
                                                    Ver comprobante
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                @if($booking->status === 'pending')
                                                    <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit"
                                                                style="background-color:#16a34a;color:#ffffff;"
                                                                class="px-3 py-1.5 rounded text-xs font-semibold">
                                                            Aprobar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-block px-2 py-1 text-xs rounded bg-green-100 text-green-700">Aprobada</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <p class="text-gray-300">Selecciona un tour para ver sus solicitudes.</p>
                @endif
            </div>
        </div>
    </div>

    <div id="receipt-modal" class="fixed inset-0 z-50 items-center justify-center" style="display:none;background: rgba(0,0,0,.75);">
        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-xl relative" style="width:min(92vw, 420px); padding:14px 12px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <button type="button" onclick="closeReceiptModal()" style="position:absolute; top:10px; right:10px; z-index:10; background-color:#dc2626;color:#ffffff;" class="px-2 py-1 rounded text-xs font-semibold">
                Cerrar
            </button>
            <h4 class="text-base font-bold mb-3 text-white">Comprobante</h4>
            <img id="receipt-modal-image" src="" alt="Comprobante" class="mx-auto rounded border border-gray-700 bg-gray-800" style="width:220px;height:320px;object-fit:contain;display:block;">
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
