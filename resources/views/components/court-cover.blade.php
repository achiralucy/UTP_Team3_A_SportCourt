@props(['court', 'height' => 'h-44'])

<div {{ $attributes->class(["relative overflow-hidden bg-gradient-to-tr", $height, $court->category === 'futsal' ? 'from-blue-900 via-indigo-950 to-slate-900' : 'from-emerald-900 via-teal-950 to-slate-900']) }}>
@if ($court->image)
    <img
        src="{{ asset('storage/' . $court->image) }}"
        alt="Foto {{ $court->name }}"
        class="absolute inset-0 w-full h-full object-cover"
        loading="lazy"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-slate-950/40"></div>
@else
    {{-- Tampilan cadangan jika gambar belum tersedia --}}
@endif

    <div class="relative z-10 h-full">
        {{ $slot }}
    </div>
</div>