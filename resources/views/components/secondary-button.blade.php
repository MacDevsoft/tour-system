<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-xl border border-white/10 bg-slate-950 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-100 transition duration-150 ease-in-out hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
