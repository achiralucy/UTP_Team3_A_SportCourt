<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Booking Lapangan Saya
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Daftar reservasi jadwal main futsal & badminton milik Anda
                </p>
            </div>
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2.5 min-h-[44px] bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/25 transition">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Sewa Lapangan Baru
            </a>
        </div>
    </x-slot>

    <div x-data="{ cancelModalOpen: false, cancelAction: '', cancelBookingTitle: '' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-4">
            <a href="{{ route('bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
               class="px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 {{ !request('status') || request('status') === 'all' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
                <span>Semua</span>
                <span class="px-1.5 py-0.5 rounded-full text-2xs bg-slate-100 dark:bg-slate-900/60 text-slate-700 dark:text-slate-200">{{ $stats['all'] }}</span>
            </a>
            <a href="{{ route('bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
               class="px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'bg-white dark:bg-slate-800 text-amber-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
                <span>⏳ Menunggu</span>
                <span class="px-1.5 py-0.5 rounded-full text-2xs bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-200">{{ $stats['pending'] }}</span>
            </a>
            <a href="{{ route('bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}"
               class="px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
                <span>✓ Disetujui</span>
                <span class="px-1.5 py-0.5 rounded-full text-2xs bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-200">{{ $stats['approved'] }}</span>
            </a>
            <a href="{{ route('bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'rejected'])) }}"
               class="px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
                <span>✕ Ditolak</span>
                <span class="px-1.5 py-0.5 rounded-full text-2xs bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-200">{{ $stats['rejected'] }}</span>
            </a>
            <a href="{{ route('bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'cancelled'])) }}"
               class="px-3.5 py-2 min-h-[44px] rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 {{ request('status') === 'cancelled' ? 'bg-slate-700 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
                <span>Dibatalkan</span>
                <span class="px-1.5 py-0.5 rounded-full text-2xs bg-slate-100 dark:bg-slate-900/60 text-slate-600 dark:text-slate-400">{{ $stats['cancelled'] }}</span>
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 shadow-sm transition-colors">
            <form method="GET" action="{{ route('bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari lapangan atau tim..."
                           class="w-full px-3.5 py-2 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <select name="court_id" class="w-full px-3.5 py-2 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-emerald-500">
                        <option value="">-- Semua Lapangan --</option>
                        @foreach ($courts as $c)
                            <option value="{{ $c->id }}" {{ request('court_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ strtoupper($c->category) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="w-full px-3.5 py-2 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-emerald-500">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 min-h-[44px] bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold transition">
                        Terapkan Filter
                    </button>
                    @if (request()->hasAny(['search', 'court_id', 'date', 'status']))
                        <a href="{{ route('bookings.index') }}" class="px-3 py-2 min-h-[44px] inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-medium transition" title="Reset filter">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($bookings->isEmpty())
            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-12 text-center transition-colors">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tidak Ada Data Booking Lapangan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto mt-1 mb-6">
                    Tidak ditemukan data booking dengan filter yang dipilih. Silakan ubah filter atau lakukan pemesanan lapangan baru.
                </p>
                <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-5 py-2.5 min-h-[44px] bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition">
                    Sewa Lapangan Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($bookings as $b)
                    @php
                        $bookingDateTime = \Carbon\Carbon::parse($b->booking_date->toDateString() . ' ' . $b->end_time);
                        $isPast = $bookingDateTime->isPast();
                        $isToday = $b->booking_date->isToday();
                        $start = \Carbon\Carbon::createFromFormat('H:i', substr($b->start_time, 0, 5));
                        $end = \Carbon\Carbon::createFromFormat('H:i', substr($b->end_time, 0, 5));
                        $durationHours = max(1, (int) ceil($start->floatDiffInHours($end)));
                    @endphp

                    <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm hover:border-emerald-500/40 dark:hover:border-slate-600 transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 text-2xs font-bold uppercase rounded {{ $b->court->category == 'futsal' ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/20 dark:text-blue-300 dark:border-blue-500/30' : 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' }}">
                                    {{ $b->court->category }}
                                </span>

                                <div class="flex items-center gap-1.5">
                                    @if ($isToday && $b->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-2xs font-extrabold bg-teal-100 text-teal-800 border border-teal-200 dark:bg-teal-500/20 dark:text-teal-300 dark:border-teal-500/30 animate-pulse">
                                            HARI INI
                                        </span>
                                    @elseif ($isPast && $b->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-2xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">
                                            SELESAI
                                        </span>
                                    @endif

                                    @if ($b->status === 'approved')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30">
                                            ✓ Disetujui
                                        </span>
                                    @elseif ($b->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/30">
                                            ⏳ Cek Lapangan
                                        </span>
                                    @elseif ($b->status === 'rejected')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:border-rose-500/30">
                                            ✕ Ditolak (Refund)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                                {{ $b->court->name }}
                            </h3>

                            <div class="mt-3 space-y-1.5 text-xs">
                                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">{{ $b->booking_date->translatedFormat('l, d F Y') }}</span>
                                </div>

                                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-mono">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</span>
                                    <span class="text-3xs px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400">({{ $durationHours }} Jam)</span>
                                </div>

                                @if ($b->team)
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="truncate">Tim: <strong class="text-slate-900 dark:text-white">{{ $b->team->name }}</strong></span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                <span class="text-2xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Tarif</span>
                                <span class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2">
                            <a href="{{ route('bookings.show', $b) }}" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3.5 py-2 min-h-[44px] inline-flex items-center rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 border border-slate-200 dark:border-slate-600 transition">
                                Detail Tiket
                            </a>

                            <div class="flex items-center gap-1.5">
                                @if ($b->status === 'pending' && !$isPast)
                                    <a href="{{ route('bookings.edit', $b) }}" class="text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline px-3 py-2 min-h-[44px] inline-flex items-center rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 transition">
                                        Ubah Jadwal
                                    </a>
                                @endif

                                @if (in_array($b->status, ['pending', 'approved']) && !$isPast)
                                    <button type="button"
                                            @click="cancelModalOpen = true; cancelAction = '{{ route('bookings.cancel', $b) }}'; cancelBookingTitle = '#{{ $b->id }} - {{ $b->court->name }}';"
                                            class="text-xs font-medium text-rose-600 dark:text-rose-400 hover:underline px-3 py-2 min-h-[44px] inline-flex items-center rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 transition cursor-pointer">
                                        Batalkan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        @endif

        <!-- Cancellation Modal Dialog (Dual Mode) -->
        <div x-show="cancelModalOpen"
             x-cloak
             class="fixed inset-0 z-[60] overflow-y-auto bg-slate-950/70 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="cancelModalOpen = false" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl transition-colors">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="text-rose-500">⚠️</span> Batalkan Booking
                    </h3>
                    <button @click="cancelModalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-base w-8 h-8 flex items-center justify-center rounded-lg">&times;</button>
                </div>

                <form :action="cancelAction" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Apakah Anda yakin ingin membatalkan booking <strong class="text-slate-900 dark:text-white" x-text="cancelBookingTitle"></strong>? Jadwal yang dibatalkan akan dibebaskan kembali untuk penyewa lain.
                    </p>

                    <div>
                        <label for="cancelReason" class="block text-2xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Alasan Pembatalan (Opsional):
                        </label>
                        <textarea name="reason" id="cancelReason" rows="2"
                                  placeholder="Contoh: Ada acara keluarga mendadak, tim berhalangan hadir..."
                                  class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-rose-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="cancelModalOpen = false" class="px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-200 text-xs font-medium rounded-xl transition cursor-pointer">
                            Tutup
                        </button>
                        <button type="submit" class="px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-rose-600/30 transition cursor-pointer">
                            Konfirmasi Pembatalan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
