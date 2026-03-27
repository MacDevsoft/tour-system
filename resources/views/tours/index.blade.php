<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tours
        </h2>
    </x-slot>

    <div class="p-6">
<a href="/tours/create" class="bg-blue-600 !text-white px-4 py-2 rounded shadow block w-fit opacity-100 inline-block"> + Crear Tour </a>


    </div>

<div class="p-10">
    <div class="flex flex-wrap justify-center gap-12 max-w-6xl mx-auto">

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
                    <p>👥 {{ $tour->cupos_disponibles }} / {{ $tour->capacidad }}</p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('tours.show', $tour->id) }}" 
                       class="bg-blue-500 text-black px-4 py-2 rounded mt-2 inline-block">
                        Ver detalles
                    </a>
                </div>

            </div>
        @endforeach

    </div>
</div>



</x-app-layout>