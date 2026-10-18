@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-3.5 bg-emerald-950/60 border border-emerald-500/30 rounded-xl font-semibold text-xs text-emerald-300 mb-4 flex items-center gap-2']) }}>
        <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ $status }}</span>
    </div>
@endif
