<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">Kelola Data Lapangan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tambah, ubah, dan atur status ketersediaan lapangan</p>
            </div>
            <a href="{{ route('admin.courts.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/25 transition">
                + Tambah Lapangan
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach ([
                ['Total Lapangan', $stats['total'], 'text-slate-900 dark:text-white'],
                ['Tersedia', $stats['available'], 'text-emerald-600 dark:text-emerald-400'],
                ['Maintenance', $stats['maintenance'], 'text-rose-600 dark:text-rose-400'],
                ['Futsal', $stats['futsal'], 'text-blue-600 dark:text-blue-300'],
                ['Badminton', $stats['badminton'], 'text-emerald-600 dark:text-emerald-300'],
            ] as [$title, $value, $color])
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 shadow-sm transition-colors">
                    <div class="text-2xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $title }}</div>
                    <div class="text-2xl font-black mt-1 {{ $color }}">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 shadow-sm transition-colors">
            <form method="GET" action="{{ route('courts.index') }}" class="flex flex-col lg:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, fasilitas, atau deskripsi"
                       class="flex-grow min-h-[44px] px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500">

                <select name="category" class="min-h-[44px] px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500">
                    <option value="all">Semua Jenis</option>
                    <option value="futsal" @selected(request('category') === 'futsal')>Futsal</option>
                    <option value="badminton" @selected(request('category') === 'badminton')>Badminton</option>
                </select>

                <select name="status" class="min-h-[44px] px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500">
                    <option value="all">Semua Status</option>
                    <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                    <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
                </select>

                <select name="sort" class="min-h-[44px] px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                    <option value="name" @selected(request('sort') === 'name')>Nama A-Z</option>
                    <option value="price_low" @selected(request('sort') === 'price_low')>Harga Terendah</option>
                    <option value="price_high" @selected(request('sort') === 'price_high')>Harga Tertinggi</option>
                </select>

                <button type="submit" class="min-h-[44px] px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-600/20 transition inline-flex items-center justify-center">Terapkan</button>

                @if (request()->hasAny(['search', 'category', 'status', 'sort']))
                    <a href="{{ route('courts.index') }}" class="min-h-[44px] px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl text-xs transition inline-flex items-center justify-center">Reset</a>
                @endif
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden transition-colors">
            @if ($courts->isEmpty())
                <div class="p-12 text-center text-slate-500 dark:text-slate-400 text-sm">
                    @if (request()->hasAny(['search', 'category', 'status']))
                        Tidak ada lapangan yang sesuai dengan pencarian atau filter.
                    @else
                        Belum ada data lapangan. Klik "Tambah Lapangan" untuk memulai.
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                                <th class="py-3.5 px-4 font-semibold">Lapangan</th>
                                <th class="py-3.5 px-4 font-semibold">Jenis</th>
                                <th class="py-3.5 px-4 font-semibold">Harga Sewa</th>
                                <th class="py-3.5 px-4 font-semibold">Fasilitas</th>
                                <th class="py-3.5 px-4 font-semibold">Status</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                            @foreach ($courts as $court)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-750/60 transition">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-16 h-12 rounded-lg overflow-hidden shrink-0 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                                                @if ($court->image)
                                                    <img
                                                        src="{{ asset('storage/' . $court->image) }}"
                                                        alt="Foto {{ $court->name }}"
                                                        class="w-full h-full object-cover"
                                                        loading="lazy"
                                                    >
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center {{ $court->category === 'futsal' ? 'bg-blue-50 dark:bg-blue-950/80 text-blue-500 dark:text-blue-400' : 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-500 dark:text-emerald-400' }}">
                                                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-bold text-slate-900 dark:text-white">{{ $court->name }}</div>
                                                @if ($court->description)
                                                    <div class="text-2xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-xs">{{ $court->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4"><x-court-badges :court="$court" /></td>
                                    <td class="py-3 px-4 font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-xs text-slate-500 dark:text-slate-400 font-normal">/jam</span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-slate-600 dark:text-slate-300 max-w-[14rem] truncate" title="{{ $court->facilities }}">{{ $court->facilities ?: '-' }}</td>
                                    <td class="py-3 px-4"><x-court-status :court="$court" /></td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('courts.show', $court) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-medium transition">Detail</a>
                                            <a href="{{ route('admin.courts.edit', $court) }}" class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 dark:bg-amber-500/10 dark:hover:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 rounded-lg text-xs font-medium transition">Edit</a>
                                            <form method="POST" action="{{ route('admin.courts.destroy', $court) }}" onsubmit="return confirm('Hapus lapangan {{ addslashes($court->name) }}? Tindakan ini tidak bisa dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 rounded-lg text-xs font-medium transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $courts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>