<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Halo, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola booking lapangan olahraga dan tim komunitasmu dengan mudah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 min-h-[44px] bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/25 transition">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Sewa Lapangan
                </a>
                <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-sm font-semibold rounded-xl transition">
                    + Daftarkan Tim
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm transition-colors">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Booking</span>
                <div class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $stats['total_bookings'] }}</div>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 inline-block">Sepanjang waktu</span>
            </div>

            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm transition-colors">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Menunggu Konfirmasi</span>
                <div class="text-3xl font-black text-amber-500 dark:text-amber-400 mt-1">{{ $stats['pending_bookings'] }}</div>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 inline-block">Dalam tinjauan admin</span>
            </div>

            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm transition-colors">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Booking Disetujui</span>
                <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['approved_bookings'] }}</div>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 inline-block">Siap digunakan</span>
            </div>

            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm transition-colors">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tim Komunitas Saya</span>
                <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $stats['my_teams'] }}</div>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 inline-block">Tim terdaftar</span>
            </div>
        </div>

        <!-- Highlight: Jadwal Terdekat -->
        @if ($nextBooking)
            <div class="bg-gradient-to-r from-emerald-50 via-white to-teal-50/50 dark:from-emerald-950/60 dark:to-slate-800/80 border border-emerald-200 dark:border-emerald-500/30 rounded-2xl p-6 shadow-md transition-colors relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl"></div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 mb-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 me-2 animate-ping"></span>
                            Jadwal Main Terdekat Anda!
                        </span>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $nextBooking->court->name }}</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-sm mt-1">
                            📅 {{ \Carbon\Carbon::parse($nextBooking->booking_date)->translatedFormat('l, d F Y') }} • 
                            ⏰ {{ substr($nextBooking->start_time, 0, 5) }} - {{ substr($nextBooking->end_time, 0, 5) }} WIB
                        </p>
                        @if ($nextBooking->team)
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                                Bermain bersama Tim: <strong>{{ $nextBooking->team->name }}</strong>
                            </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('bookings.show', $nextBooking) }}" class="px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-lg shadow-emerald-600/30 transition">
                            Lihat Tiket Booking
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- 5 Booking Terakhir -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden transition-colors">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Riwayat Booking Terbaru
                    </h3>
                    <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($recentBookings->isEmpty())
                    <div class="p-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                        Belum ada riwayat booking. Yuk sewa lapangan pertamamu sekarang!
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @foreach ($recentBookings as $b)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-750 transition">
                                <div>
                                    <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ $b->court->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d M Y') }} • {{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB
                                    </div>
                                    @if ($b->team)
                                        <div class="text-2xs text-emerald-600 dark:text-emerald-400 font-medium">Tim: {{ $b->team->name }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if ($b->status === 'approved')
                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30">
                                            Disetujui
                                        </span>
                                    @elseif ($b->status === 'pending')
                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/30">
                                            Menunggu
                                        </span>
                                    @elseif ($b->status === 'rejected')
                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 border border-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:border-rose-500/30">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">
                                            Dibatalkan
                                        </span>
                                    @endif
                                    <div class="mt-1">
                                        <a href="{{ route('bookings.show', $b) }}" class="text-xs text-slate-500 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium">
                                            Detail &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tim Komunitas Saya Card -->
            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between transition-colors">
                <div>
                    <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Tim / Komunitas Saya
                        </h3>
                        <a href="{{ route('teams.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Kelola &rarr;
                        </a>
                    </div>

                    @if ($myTeams->isEmpty())
                        <div class="p-6 text-center text-slate-500 dark:text-slate-400 text-sm">
                            <p>Kamu belum memiliki tim terdaftar.</p>
                            <a href="{{ route('teams.create') }}" class="mt-3 inline-block px-3.5 py-2 text-xs bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold transition">
                                + Daftarkan Tim Baru
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-700/60 p-2">
                            @foreach ($myTeams as $t)
                                <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-750 rounded-xl transition flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $t->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">Kapten: {{ $t->captain_name }} • {{ $t->members_count }} pemain</div>
                                    </div>
                                    <a href="{{ route('teams.show', $t) }}" class="text-xs text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 border border-slate-200 dark:border-slate-600 transition">
                                        Lihat
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-850 border-t border-slate-100 dark:border-slate-700/80 text-center transition-colors">
                    <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 inline-flex items-center gap-1">
                        Eksplorasi Katalog Lapangan SportCourt &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
