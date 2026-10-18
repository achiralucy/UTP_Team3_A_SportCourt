<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Detail Transaksi Booking #{{ $booking->id }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola persetujuan, penolakan, dan catatan untuk reservasi pengguna
                </p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition">
                &larr; Kembali ke Daftar Booking
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Main Card Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status & Booking Summary -->
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm transition-colors">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <span class="text-xs text-slate-500 font-mono">KODE BOOKING</span>
                            <div class="text-xl font-black text-slate-900 dark:text-white mt-0.5">#SC-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div>
                            @if ($booking->status === 'approved')
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                                    ✓ Lapangan Siap Main
                                </span>
                            @elseif ($booking->status === 'pending')
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30">
                                    ⏳ Menunggu Kesiapan Lapangan
                                </span>
                            @elseif ($booking->status === 'rejected')
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30">
                                    ✕ Ditolak (Refund Diproses)
                                </span>
                            @else
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 text-xs">
                        <div>
                            <span class="text-slate-500">Tanggal Pengajuan:</span>
                            <div class="text-slate-700 dark:text-slate-300 font-medium mt-0.5">{{ $booking->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                        </div>
                        <div>
                            <span class="text-slate-500">Tanggal Main:</span>
                            <div class="text-slate-900 dark:text-white font-bold mt-0.5">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</div>
                        </div>
                        <div>
                            <span class="text-slate-500">Jam Sewa:</span>
                            <div class="text-emerald-600 dark:text-emerald-400 font-mono font-bold mt-0.5">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</div>
                        </div>
                        <div>
                            <span class="text-slate-500">Total Tarif & Pembayaran:</span>
                            <div class="text-emerald-600 dark:text-emerald-400 text-base font-black mt-0.5">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                            @if (in_array($booking->status, ['pending', 'approved']))
                                <span class="inline-flex items-center gap-1 text-3xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 mt-1">
                                    ✓ Lunas Terbayar Online
                                </span>
                            @elseif ($booking->status === 'rejected')
                                <span class="inline-flex items-center gap-1 text-3xs font-semibold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10 px-2 py-0.5 rounded border border-rose-200 dark:border-rose-500/20 mt-1">
                                    Dana Direfund ke Rekening
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($booking->admin_notes)
                        <div class="mt-4 p-3.5 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-700 rounded-xl">
                            <span class="text-2xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan Admin:</span>
                            <p class="text-xs text-slate-700 dark:text-slate-300 mt-1 italic leading-relaxed">
                                "{{ $booking->admin_notes }}"
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Info Lapangan -->
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm transition-colors">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Informasi Lapangan</h3>
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $booking->court->name }}</h4>
                            <span class="inline-block px-2.5 py-0.5 text-2xs font-bold uppercase rounded mt-1 {{ $booking->court->category == 'futsal' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 border border-blue-200 dark:border-blue-500/30' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30' }}">
                                Olahraga {{ $booking->court->category }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Tarif per Jam:</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($booking->court->price_per_hour, 0, ',', '.') }}/jam</div>
                        </div>
                    </div>
                    @if ($booking->court->facilities)
                        <div class="mt-3 text-xs text-slate-600 dark:text-slate-400">
                            <strong>Fasilitas:</strong> {{ $booking->court->facilities }}
                        </div>
                    @endif
                </div>

                <!-- Info Penyewa & Tim -->
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm transition-colors">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Informasi Penyewa</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-500">Nama Akun:</span>
                            <div class="text-slate-900 dark:text-slate-200 font-bold text-sm mt-0.5">{{ $booking->user->name }}</div>
                            <div class="text-slate-500 dark:text-slate-400 mt-0.5">{{ $booking->user->email }}</div>
                            <div class="text-emerald-600 dark:text-emerald-400 font-mono mt-0.5">{{ $booking->user->phone ?? 'Tidak ada nomor telp' }}</div>
                        </div>

                        <div>
                            <span class="text-slate-500">Tim / Komunitas Terkait:</span>
                            @if ($booking->team)
                                <div class="text-indigo-600 dark:text-indigo-400 font-bold text-sm mt-0.5">{{ $booking->team->name }}</div>
                                <div class="text-slate-500 dark:text-slate-400 mt-0.5">Kapten: {{ $booking->team->captain_name }} (WA: {{ $booking->team->captain_phone }})</div>
                                <div class="text-slate-500 mt-0.5">{{ $booking->team->members_count }} anggota tim</div>
                            @else
                                <div class="text-slate-500 dark:text-slate-400 italic mt-0.5">Dipesan perorangan (tidak menggunakan tim)</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Actions Panel -->
            <div class="space-y-6">
                <!-- Action: Persetujuan / Penolakan -->
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm transition-colors">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Verifikasi Kesiapan Lapangan</h3>

                    @if ($booking->status === 'pending')
                        <!-- Form Setujui -->
                        <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="approve_notes" class="block text-2xs font-semibold text-slate-700 dark:text-slate-400 uppercase mb-1">Catatan Kesiapan Lapangan (Opsional)</label>
                                <input type="text" name="admin_notes" id="approve_notes" placeholder="Contoh: Lapangan bersih, lampu LED menyala, bola & rompi disiapkan"
                                       class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-emerald-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition cursor-pointer">
                                ✓ Konfirmasi Kesiapan & ACC Jadwal
                            </button>
                        </form>

                        <div class="relative flex py-2 items-center">
                            <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                            <span class="flex-shrink mx-2 text-2xs text-slate-400 uppercase">atau</span>
                            <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                        </div>

                        <!-- Form Tolak -->
                        <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="reject_notes" class="block text-2xs font-semibold text-rose-600 dark:text-rose-400 uppercase mb-1">Alasan Pembatalan / Refund <span class="text-rose-500">*</span></label>
                                <textarea name="admin_notes" id="reject_notes" rows="2" required placeholder="Contoh: Lapangan sedang maintenance darurat / disewa agenda pemda"
                                          class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-rose-500 placeholder-slate-400 dark:placeholder-slate-500"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition cursor-pointer">
                                ✕ Tolak Jadwal & Proses Refund
                            </button>
                        </form>
                    @else
                        <div class="text-xs text-slate-600 dark:text-slate-400 mb-4">
                            Status saat ini sudah <strong class="text-slate-900 dark:text-white">{{ strtoupper($booking->status) }}</strong>.
                        </div>

                        @if ($booking->status !== 'approved')
                            <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" class="mb-3">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition cursor-pointer">
                                    Ubah Menjadi Disetujui (Approve)
                                </button>
                            </form>
                        @endif

                        @if ($booking->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}" class="mb-3">
                                @csrf
                                <input type="hidden" name="admin_notes" value="Dibatalkan/ditolak oleh admin">
                                <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl transition cursor-pointer">
                                    Ubah Menjadi Ditolak (Reject)
                                </button>
                            </form>
                        @endif
                    @endif

                    <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-700">
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data booking ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2 bg-slate-100 dark:bg-slate-900 hover:bg-rose-100 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 border border-rose-200 dark:border-rose-500/30 text-xs font-semibold rounded-xl transition cursor-pointer">
                                Hapus Data Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
