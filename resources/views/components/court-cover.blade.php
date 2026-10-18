@props(['court', 'aspect' => 'aspect-video'])

<div {{ $attributes->class([
    "relative isolate overflow-hidden rounded-2xl w-full",
    $aspect,
    $court->category === 'futsal' ? 'bg-gradient-to-br from-blue-950 via-slate-900 to-slate-950' : 'bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950'
]) }}>
@if ($court->image)
    <img
        src="{{ asset('storage/' . $court->image) }}"
        alt="Foto {{ $court->name }}"
        class="absolute inset-0 w-full h-full object-cover -z-20"
        loading="lazy"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/50 to-slate-950/20 -z-10 pointer-events-none"></div>
@else
    {{-- Sporty court lines placeholder SVG --}}
    <div class="absolute inset-0 opacity-25 pointer-events-none -z-20">
        @if ($court->category === 'futsal')
            <!-- Futsal Pitch Lines Vector -->
            <svg class="w-full h-full text-blue-400" viewBox="0 0 400 225" preserveAspectRatio="xMidYMid slice" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="15" y="15" width="370" height="195" rx="4" vector-effect="non-scaling-stroke" />
                <line x1="200" y1="15" x2="200" y2="210" vector-effect="non-scaling-stroke" />
                <circle cx="200" cy="112.5" r="35" vector-effect="non-scaling-stroke" />
                <circle cx="200" cy="112.5" r="2.5" fill="currentColor" />
                <path d="M 15 60 H 65 A 52.5 52.5 0 0 1 65 165 H 15" vector-effect="non-scaling-stroke" />
                <circle cx="50" cy="112.5" r="2" fill="currentColor" />
                <path d="M 385 60 H 335 A 52.5 52.5 0 0 0 335 165 H 385" vector-effect="non-scaling-stroke" />
                <circle cx="350" cy="112.5" r="2" fill="currentColor" />
            </svg>
        @else
            <!-- Badminton Court Lines Vector -->
            <svg class="w-full h-full text-emerald-400" viewBox="0 0 400 225" preserveAspectRatio="xMidYMid slice" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="25" y="20" width="350" height="185" vector-effect="non-scaling-stroke" />
                <line x1="25" y1="36" x2="375" y2="36" stroke-dasharray="4 2" vector-effect="non-scaling-stroke" />
                <line x1="25" y1="189" x2="375" y2="189" stroke-dasharray="4 2" vector-effect="non-scaling-stroke" />
                <line x1="25" y1="112.5" x2="160" y2="112.5" vector-effect="non-scaling-stroke" />
                <line x1="240" y1="112.5" x2="375" y2="112.5" vector-effect="non-scaling-stroke" />
                <line x1="160" y1="20" x2="160" y2="205" vector-effect="non-scaling-stroke" />
                <line x1="240" y1="20" x2="240" y2="205" vector-effect="non-scaling-stroke" />
                <line x1="200" y1="15" x2="200" y2="210" stroke-width="2.5" vector-effect="non-scaling-stroke" />
                <line x1="50" y1="20" x2="50" y2="205" vector-effect="non-scaling-stroke" />
                <line x1="350" y1="20" x2="350" y2="205" vector-effect="non-scaling-stroke" />
            </svg>
        @endif
    </div>
    <div class="absolute -top-10 -right-10 w-44 h-44 {{ $court->category === 'futsal' ? 'bg-blue-500/15' : 'bg-emerald-500/15' }} rounded-full blur-2xl pointer-events-none -z-10"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent -z-10 pointer-events-none"></div>
@endif

    <div class="relative z-10 h-full p-5 flex flex-col justify-between">
        {{ $slot }}
    </div>
</div>