<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] bg-emerald-600 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:bg-emerald-500 active:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg shadow-emerald-600/25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
