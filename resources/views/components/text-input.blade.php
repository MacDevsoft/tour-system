@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border border-slate-700 bg-slate-950 text-white placeholder:text-slate-500 shadow-sm focus:border-cyan-500 focus:ring-cyan-500']) }}>
