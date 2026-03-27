<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis tours</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6">Mis reservas</h3>

                @if($tourGroups->count() === 0)
                    <p class="text-gray-500">Aún no has reservado ningún tour.</p>
                @else
                    <div class="flex flex-wrap justify-center gap-6">
                        @foreach($tourGroups as $tourGroup)
                            @php
                                $booking = $tourGroup->first();
                                $tour = $booking->tour;
                                $pendingCount = $tourGroup->where('status', 'pending')->count();
                                $approvedCount = $tourGroup->where('status', 'approved')->count();
                            @endphp
                            <a href="{{ route('bookings.show-tour', $tour->id) }}" class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-80 text-center hover:shadow-lg transition">
                                <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $tour->nombre }}</h4>
                                <p class="text-sm text-gray-600 mb-2">📅 {{ $tour->fecha_inicio ?? 'Sin fecha' }} {{ $tour->fecha_fin ? '→ ' . $tour->fecha_fin : '' }}</p>
                                <p class="text-sm text-gray-700 mb-1">💰 Anticipo: ${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                                <p class="text-sm text-gray-700 mb-4">👥 Cupos: {{ $tour->cupos_disponibles }}/{{ $tour->cupos_totales }}</p>

                                <div class="flex items-center justify-center gap-2 text-xs font-semibold">
                                    @if($pendingCount > 0)
                                        <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">Pendientes: {{ $pendingCount }}</span>
                                    @endif
                                    @if($approvedCount > 0)
                                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">Aprobadas: {{ $approvedCount }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
