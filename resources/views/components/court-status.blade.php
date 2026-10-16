@props(['court', 'long' => false])

@if ($court->isAvailable())
    <span {{ $attributes->class(['inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30']) }}>
        {{ $long ? 'Tersedia & Siap Digunakan' : 'Tersedia' }}
    </span>
@else
    <span {{ $attributes->class(['inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30']) }}>
        {{ $long ? 'Sedang Perawatan (Maintenance)' : 'Maintenance' }}
    </span>
@endif