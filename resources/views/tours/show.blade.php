<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del Tour
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $tour->nombre }}</h1>

            <p class="text-gray-700 mb-4">{{ $tour->descripcion }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div class="space-y-2">
                    <p><span class="font-semibold">Precio total:</span> ${{ number_format($tour->precio_total, 2) }}</p>
                    <p><span class="font-semibold">Anticipo:</span> ${{ number_format($tour->anticipo ?? 0, 2) }}</p>
                    <p><span class="font-semibold">Capacidad total:</span> {{ $tour->capacidad ?? $tour->cupos_totales }}</p>
                    <p><span class="font-semibold">Cupos disponibles:</span> {{ $tour->cupos_disponibles ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Transporte:</span> {{ $tour->transporte ?? 'No especificado' }}</p>
                    <p><span class="font-semibold">Hora de salida:</span> {{ $tour->hora_salida ?? 'No especificada' }}</p>
                </div>

                <div class="space-y-2">
                    <p><span class="font-semibold">Ubicación:</span> {{ $tour->ubicacion ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Punto de encuentro:</span> {{ $tour->punto_encuentro ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Fecha inicio:</span> {{ $tour->fecha_inicio ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Fecha fin:</span> {{ $tour->fecha_fin ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Creado:</span> {{ $tour->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Actualizado:</span> {{ $tour->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="/tours" class="bg-gray-200 text-black px-4 py-2 rounded inline-block">Volver</a>
                @else
                    <a href="/dashboard" class="bg-gray-200 text-black px-4 py-2 rounded inline-block">Volver</a>
                @endif
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('tours.edit', $tour->id) }}" class="bg-yellow-400 text-black px-4 py-2 rounded inline-block ml-2">Editar</a>
                    <a href="{{ route('tours.toggle', $tour->id) }}" 
                       style="@if($tour->is_enabled) background-color: #dc2626; @else background-color: #16a34a; @endif"
                       class="text-white px-4 py-2 rounded inline-block ml-2">
                        @if($tour->is_enabled) Deshabilitar @else Habilitar @endif
                    </a>
                @else
                    <a href="#" style="background-color: #22c55e;" class="text-white px-4 py-2 rounded inline-block ml-2">Reservar</a>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
