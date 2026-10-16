@props(['court'])

<span {{ $attributes->class(['inline-block px-2.5 py-0.5 text-2xs font-extrabold uppercase rounded border', $court->category === 'futsal' ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' : 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30']) }}>
    {{ $court->category }}
</span>