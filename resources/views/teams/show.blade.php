<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Detail Tim: {{ $team->name }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Informasi komunitas dan riwayat pemesanan lapangan oleh tim ini
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teams.edit', $team) }}" class="text-xs font-semibold text-amber-700 dark:text-amber-300 hover:text-amber-800 dark:hover:text-white px-3 py-2 min-h-[44px] inline-flex items-center bg-amber-50 hover:bg-amber-100 dark:bg-amber-500/10 dark:hover:bg-amber-500/20 rounded-xl border border-amber-200 dark:border-amber-500/30 transition">
                    Edit Tim
                </a>
                <a href="{{ route('teams.index') }}" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3 py-2 min-h-[44px] inline-flex items-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Informasi Tim Card -->
        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm transition-colors">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama Tim</span>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $team->name }}</h3>
                    <span class="inline-block mt-2 px-2.5 py-0.5 text-xs font-semibold rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30">
                        {{ $team->members_count }} Anggota Aktif
                    </span>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Kapten Tim</span>
                    <div class="text-base font-bold text-slate-900 dark:text-slate-200 mt-1">{{ $team->captain_name }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        WA: <span class="text-emerald-600 dark:text-emerald-400 font-mono font-semibold">{{ $team->captain_phone }}</span>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Akun Terdaftar</span>
                    <div class="text-base font-bold text-slate-900 dark:text-slate-200 mt-1">{{ $team->user->name }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $team->user->email }}</div>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Total Pemesanan</span>
                    <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $team->bookings->count() }} kali</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Menggunakan nama tim ini</div>
                </div>
            </div>

            @if ($team->description)
                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Deskripsi Komunitas</span>
                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1 leading-relaxed">{{ $team->description }}</p>
                </div>
            @endif
        </div>

        <!-- Riwayat Booking Tim -->
        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Booking Lapangan oleh Tim Ini</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar pertandingan atau latihan yang dipesan atas nama {{ $team->name }}</p>
                </div>
                <a href="{{ route('bookings.create') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                    + Booking Atas Nama Tim Ini
                </a>
            </div>

            @if ($team->bookings->isEmpty())
                <div class="p-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                    Tim ini belum pernah memesan lapangan. Pilih nama tim ini saat membuat booking baru!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                                <th class="py-3 px-4 font-semibold">Lapangan</th>
                                <th class="py-3 px-4 font-semibold">Tanggal Sewa</th>
                                <th class="py-3 px-4 font-semibold">Waktu Main</th>
                                <th class="py-3 px-4 font-semibold">Total Biaya</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                            @foreach ($team->bookings as $b)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 transition">
                                    <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">
                                        {{ $b->court->name }}
                                        <span class="block text-2xs text-slate-500 dark:text-slate-400 font-normal uppercase">{{ $b->court->category }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700 dark:text-slate-200">
                                        {{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 dark:text-slate-300 font-mono text-xs">
                                        {{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB
                                    </td>
                                    <td class="py-3 px-4 font-bold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($b->status === 'approved')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                                                Disetujui
                                            </span>
                                        @elseif ($b->status === 'pending')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30">
                                                Menunggu
                                            </span>
                                        @elseif ($b->status === 'rejected')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('bookings.show', $b) }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-semibold">
                                            Lihat &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
