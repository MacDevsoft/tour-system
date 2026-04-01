<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Crear Tour
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        <div class="border border-gray-700 rounded-xl shadow-md p-6" style="background-color:#111827;">
            <p class="mb-5 text-sm text-gray-300">
                Completa la información del nuevo tour. Puedes definir el precio total, anticipo, capacidad y fechas desde aquí.
            </p>

            <form method="POST" action="/tours" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-200">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200">Descripción</label>
                    <textarea name="descripcion" class="mt-1 block w-full border rounded px-3 py-2 text-black" rows="4">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Precio total</label>
                        <input type="number" step="0.01" name="precio_total" value="{{ old('precio_total') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Anticipo</label>
                        <input type="number" step="0.01" name="anticipo" value="{{ old('anticipo') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Capacidad</label>
                        <input type="number" name="capacidad" value="{{ old('capacidad') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Transporte</label>
                        <input type="text" name="transporte" value="{{ old('transporte') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Ubicación</label>
                        <input type="text" name="ubicacion" value="{{ old('ubicacion') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-200">Punto de encuentro</label>
                        <input type="text" name="punto_encuentro" value="{{ old('punto_encuentro') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha fin</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200">Hora de salida</label>
                    <input type="time" name="hora_salida" value="{{ old('hora_salida') }}" class="mt-1 block w-full border rounded px-3 py-2 text-black">
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Guardar</button>
                    <button type="reset" class="bg-gray-200 text-black px-4 py-2 rounded">Limpiar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>