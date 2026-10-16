<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Dashboard Administrator
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Ringkasan performa dan pemantauan sistem reservasi SportCourt
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Semua Booking
                </a>
                <a href="{{ route('admin.courts.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-sm font-semibold rounded-xl transition">
                    + Tambah Lapangan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Statistik Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Lapangan -->
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Lapangan</span>
                        <div class="text-3xl font-black text-white mt-1">{{ $stats['total_courts'] }}</div>
                        <span class="text-xs text-emerald-400 mt-1 inline-block">{{ $stats['available_courts'] }} siap digunakan</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Booking Pending (Membutuhkan Approval) -->
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Persetujuan</span>
                        <div class="text-3xl font-black text-amber-400 mt-1">{{ $stats['pending_bookings'] }}</div>
                        <span class="text-xs text-slate-400 mt-1 inline-block">Permintaan baru</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Booking Disetujui -->
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Booking Disetujui</span>
                        <div class="text-3xl font-black text-emerald-400 mt-1">{{ $stats['approved_bookings'] }}</div>
                        <span class="text-xs text-slate-400 mt-1 inline-block">Dari total {{ $stats['total_bookings'] }} booking</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Komunitas / Tim -->
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Komunitas / Tim</span>
                        <div class="text-3xl font-black text-indigo-400 mt-1">{{ $stats['total_teams'] }}</div>
                        <span class="text-xs text-slate-400 mt-1 inline-block">{{ $stats['total_penyewa'] }} penyewa terdaftar</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Booking Menunggu Persetujuan (Quick Approval) -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-700/80 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Booking Menunggu Persetujuan (Pending)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tinjau dan setujui atau tolak permohonan booking lapangan terbaru</p>
                </div>
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                    Lihat Semua Pending &rarr;
                </a>
            </div>

            @if ($pendingBookings->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-300">Semua permohonan booking sudah diproses!</p>
                    <p class="text-xs text-slate-500 mt-1">Tidak ada permohonan booking yang menunggu saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-900/60 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700">
                                <th class="py-3.5 px-4 font-semibold">Penyewa / Tim</th>
                                <th class="py-3.5 px-4 font-semibold">Lapangan</th>
                                <th class="py-3.5 px-4 font-semibold">Tanggal & Jam</th>
                                <th class="py-3.5 px-4 font-semibold">Total Biaya</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/60">
                            @foreach ($pendingBookings as $b)
                                <tr class="hover:bg-slate-750 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="font-medium text-white">{{ $b->user->name }}</div>
                                        <div class="text-xs text-slate-400">
                                            @if ($b->team)
                                                Tim: <span class="text-emerald-400 font-semibold">{{ $b->team->name }}</span>
                                            @else
                                                <span class="italic text-slate-500">Perorangan</span>
                                            @endif
                                            • {{ $b->user->phone ?? 'Tanpa kontak' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-medium text-slate-200">{{ $b->court->name }}</div>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 text-2xs font-semibold rounded uppercase {{ $b->court->category == 'futsal' ? 'bg-blue-500/20 text-blue-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                                            {{ $b->court->category }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-300">
                                        <div class="font-semibold text-white">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d M Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-400">
                                        Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-2">
                                        <form method="POST" action="{{ route('admin.bookings.approve', $b) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold shadow transition">
                                                Setujui
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.bookings.show', $b) }}" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg text-xs font-medium transition">
                                            Detail / Tolak
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Section: Jadwal Lapangan Mendatang yang Disetujui -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-700/80 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Jadwal Mendatang (Approved)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Jadwal penggunaan lapangan yang sudah terkonfirmasi</p>
                </div>
            </div>

            @if ($upcomingBookings->isEmpty())
                <div class="p-8 text-center text-slate-400 text-sm">
                    Belum ada jadwal penggunaan lapangan mendatang.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-900/60 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700">
                                <th class="py-3 px-4 font-semibold">Tanggal & Waktu</th>
                                <th class="py-3 px-4 font-semibold">Lapangan</th>
                                <th class="py-3 px-4 font-semibold">Penyewa</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/60">
                            @foreach ($upcomingBookings as $ub)
                                <tr class="hover:bg-slate-750 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-white">{{ \Carbon\Carbon::parse($ub->booking_date)->translatedFormat('d M Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ substr($ub->start_time, 0, 5) }} - {{ substr($ub->end_time, 0, 5) }} WIB</div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-200">
                                        {{ $ub->court->name }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-slate-200">{{ $ub->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $ub->team ? $ub->team->name : 'Perorangan' }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            Disetujui
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $ub) }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-medium">
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
