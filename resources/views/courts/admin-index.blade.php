<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Kelola Data Lapangan (Admin)
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Tambah, perbarui informasi, fasilitas, dan kelola status ketersediaan lapangan olahraga
                </p>
            </div>
            <a href="{{ route('admin.courts.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/25 transition">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                + Tambah Lapangan Baru
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter Controls -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('courts.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lapangan, fasilitas..."
                       class="flex-grow px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">

                <select name="category" class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                    <option value="all">Semua Kategori</option>
                    <option value="futsal" {{ request('category') === 'futsal' ? 'selected' : '' }}>Futsal</option>
                    <option value="badminton" {{ request('category') === 'badminton' ? 'selected' : '' }}>Badminton</option>
                </select>

                <select name="status" class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                    <option value="all">Semua Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>

                <button type="submit" class="px-5 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-xs font-semibold transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Courts Admin Table -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
            @if ($courts->isEmpty())
                <div class="p-12 text-center text-slate-400 text-sm">
                    Belum ada data lapangan. Klik tombol di atas untuk menambahkan lapangan baru!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-900/60 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700">
                                <th class="py-3.5 px-4 font-semibold">Nama Lapangan</th>
                                <th class="py-3.5 px-4 font-semibold">Kategori</th>
                                <th class="py-3.5 px-4 font-semibold">Harga Sewa</th>
                                <th class="py-3.5 px-4 font-semibold">Fasilitas</th>
                                <th class="py-3.5 px-4 font-semibold">Status</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/60">
                            @foreach ($courts as $court)
                                <tr class="hover:bg-slate-750 transition">
                                    <td class="py-3.5 px-4 font-bold text-white">
                                        {{ $court->name }}
                                        @if ($court->description)
                                            <div class="text-2xs text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $court->description }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-block px-2.5 py-0.5 text-2xs font-extrabold uppercase rounded {{ $court->category == 'futsal' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                            {{ $court->category }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-400">
                                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/jam</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-300 max-w-xs truncate" title="{{ $court->facilities }}">
                                        {{ $court->facilities ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if ($court->status === 'available')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                ● Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                ● Maintenance
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('courts.show', $court) }}" class="px-2.5 py-1.5 bg-slate-700 hover:bg-slate-650 text-slate-200 rounded-lg text-xs font-medium transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.courts.edit', $court) }}" class="px-2.5 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-lg text-xs font-medium transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.courts.destroy', $court) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lapangan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-lg text-xs font-medium transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-700">
                    {{ $courts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
