<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 min-h-[44px] bg-rose-600 hover:bg-rose-500 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-lg shadow-rose-600/30 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition active:bg-rose-700 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
