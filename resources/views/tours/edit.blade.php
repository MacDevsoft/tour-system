<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Editar Tour</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        <div class="border border-gray-700 rounded-xl shadow-md p-6" style="background-color:#111827;">
            @if(session('status'))
                <div class="mb-4 text-green-600">{{ session('status') }}</div>
            @endif

            <form action="{{ route('tours.update', $tour->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-200">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $tour->nombre) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200">Descripción</label>
                    <textarea name="descripcion" class="mt-1 block w-full border rounded px-3 py-2">{{ old('descripcion', $tour->descripcion) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Precio total</label>
                        <input type="number" step="0.01" name="precio_total" value="{{ old('precio_total', $tour->precio_total) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Anticipo</label>
                        <input type="number" step="0.01" name="anticipo" value="{{ old('anticipo', $tour->anticipo) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Capacidad</label>
                        <input type="number" name="capacidad" value="{{ old('capacidad', $tour->capacidad) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Cupos disponibles</label>
                        <input type="number" name="cupos_disponibles" value="{{ old('cupos_disponibles', $tour->cupos_disponibles) }}" class="mt-1 block w-full border rounded px-3 py-2" disabled>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Ubicación</label>
                        <input type="text" name="ubicacion" value="{{ old('ubicacion', $tour->ubicacion) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-200">Punto de encuentro</label>
                        <input type="text" name="punto_encuentro" value="{{ old('punto_encuentro', $tour->punto_encuentro) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $tour->fecha_inicio) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha fin</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $tour->fecha_fin) }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200">Hora de salida</label>
                    <input type="time" name="hora_salida" value="{{ old('hora_salida', $tour->hora_salida) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200">Transporte</label>
                    <input type="text" name="transporte" value="{{ old('transporte', $tour->transporte) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Guardar</button>

                    <a href="{{ route('tours.show', $tour->id) }}" class="bg-gray-200 text-black px-4 py-2 rounded">Cancelar</a>
                </div>
            </form>

            <div class="mt-4">
                <form action="{{ route('tours.destroy', $tour->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este tour? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Eliminar tour</button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
