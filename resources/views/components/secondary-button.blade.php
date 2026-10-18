<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-700 dark:text-slate-200 uppercase tracking-widest shadow-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 transition disabled:opacity-50']) }}>
    {{ $slot }}
</button>
