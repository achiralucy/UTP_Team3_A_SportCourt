<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">Tambah Lapangan Baru</h2>
                <p class="text-xs text-slate-400 mt-1">Isi data lapangan, harga sewa, fasilitas, dan unggah foto</p>
            </div>
            <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm">
            @include('courts.partials.form')
        </div>
    </div>
</x-app-layout>