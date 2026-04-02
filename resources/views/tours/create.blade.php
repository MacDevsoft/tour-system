<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Crear Tour</h2>
            <p class="text-sm text-slate-300">Captura toda la información con un formulario cómodo en celular, tablet o escritorio.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/30 sm:p-6 lg:p-8">
                <div class="mb-6 grid gap-4 lg:grid-cols-[1.3fr_.7fr] lg:items-start">
                    <div>
                        <p class="text-sm text-cyan-300">Nuevo registro</p>
                        <h3 class="mt-1 text-2xl font-bold text-white sm:text-3xl">Configura un tour completo</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">
                            Define precio total, anticipo, plan de pagos y fechas clave desde una sola pantalla adaptable.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm text-cyan-50">
                        <p class="font-semibold">Tip rápido</p>
                        <p class="mt-1 text-cyan-100/90">Si eliges la fecha de inicio primero, el sistema te ayuda a sugerir la fecha final y la fecha límite de pago.</p>
                    </div>
                </div>

                <form method="POST" action="/tours" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-200">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-200">Descripción</label>
                            <textarea name="descripcion" rows="4" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">{{ old('descripcion') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Precio total</label>
                            <input type="number" step="0.01" name="precio_total" value="{{ old('precio_total') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Anticipo</label>
                            <input type="number" step="0.01" name="anticipo" value="{{ old('anticipo') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Número de pagos</label>
                            <input type="number" name="payment_installments" value="{{ old('payment_installments') }}" min="1" max="60" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500" placeholder="Ej. 15">
                            <p class="mt-1 text-xs text-slate-400">Cuántos pagos deseas generar para liquidar el tour.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Fecha límite de liquidación</label>
                            <input type="date" name="payment_deadline" value="{{ old('payment_deadline') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                            <p class="mt-1 text-xs text-slate-400">Todos los pagos deberán quedar cubiertos antes de esta fecha.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Capacidad</label>
                            <input type="number" name="capacidad" value="{{ old('capacidad') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Transporte</label>
                            <input type="text" name="transporte" value="{{ old('transporte') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Ubicación</label>
                            <input type="text" name="ubicacion" value="{{ old('ubicacion') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Punto de encuentro</label>
                            <input type="text" name="punto_encuentro" value="{{ old('punto_encuentro') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200">Fecha fin</label>
                            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-200">Hora de salida</label>
                            <input type="time" name="hora_salida" value="{{ old('hora_salida') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder:text-slate-500">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500 sm:w-auto">
                            Guardar tour
                        </button>
                        <button type="reset" class="inline-flex w-full items-center justify-center rounded-xl border border-white/10 bg-slate-950 px-4 py-2.5 text-sm font-semibold text-slate-100 transition hover:bg-slate-800 sm:w-auto">
                            Limpiar formulario
                        </button>
                    </div>
                </form>
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