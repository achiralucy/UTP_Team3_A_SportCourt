<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Tiket Reservasi Lapangan
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Detail bukti booking lapangan SportCourt
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">
                    🖨️ Cetak Tiket
                </button>
                <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">
                    &larr; Riwayat Booking
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- E-Ticket Card -->
        <div class="bg-slate-800 border border-slate-700 rounded-3xl overflow-hidden shadow-2xl relative">
            <!-- Top Banner -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 p-6 text-slate-950 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-950/70">SportCourt E-Ticket</span>
                    <h3 class="text-2xl font-black tracking-tight text-white mt-0.5">{{ $booking->court->name }}</h3>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-2xs font-bold uppercase bg-slate-950/20 text-white">
                        Olahraga {{ $booking->court->category }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-xs font-mono text-emerald-950/80">KODE TIKET</span>
                    <div class="text-lg font-mono font-black text-white">#SC-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <!-- Perforated Cut Visual -->
            <div class="relative bg-slate-800 py-2">
                <div class="border-b-2 border-dashed border-slate-700"></div>
                <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-900 rounded-full"></div>
                <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-900 rounded-full"></div>
            </div>

            <!-- Ticket Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center justify-between p-3.5 bg-slate-900/80 rounded-2xl border border-slate-750">
                    <span class="text-xs text-slate-400 font-medium">Status Pemesanan:</span>
                    <div>
                        @if ($booking->status === 'approved')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                ✓ Disetujui (Siap Main)
                            </span>
                        @elseif ($booking->status === 'pending')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse">
                                ⏳ Menunggu Konfirmasi Admin
                            </span>
                        @elseif ($booking->status === 'rejected')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                ✕ Ditolak
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-700 text-slate-400">
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Grid Info -->
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500 uppercase tracking-wider text-2xs">Tanggal Main</span>
                        <div class="text-sm font-bold text-white mt-1">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</div>
                    </div>

                    <div>
                        <span class="text-slate-500 uppercase tracking-wider text-2xs">Jam Main</span>
                        <div class="text-sm font-bold font-mono text-emerald-400 mt-1">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-500 uppercase tracking-wider text-2xs">Nama Pemesan</span>
                        <div class="text-sm font-semibold text-slate-200 mt-1">{{ $booking->user->name }}</div>
                        <div class="text-slate-400 text-2xs mt-0.5">{{ $booking->user->email }}</div>
                    </div>

                    <div>
                        <span class="text-slate-500 uppercase tracking-wider text-2xs">Tim / Komunitas</span>
                        @if ($booking->team)
                            <div class="text-sm font-bold text-indigo-400 mt-1">{{ $booking->team->name }}</div>
                            <div class="text-slate-400 text-2xs mt-0.5">Kapten: {{ $booking->team->captain_name }}</div>
                        @else
                            <div class="text-sm text-slate-400 italic mt-1">Perorangan</div>
                        @endif
                    </div>
                </div>

                @if ($booking->admin_notes)
                    <div class="p-4 bg-slate-900/60 rounded-2xl border border-slate-700/80">
                        <span class="text-2xs font-bold uppercase tracking-wider text-slate-400">Pesan dari Pengelola SportCourt:</span>
                        <p class="text-xs text-slate-300 mt-1 italic leading-relaxed">
                            "{{ $booking->admin_notes }}"
                        </p>
                    </div>
                @endif

                <!-- Total Harga -->
                <div class="pt-4 border-t border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-2xs text-slate-400 uppercase">Tarif Sewa</span>
                        <div class="text-xs text-slate-300">Rp {{ number_format($booking->court->price_per_hour, 0, ',', '.') }}/jam</div>
                    </div>
                    <div class="text-right">
                        <span class="text-2xs text-slate-400 uppercase">Total Tagihan</span>
                        <div class="text-2xl font-black text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="pt-4 border-t border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('bookings.index') }}" class="w-full sm:w-auto px-4 py-2 bg-slate-700 hover:bg-slate-650 text-slate-200 rounded-xl text-xs font-semibold text-center transition">
                        Kembali
                    </a>

                    <div class="w-full sm:w-auto flex items-center justify-end gap-2">
                        @if ($booking->status === 'pending')
                            <a href="{{ route('bookings.edit', $booking) }}" class="flex-1 sm:flex-none px-4 py-2 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 rounded-xl text-xs font-semibold text-center transition">
                                Ubah Jadwal
                            </a>

                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-semibold transition">
                                    Batalkan Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
