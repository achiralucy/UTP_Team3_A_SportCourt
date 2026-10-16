<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Booking Lapangan Saya
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Daftar reservasi jadwal main futsal & badminton milik Anda
                </p>
            </div>
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/25 transition">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                + Sewa Lapangan Baru
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 border-b border-slate-800 pb-3">
            <a href="{{ route('bookings.index') }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ !request('status') || request('status') === 'all' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                Semua
            </a>
            <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'bg-slate-800 text-amber-400 hover:bg-slate-700' }}">
                Menunggu Konfirmasi
            </a>
            <a href="{{ route('bookings.index', ['status' => 'approved']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-800 text-emerald-400 hover:bg-slate-700' }}">
                Disetujui
            </a>
            <a href="{{ route('bookings.index', ['status' => 'rejected']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-800 text-rose-400 hover:bg-slate-700' }}">
                Ditolak
            </a>
            <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'cancelled' ? 'bg-slate-700 text-white shadow-md' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">
                Dibatalkan
            </a>
        </div>

        @if ($bookings->isEmpty())
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">Belum Ada Booking Lapangan</h3>
                <p class="text-sm text-slate-400 max-w-sm mx-auto mt-1 mb-6">
                    Pilih lapangan favoritmu dan jadwalkan latihan atau pertandingan sekarang juga.
                </p>
                <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition">
                    Sewa Lapangan Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($bookings as $b)
                    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-sm hover:border-slate-600 transition flex flex-col justify-between">
                        <div>
                            <!-- Header Card: Category & Status -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 text-2xs font-bold uppercase rounded {{ $b->court->category == 'futsal' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                    {{ $b->court->category }}
                                </span>

                                @if ($b->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        ✓ Disetujui
                                    </span>
                                @elseif ($b->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        ⏳ Menunggu
                                    </span>
                                @elseif ($b->status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                        ✕ Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-700 text-slate-400">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>

                            <!-- Court Name -->
                            <h3 class="text-lg font-bold text-white">{{ $b->court->name }}</h3>

                            <!-- Schedule Info -->
                            <div class="space-y-1.5 text-xs text-slate-300 my-3">
                                <div class="flex items-center">
                                    <span class="text-slate-500 w-24">Tanggal:</span>
                                    <span class="font-semibold text-slate-200">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('l, d F Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-slate-500 w-24">Waktu:</span>
                                    <span class="font-mono text-emerald-400 font-semibold">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-slate-500 w-24">Tim:</span>
                                    @if ($b->team)
                                        <span class="text-indigo-400 font-medium">{{ $b->team->name }}</span>
                                    @else
                                        <span class="italic text-slate-500">Perorangan</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <span class="text-slate-500 w-24">Total Biaya:</span>
                                    <span class="font-bold text-white">Rp {{ number_format($b->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if ($b->admin_notes)
                                <div class="p-2.5 bg-slate-900/80 rounded-xl border border-slate-700 text-2xs text-slate-300 mt-2 italic">
                                    "{{ $b->admin_notes }}"
                                </div>
                            @endif
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-4 border-t border-slate-700/80 flex items-center justify-between gap-2 mt-4">
                            <a href="{{ route('bookings.show', $b) }}" class="text-xs font-semibold text-slate-200 hover:text-white px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-650 transition">
                                Lihat Tiket
                            </a>

                            <div class="flex items-center gap-2">
                                @if ($b->status === 'pending')
                                    <a href="{{ route('bookings.edit', $b) }}" class="text-xs font-medium text-amber-400 hover:text-amber-300 px-2.5 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 transition">
                                        Ubah Jadwal
                                    </a>

                                    <form method="POST" action="{{ route('bookings.cancel', $b) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');">
                                        @csrf
                                        <button type="submit" class="text-xs font-medium text-rose-400 hover:text-rose-300 px-2.5 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 transition">
                                            Batalkan
                                        </button>
                                    </form>
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
    </div>
</x-app-layout>
