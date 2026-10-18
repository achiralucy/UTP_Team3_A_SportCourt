<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 min-h-[44px] bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl font-semibold text-xs text-slate-200 uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition disabled:opacity-50']) }}>
    {{ $slot }}
</button>
