@props(['court', 'height' => 'h-44'])

<div {{ $attributes->class(["relative overflow-hidden bg-gradient-to-tr", $height, $court->category === 'futsal' ? 'from-blue-900 via-indigo-950 to-slate-900' : 'from-emerald-900 via-teal-950 to-slate-900']) }}>
    @if ($court->image_url)
        <img src="{{ $court->image_url }}" alt="Foto {{ $court->name }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-slate-950/40"></div>
    @else
        <div class="absolute -right-4 -bottom-4 text-white/5 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
            </svg>
        </div>
    @endif

    <div class="relative z-10 h-full">
        {{ $slot }}
    </div>
</div>