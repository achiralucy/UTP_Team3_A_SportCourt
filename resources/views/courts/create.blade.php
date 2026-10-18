<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">Tambah Lapangan Baru</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Isi data lapangan, harga sewa, fasilitas, dan unggah foto</p>
            </div>
            <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm transition-colors">
            @include('courts.partials.form')
        </div>
    </div>
</x-app-layout>