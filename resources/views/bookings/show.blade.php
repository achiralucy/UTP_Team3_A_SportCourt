<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between no-print">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Tiket Reservasi Lapangan
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Detail bukti booking lapangan SportCourt
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 min-h-[44px] bg-slate-800 rounded-xl border border-slate-700 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Tiket
                </button>
                <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 min-h-[44px] inline-flex items-center bg-slate-800 rounded-xl border border-slate-700 transition">
                    &larr; Riwayat Booking
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
            cancelModalOpen: false, 
            paymentModalOpen: false, 
            paymentSimulated: false,
            timerMin: 14,
            timerSec: 59,
            timerInterval: null,
            startTimer() {
                if (!this.timerInterval) {
                    this.timerInterval = setInterval(() => {
                        if (this.timerSec > 0) {
                            this.timerSec--;
                        } else if (this.timerMin > 0) {
                            this.timerMin--;
                            this.timerSec = 59;
                        } else {
                            clearInterval(this.timerInterval);
                        }
                    }, 1000);
                }
            }
         }" 
         class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Alert for Simulated Payment -->
        <div x-show="paymentSimulated" x-cloak class="mb-4 p-4 bg-emerald-500/15 border border-emerald-500/30 rounded-2xl flex items-center justify-between gap-3 text-emerald-300 shadow-lg">
            <div class="flex items-center gap-2.5 text-xs font-bold">
                <span class="w-6 h-6 rounded-full bg-emerald-500 text-slate-950 flex items-center justify-center text-xs shrink-0">✓</span>
                <span>Simulasi Berhasil: Tagihan sebesar Rp {{ number_format($booking->total_price, 0, ',', '.') }} telah terverifikasi via QRIS!</span>
            </div>
            <button @click="paymentSimulated = false" class="text-emerald-400 hover:text-white text-base leading-none px-2">&times;</button>
        </div>

        <div id="printableTicket" class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden shadow-2xl relative">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-400 p-6 text-slate-950 flex items-center justify-between">
                <div>
                    <span class="text-2xs font-bold uppercase tracking-widest text-emerald-950/80">SportCourt Official E-Ticket</span>
                    <h3 class="text-2xl font-black tracking-tight text-slate-950 mt-0.5">{{ $booking->court->name }}</h3>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-2xs font-bold uppercase bg-slate-950/15 text-slate-950 border border-slate-950/20">
                        Olahraga {{ $booking->court->category }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-2xs font-mono text-emerald-950/80 uppercase tracking-wider">KODE TIKET</span>
                    <div class="text-lg font-mono font-black text-slate-950 break-all">#SC-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <div class="relative bg-slate-800 py-2.5 no-print">
                <div class="border-b-2 border-dashed border-slate-700"></div>
                <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-900 rounded-full border-r border-slate-700 pointer-events-none"></div>
                <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-900 rounded-full border-l border-slate-700 pointer-events-none"></div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <!-- Dual Status Box -->
                <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-750 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-xs text-slate-400 font-medium">Status Jadwal Lapangan:</span>
                        <div>
                            @if ($booking->status === 'approved')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    ✓ Jadwal Terkonfirmasi
                                </span>
                            @elseif ($booking->status === 'pending')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse">
                                    ⏳ Menunggu Konfirmasi Jadwal
                                </span>
                            @elseif ($booking->status === 'rejected')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                    ✕ Jadwal Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-700 text-slate-400">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-2.5 border-t border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-xs text-slate-400 font-medium">Status Pembayaran & Tagihan:</span>
                        <div>
                            <template x-if="paymentSimulated">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 inline-flex items-center gap-1.5">
                                    <span>✓ Lunas</span>
                                    <span class="text-3xs text-emerald-400/80 font-normal">(Simulasi QRIS)</span>
                                </span>
                            </template>
                            <template x-if="!paymentSimulated">
                                <div>
                                    @if ($booking->status === 'approved')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-sky-500/20 text-sky-300 border border-sky-500/30">
                                            Menunggu Check-In & Pelunasan di Kasir
                                        </span>
                                    @elseif ($booking->status === 'pending')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                            Menunggu Konfirmasi Jadwal
                                        </span>
                                    @elseif ($booking->status === 'rejected' || $booking->status === 'cancelled')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-500">
                                            Tagihan Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase tracking-wider text-2xs font-semibold">Tanggal Main</span>
                        <div class="text-sm font-bold text-white mt-1">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase tracking-wider text-2xs font-semibold">Jam & Durasi</span>
                        <div class="text-sm font-bold font-mono text-emerald-400 mt-1">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </div>
                        <div class="text-slate-400 text-2xs mt-0.5 font-medium">Durasi: {{ $durationHours }} Jam</div>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase tracking-wider text-2xs font-semibold">Nama Pemesan</span>
                        <div class="text-sm font-semibold text-slate-200 mt-1">{{ $booking->user->name }}</div>
                        <div class="text-slate-400 text-2xs mt-0.5">{{ $booking->user->email }} • {{ $booking->user->phone ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase tracking-wider text-2xs font-semibold">Tim / Komunitas</span>
                        @if ($booking->team)
                            <div class="text-sm font-bold text-indigo-400 mt-1">{{ $booking->team->name }}</div>
                            <div class="text-slate-400 text-2xs mt-0.5">Kapten: {{ $booking->team->captain_name }} ({{ $booking->team->captain_phone }})</div>
                        @else
                            <div class="text-sm text-slate-400 italic mt-1">Perorangan (Tanpa Tim)</div>
                        @endif
                    </div>
                </div>

                @if ($booking->court->facilities)
                    <div class="pt-2">
                        <span class="text-2xs font-bold uppercase tracking-wider text-slate-300">Fasilitas Termasuk:</span>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            @foreach (explode(',', $booking->court->facilities) as $f)
                                <span class="px-2 py-0.5 bg-slate-900 border border-slate-700/80 rounded-lg text-2xs text-slate-300">
                                    ✓ {{ trim($f) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($booking->admin_notes)
                    <div class="p-4 bg-slate-900/60 rounded-2xl border border-slate-700/80">
                        <span class="text-2xs font-bold uppercase tracking-wider text-slate-300">Catatan & Riwayat:</span>
                        <p class="text-xs text-slate-300 mt-1 italic leading-relaxed whitespace-pre-line">
                            "{{ $booking->admin_notes }}"
                        </p>
                    </div>
                @endif

                <!-- Check-in & QR Code Section -->
                <div class="p-4 bg-slate-900/90 rounded-2xl border border-slate-700/80 flex flex-col sm:flex-row items-center gap-4">
                    <div class="w-24 h-24 shrink-0 p-2 bg-white rounded-xl shadow-lg border border-slate-200 flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-950" viewBox="0 0 100 100" fill="currentColor">
                            <rect width="100" height="100" fill="white" />
                            <path fill="#020617" d="M10 10h24v24H10zM14 14h16v16H14zM18 18h8v8h-8zM66 10h24v24H66zM70 14h16v16H70zM74 18h8v8h-8zM10 66h24v24H10zM14 70h16v16H14zM18 74h8v8h-8zM42 10h8v8h-8zM50 18h8v8h-8zM42 26h8v8h-8zM58 10h8v8h-8zM10 42h8v8h-8zM18 50h8v8h-8zM26 42h8v8h-8zM42 42h16v8H42zM50 50h8v16h-8zM42 58h8v8h-8zM66 42h8v8h-8zM82 42h8v8h-8zM74 50h16v8H74zM66 58h8v8h-8zM82 58h8v16h-8zM42 74h8v16h-8zM50 82h16v8H50zM66 74h8v8h-8zM74 82h8v8h-8zM82 74h8v8h-8zM66 90h24v-4H66z"/>
                        </svg>
                    </div>
                    <div class="space-y-1 text-center sm:text-left flex-1 min-w-0">
                        <div class="flex items-center justify-center sm:justify-start gap-1.5 text-emerald-400 font-bold text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Petunjuk Check-In Arena
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Tunjukkan E-Tiket ini kepada petugas resepsionis/kasir SportCourt minimal <strong class="text-white font-semibold">15 menit</strong> sebelum jadwal dimulai untuk konfirmasi pemakaian lapangan.
                        </p>
                        <div class="text-2xs text-slate-400 break-all">
                            Validasi Tiket: <span class="font-mono text-emerald-300 font-semibold break-all">SC-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}-{{ substr(md5($booking->id . $booking->created_at), 0, 6) }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-2xs text-slate-400 uppercase font-semibold">Rincian Tarif</span>
                        <div class="text-xs text-slate-300 font-medium">Rp {{ number_format($booking->court->price_per_hour, 0, ',', '.') }}/jam &times; {{ $durationHours }} jam</div>
                    </div>
                    <div class="text-right">
                        <span class="text-2xs text-slate-400 uppercase font-semibold">Total Tagihan</span>
                        <div class="text-2xl font-black text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Catatan Kasir & Resepsionis Venue (Terbaca di Layar & Cetak Tiket) -->
                <div class="p-3.5 bg-slate-900/60 rounded-xl border border-slate-750 text-2xs text-slate-300 space-y-1">
                    <span class="font-bold uppercase tracking-wider text-slate-200 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Catatan Kasir & Resepsionis:
                    </span>
                    <p class="leading-relaxed">
                        Tunjukkan E-Tiket ini kepada petugas kasir SportCourt Arena saat tiba di lokasi untuk verifikasi check-in dan pelunasan transaksi (Tunai, Debit EDC, atau QRIS) sebelum memasuki lapangan.
                    </p>
                </div>

                <!-- Interactive Payment Action Box (No Print) -->
                @if (in_array($booking->status, ['pending', 'approved']))
                    <div class="p-4 bg-emerald-950/30 border border-emerald-500/20 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-3 no-print">
                        <div class="space-y-0.5 text-center sm:text-left">
                            <div class="text-xs font-bold text-white flex items-center justify-center sm:justify-start gap-1.5">
                                <span class="text-emerald-400">💳</span> Pembayaran Online & Kasir
                            </div>
                            <p class="text-2xs text-slate-300">
                                Selesaikan pembayaran instan melalui QRIS atau bayar langsung di kasir arena.
                            </p>
                        </div>
                        <button type="button"
                                @click="paymentModalOpen = true; startTimer()"
                                class="w-full sm:w-auto px-4 py-2.5 min-h-[44px] bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            Buka QRIS & Petunjuk Bayar
                        </button>
                    </div>
                @endif

                <div class="pt-4 border-t border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 no-print">
                    <a href="{{ route('bookings.index') }}" class="w-full sm:w-auto px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-slate-700 hover:bg-slate-650 text-slate-200 rounded-xl text-xs font-semibold text-center transition">
                        Kembali
                    </a>

                    <div class="w-full sm:w-auto flex items-center justify-end gap-2">
                        @if ($booking->status === 'pending')
                            <a href="{{ route('bookings.edit', $booking) }}" class="flex-1 sm:flex-none px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 rounded-xl text-xs font-semibold text-center transition">
                                Ubah Jadwal
                            </a>
                        @endif

                        @if (in_array($booking->status, ['pending', 'approved']) && !$isPast)
                            <button type="button"
                                    @click="cancelModalOpen = true"
                                    class="w-full sm:w-auto px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-semibold transition">
                                Batalkan Booking
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div x-show="cancelModalOpen"
             x-cloak
             class="fixed inset-0 z-[60] overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 no-print">
            <div @click.away="cancelModalOpen = false" class="bg-slate-800 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="text-rose-400">⚠️</span> Konfirmasi Pembatalan
                    </h3>
                    <button @click="cancelModalOpen = false" class="text-slate-400 hover:text-white text-sm w-8 h-8 flex items-center justify-center rounded-lg">&times;</button>
                </div>

                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Apakah Anda yakin ingin membatalkan booking tiket <strong>#SC-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</strong>? Slot waktu lapangan ini akan dibebaskan kembali.
                    </p>

                    <div>
                        <label for="modalReason" class="block text-2xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Alasan Pembatalan (Opsional):
                        </label>
                        <textarea name="reason" id="modalReason" rows="2"
                                  placeholder="Contoh: Ada keperluan mendadak, tim berhalangan..."
                                  class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-rose-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="cancelModalOpen = false" class="px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-slate-700 hover:bg-slate-650 text-slate-200 text-xs font-medium rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-rose-600/30 transition">
                            Ya, Batalkan Booking Ini
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Simulasi QRIS & Petunjuk Pembayaran -->
        <div x-show="paymentModalOpen"
             x-cloak
             class="fixed inset-0 z-[60] overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 no-print">
            <div @click.away="paymentModalOpen = false" class="bg-slate-800 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
                <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-black text-2xs tracking-wider">QRIS</span>
                        <h3 class="text-sm font-bold text-white">Pembayaran SportCourt Arena</h3>
                    </div>
                    <button @click="paymentModalOpen = false" class="text-slate-400 hover:text-white text-base w-8 h-8 flex items-center justify-center rounded-lg">&times;</button>
                </div>

                <div class="mt-4 space-y-4">
                    <!-- Merchant info -->
                    <div class="text-center">
                        <div class="text-2xs uppercase tracking-wider text-slate-400 font-semibold">Merchant Resmi</div>
                        <div class="text-base font-extrabold text-white mt-0.5">SPORTSCOURT ARENA INDONESIA</div>
                        <div class="text-3xs font-mono text-slate-400 mt-0.5">NMID: ID1020304050607 • A01</div>
                    </div>

                    <!-- Nominal Tagihan -->
                    <div class="p-3.5 bg-slate-900 rounded-xl border border-slate-750 text-center">
                        <span class="text-3xs uppercase tracking-wider text-slate-400 font-bold">Total Tagihan Booking</span>
                        <div class="text-2xl font-black text-emerald-400 mt-0.5">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                        <div class="text-3xs text-slate-400 mt-1">
                            Sisa batas waktu: <span class="font-mono text-amber-300 font-bold" x-text="String(timerMin).padStart(2, '0') + ':' + String(timerSec).padStart(2, '0')"></span>
                        </div>
                    </div>

                    <!-- QR Code Mockup -->
                    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-inner border border-slate-200">
                        <div class="text-xs font-black tracking-widest text-slate-950 mb-2">QRIS STANDAR NASIONAL</div>
                        <svg class="w-44 h-44 text-slate-950" viewBox="0 0 100 100" fill="currentColor">
                            <rect width="100" height="100" fill="white" />
                            <path fill="#020617" d="M10 10h24v24H10zM14 14h16v16H14zM18 18h8v8h-8zM66 10h24v24H66zM70 14h16v16H70zM74 18h8v8h-8zM10 66h24v24H10zM14 70h16v16H14zM18 74h8v8h-8zM42 10h8v8h-8zM50 18h8v8h-8zM42 26h8v8h-8zM58 10h8v8h-8zM10 42h8v8h-8zM18 50h8v8h-8zM26 42h8v8h-8zM42 42h16v8H42zM50 50h8v16h-8zM42 58h8v8h-8zM66 42h8v8h-8zM82 42h8v8h-8zM74 50h16v8H74zM66 58h8v8h-8zM82 58h8v16h-8zM42 74h8v16h-8zM50 82h16v8H50zM66 74h8v8h-8zM74 82h8v8h-8zM82 74h8v8h-8zM66 90h24v-4H66z"/>
                        </svg>
                        <div class="text-3xs text-slate-600 mt-2 text-center font-medium">
                            Scan via BCA, Mandiri Livin', GoPay, OVO, Dana, ShopeePay
                        </div>
                    </div>

                    <!-- Payment Simulation Action -->
                    <div class="space-y-2 pt-2">
                        <button type="button"
                                @click="paymentSimulated = true; paymentModalOpen = false"
                                class="w-full py-3 min-h-[44px] bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/25 transition text-center flex items-center justify-center gap-2">
                            <span>✓</span> Simulasikan Pembayaran Berhasil
                        </button>
                        <p class="text-3xs text-slate-400 text-center leading-relaxed">
                            Atau Anda juga dapat melakukan pelunasan langsung secara tunai/debit di kasir arena saat kedatangan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            nav, header, footer, .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            #printableTicket {
                border: 2px solid #000000 !important;
                box-shadow: none !important;
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            #printableTicket * {
                color: #000000 !important;
                background: transparent !important;
                border-color: #cccccc !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
        }
    </style>
</x-app-layout>
