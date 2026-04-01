<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Editar Tour</h2>
            <p class="text-sm text-slate-300">Actualiza la información del viaje con una vista más cómoda en pantallas pequeñas.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/30 sm:p-6 lg:p-8">
                @if(session('status'))
                    <div class="mb-5 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-6 grid gap-4 lg:grid-cols-[1.3fr_.7fr] lg:items-start">
                    <div>
                        <p class="text-sm text-cyan-300">Edición activa</p>
                        <h3 class="mt-1 text-2xl font-bold text-white sm:text-3xl">{{ $tour->nombre }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Ajusta precios, fechas, capacidad y plan de pagos sin perder claridad en móvil o escritorio.</p>
                    </div>

                    <div class="rounded-2xl border border-violet-500/20 bg-violet-500/10 p-4 text-sm text-violet-50">
                        <p class="font-semibold">Cupos disponibles</p>
                        <p class="mt-1 text-2xl font-bold">{{ $tour->cupos_disponibles }}</p>
                        <p class="mt-1 text-violet-100/90">de {{ $tour->capacidad }} lugares totales.</p>
                    </div>
                </div>

                <form action="{{ route('tours.update', $tour->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-200">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $tour->nombre) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-200">Descripción</label>
                            <textarea name="descripcion" rows="4" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">{{ old('descripcion', $tour->descripcion) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Precio total</label>
                            <input type="number" step="0.01" name="precio_total" value="{{ old('precio_total', $tour->precio_total) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Anticipo</label>
                            <input type="number" step="0.01" name="anticipo" value="{{ old('anticipo', $tour->anticipo) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Número de pagos</label>
                            <input type="number" name="payment_installments" min="1" max="60" value="{{ old('payment_installments', $tour->payment_installments) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                            <p class="mt-1 text-xs text-slate-400">Ejemplo: 15 pagos antes de la fecha límite que definas.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Fecha límite de liquidación</label>
                            <input type="date" name="payment_deadline" value="{{ old('payment_deadline', optional($tour->payment_deadline)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                            <p class="mt-1 text-xs text-slate-400">Este valor manda sobre la fecha del evento para el plan de pagos.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Capacidad</label>
                            <input type="number" name="capacidad" value="{{ old('capacidad', $tour->capacidad) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Cupos disponibles</label>
                            <input type="number" name="cupos_disponibles" value="{{ old('cupos_disponibles', $tour->cupos_disponibles) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-100 px-3 py-2.5 text-sm text-slate-700 shadow-sm" disabled>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Ubicación</label>
                            <input type="text" name="ubicacion" value="{{ old('ubicacion', $tour->ubicacion) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Punto de encuentro</label>
                            <input type="text" name="punto_encuentro" value="{{ old('punto_encuentro', $tour->punto_encuentro) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $tour->fecha_inicio) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Fecha fin</label>
                            <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $tour->fecha_fin) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Hora de salida</label>
                            <input type="time" name="hora_salida" value="{{ old('hora_salida', $tour->hora_salida) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Transporte</label>
                            <input type="text" name="transporte" value="{{ old('transporte', $tour->transporte) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:flex-wrap sm:items-center">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400 sm:w-auto">
                            Guardar cambios
                        </button>

                        <a href="{{ route('tours.show', $tour->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-slate-100 sm:w-auto">
                            Cancelar
                        </a>
                    </div>
                </form>

                <div class="mt-4 border-t border-slate-800 pt-4">
                    <form action="{{ route('tours.destroy', $tour->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este tour? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500 sm:w-auto">
                            Eliminar tour
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function syncTourDates() {
            const startInput = document.querySelector('input[name="fecha_inicio"]');
            const endInput = document.querySelector('input[name="fecha_fin"]');
            const paymentDeadlineInput = document.querySelector('input[name="payment_deadline"]');

            if (!startInput || !endInput) return;

            startInput.addEventListener('change', () => {
                if (!startInput.value) return;

                endInput.min = startInput.value;

                if (!endInput.value || endInput.value < startInput.value) {
                    endInput.value = startInput.value;
                }

                if (paymentDeadlineInput && !paymentDeadlineInput.value) {
                    paymentDeadlineInput.value = startInput.value;
                }
            });
        })();
    </script>
</x-app-layout>
