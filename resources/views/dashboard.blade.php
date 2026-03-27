<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    
   <h2 class="text-2xl font-bold mb-4">
    Bienvenido {{ auth()->user()->name }}
</h2>

@if(auth()->user()->role === 'admin')
    <div class="p-6 bg-green-200 rounded space-y-4">
        <p>👑 Eres ADMIN - aquí irá tu panel de control</p>

        <a href="/tours" class="bg-blue-600 !text-white px-4 py-2 rounded shadow inline-block">
            Ver / Editar Tours
        </a>
    </div>
@else
    <div class="p-6">
        <h3 class="text-2xl font-bold mb-6">Tours Disponibles</h3>
        
        @php
            $tours = \App\Models\Tour::where('is_enabled', true)->get();
        @endphp

        @if($tours->count() > 0)
            <div class="flex flex-wrap justify-center gap-12 max-w-6xl">
                @foreach($tours as $tour)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-80 text-center">

                        <h3 class="text-xl font-bold mb-2">
                            {{ $tour->nombre }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-4">
                            {{ $tour->descripcion }}
                        </p>

                        <div class="text-sm text-gray-700 space-y-2 flex flex-col items-center">
                            <p>📍 {{ $tour->ubicacion }}</p>
                            <p>📅 {{ $tour->fecha_inicio }} → {{ $tour->fecha_fin }}</p>
                            <p class="text-green-600 font-bold">💰 ${{ number_format($tour->precio_total, 2) }}</p>
                            <p>👥 {{ $tour->cupos_disponibles }} / {{ $tour->cupos_totales }}</p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('tours.show', $tour->id) }}" 
                               class="bg-blue-500 text-black px-4 py-2 rounded mt-2 inline-block">
                                Ver detalles
                            </a>
                            <br>
                            <a href="#" 
                               style="background-color: #22c55e;"
                               class="!text-white px-4 py-2 rounded-xl shadow-md inline-block mt-2 w-full text-center font-semibold">
                                Reservar
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No hay tours disponibles en este momento.</p>
        @endif
    </div>
@endif

</div>
            </div>
        </div>
    </div>
</x-app-layout>
