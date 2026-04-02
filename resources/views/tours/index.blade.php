<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-white leading-tight">Tours</h2>
            <p class="text-sm text-slate-300">Consulta los tours disponibles desde cualquier dispositivo.</p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-6">
            @php
                $formatHumanDate = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') : 'Por confirmar';
            @endphp
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-300">Listado general</p>
                    <h3 class="text-2xl font-bold text-white sm:text-3xl">Tours disponibles</h3>
                </div>

                <a href="/tours/create"
                   class="inline-flex w-full items-center justify-center rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-cyan-900/30 transition hover:bg-cyan-500 sm:w-auto">
                    + Crear Tour
                </a>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/30 sm:p-6 lg:p-8">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($tours as $tour)
                        <article class="flex h-full flex-col rounded-2xl border border-white/10 bg-slate-950/70 p-5 shadow-lg shadow-black/20">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="text-lg font-bold text-white sm:text-xl">{{ $tour->nombre }}</h4>
                                <span class="rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-300">
                                    {{ $tour->cupos_disponibles }} lugares
                                </span>
                            </div>

                            <p class="mt-3 text-sm leading-6 text-slate-300">
                                {{ $tour->descripcion ?: 'Este tour aún no tiene una descripción registrada.' }}
                            </p>

                            <div class="mt-4 grid gap-3 text-sm text-slate-200 sm:grid-cols-2">
                                <div class="rounded-xl border border-white/10 bg-slate-900 px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Ubicación</p>
                                    <p class="mt-1 font-semibold">{{ $tour->ubicacion ?: 'Por confirmar' }}</p>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-slate-900 px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Precio</p>
                                    <p class="mt-1 font-semibold text-emerald-600">${{ number_format($tour->precio_total, 2) }}</p>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-slate-900 px-3 py-2 sm:col-span-2">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Fechas</p>
                                    <p class="mt-1 font-semibold">{{ $tour->fecha_inicio }} → {{ $tour->fecha_fin }}</p>
                                    <p class="mt-1 text-[11px] text-slate-500">{{ $formatHumanDate($tour->fecha_inicio) }} → {{ $formatHumanDate($tour->fecha_fin) }}</p>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-slate-900 px-3 py-2 sm:col-span-2">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Capacidad</p>
                                    <p class="mt-1 font-semibold">{{ $tour->cupos_disponibles }} disponibles de {{ $tour->capacidad }}</p>
                                </div>
                            </div>

                            <div class="mt-5 pt-2">
                                <a href="{{ route('tours.show', $tour->id) }}"
                                   class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                                    Ver detalles
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/40 p-8 text-center text-slate-300 sm:col-span-2 xl:col-span-3">
                            <p class="text-lg font-semibold text-white">Aún no hay tours registrados.</p>
                            <p class="mt-2 text-sm">Cuando agregues uno, aparecerá aquí con formato adaptable para celular, tablet y escritorio.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>