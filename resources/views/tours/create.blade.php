<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Crear Tour
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl">
        <form method="POST" action="/tours">
            @csrf

            <!-- Nombre -->
            <div class="mb-4">
                <label class="block font-semibold">Nombre</label>
                <input type="text" name="nombre" class="border p-2 w-full rounded">
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label class="block font-semibold">Descripción</label>
                <textarea name="descripcion" class="border p-2 w-full rounded"></textarea>
            </div>

            <!-- Precio -->
            <div class="mb-4">
                <label class="block font-semibold">Precio total (MXN)</label>
                <input type="number" step="0.01" name="precio_total" class="border p-2 w-full rounded">
            </div>

            <!-- Anticipo -->
            <div class="mb-4">
                <label class="block font-semibold">Anticipo (MXN)</label>
                <input type="number" step="0.01" name="anticipo" class="border p-2 w-full rounded">
            </div>

            <!-- Fechas -->
            <div class="mb-4">
                <label class="block font-semibold">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Fecha fin</label>
                <input type="date" name="fecha_fin" class="border p-2 w-full rounded">
            </div>

            <!-- Ubicación -->
            <div class="mb-4">
                <label class="block font-semibold">Ubicación</label>
                <input type="text" name="ubicacion" class="border p-2 w-full rounded">
            </div>

            <!-- Punto de encuentro -->
            <div class="mb-4">
                <label class="block font-semibold">Punto de encuentro</label>
                <input type="text" name="punto_encuentro" class="border p-2 w-full rounded">
            </div>

            <!-- Horas -->
            <div class="mb-4">
                <label class="block font-semibold">Hora de salida</label>
                <input type="time" name="hora_salida" class="border p-2 w-full rounded">
            </div>

          

            <!-- Transporte -->
            <div class="mb-4">
                <label class="block font-semibold">Transporte</label>
                <input type="text" name="transporte" class="border p-2 w-full rounded">
            </div>

            <!-- Capacidad -->
            <div class="mb-4">
                <label class="block font-semibold">Capacidad</label>
                <input type="number" name="capacidad" class="border p-2 w-full rounded">
            </div>

            <!-- Botones -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 !text-white px-4 py-2 rounded shadow block w-fit opacity-100 inline-block">
                    Guardar
                </button>

                <button type="reset" class="bg-blue-600 !text-white px-4 py-2 rounded shadow block w-fit opacity-100 inline-block">
                    Limpiar
                </button>
            </div>

        </form>
    </div>
</x-app-layout>