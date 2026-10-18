<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Sewa Lapangan Olahraga
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Pilih lapangan, cek ketersediaan jam, dan lakukan reservasi secara instan
                </p>
            </div>
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 min-h-[44px] bg-slate-800 rounded-xl border border-slate-700 transition">
                Riwayat Booking
            </a>
        </div>
    </x-slot>

    <div x-data="bookingPaymentGateway()" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm" class="space-y-5">
                    @csrf

                    <div>
                        <label for="court_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Pilih Lapangan <span class="text-rose-400">*</span>
                        </label>
                        <select name="court_id" id="court_id" required
                                class="w-full px-4 py-2.5 min-h-[44px] bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('court_id') border-rose-500 @enderror">
                            <option value="">-- Pilih Lapangan Tersedia --</option>
                            @foreach ($courts as $court)
                                <option value="{{ $court->id }}"
                                        data-price="{{ $court->price_per_hour }}"
                                        data-category="{{ $court->category }}"
                                        {{ old('court_id', $selectedCourtId) == $court->id ? 'selected' : '' }}>
                                    {{ $court->name }} ({{ strtoupper($court->category) }}) — Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam
                                </option>
                            @endforeach
                        </select>
                        @error('court_id')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="team_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                Bermain Bersama Tim / Komunitas (Opsional)
                            </label>
                            <a href="{{ route('teams.create') }}" class="min-h-[44px] inline-flex items-center text-2xs text-indigo-400 hover:text-indigo-300 font-semibold" target="_blank">
                                + Daftarkan Tim Baru
                            </a>
                        </div>
                        <select name="team_id" id="team_id"
                                class="w-full px-4 py-2.5 min-h-[44px] bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('team_id') border-rose-500 @enderror">
                            <option value="">-- Tanpa Tim (Sewa Perorangan) --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }} (Kapten: {{ $team->captain_name }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-2xs text-slate-400 mt-1">Mengaitkan tim mempermudah pencatatan nama komunitas pada jadwal lapangan.</p>
                        @error('team_id')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Tanggal Main <span class="text-rose-400">*</span>
                        </label>
                        <input type="date" name="booking_date" id="booking_date"
                               value="{{ old('booking_date', $selectedDate) }}"
                               min="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d', strtotime('+30 days')) }}" required
                               style="color-scheme: dark;"
                               class="w-full px-4 py-2.5 min-h-[44px] bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('booking_date') border-rose-500 @enderror">
                        <p class="text-2xs text-slate-400 mt-1">Pemesanan dapat dilakukan maksimal 30 hari ke depan.</p>
                        @error('booking_date')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="scheduleBox" class="p-4 bg-slate-900/90 border border-slate-750 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Jadwal Terisi di Lapangan Ini
                            </span>
                            <span id="scheduleStatusText" class="text-2xs text-slate-400">Pilih lapangan & tanggal</span>
                        </div>
                        <div id="slotsContainer" class="flex flex-wrap gap-2 pt-1">
                            <span class="text-xs text-slate-400 italic">Silakan pilih lapangan terlebih dahulu untuk memuat jadwal.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                Jam Mulai (06:00 - 22:00) <span class="text-rose-400">*</span>
                            </label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '19:00') }}" min="06:00" max="22:00" step="1800" required
                                   style="color-scheme: dark;"
                                   class="w-full px-4 py-2.5 min-h-[44px] bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('start_time') border-rose-500 @enderror">
                            @error('start_time')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                Jam Selesai (Maks. 23:00) <span class="text-rose-400">*</span>
                            </label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', '21:00') }}" min="07:00" max="23:00" step="1800" required
                                   style="color-scheme: dark;"
                                   class="w-full px-4 py-2.5 min-h-[44px] bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('end_time') border-rose-500 @enderror">
                            @error('end_time')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="clientAlert" class="hidden p-3.5 rounded-xl text-xs font-medium border"></div>

                    <div class="pt-4 border-t border-slate-700 flex items-center justify-end gap-3">
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] rounded-xl bg-slate-700 hover:bg-slate-650 text-slate-200 text-sm font-medium transition">
                            Batal
                        </a>
                        <button type="button"
                                @click="openPaymentGateway()"
                                id="submitBtn"
                                class="inline-flex items-center justify-center px-6 py-2.5 min-h-[44px] rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Lanjut ke Pembayaran Online</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4 lg:sticky lg:top-24 self-start">
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-4">Estimasi Tarif Sewa</h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tarif per Jam:</span>
                            <span class="font-semibold text-slate-200" id="displayRate">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Estimasi Durasi:</span>
                            <span class="font-semibold text-slate-200" id="displayDuration">0 Jam</span>
                        </div>
                        <div class="pt-3 border-t border-slate-700 flex justify-between items-center">
                            <span class="text-slate-300 font-bold">Total Biaya:</span>
                            <span class="text-lg font-black text-emerald-400" id="displayTotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-emerald-950/40 border border-emerald-500/20 rounded-xl text-2xs text-emerald-300 leading-relaxed">
                        💡 <strong>Sistem Anti-Bentrok:</strong> SportCourt memvalidasi jadwal secara otomatis. Slot jam yang bentrok akan terdeteksi langsung sebelum Anda mengirim formulir.
                    </div>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-sm text-xs space-y-3.5">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-white uppercase tracking-wider text-2xs flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Metode Pembayaran Online
                        </h4>
                        <span class="text-3xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 font-semibold">100% Online</span>
                    </div>

                    <p class="text-slate-300 leading-relaxed text-2xs">
                        Pemesanan wajib dibayar di muka melalui gateway pembayaran online resmi:
                    </p>

                    <div class="space-y-2 pt-1 text-2xs">
                        <div @click="paymentMethod = 'qris'"
                             :class="paymentMethod === 'qris' ? 'border-emerald-500 bg-emerald-500/10 shadow-sm shadow-emerald-950/50' : 'border-slate-750 bg-slate-900/60 hover:border-slate-650'"
                             class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition select-none">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-lg bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-xs shrink-0">QRIS</span>
                                <div>
                                    <div class="font-semibold text-slate-200 text-xs">QRIS Instan Nasional</div>
                                    <div class="text-3xs text-slate-400">BCA, Livin', GoPay, OVO, Dana, ShopeePay</div>
                                </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                                 :class="paymentMethod === 'qris' ? 'border-emerald-400 bg-emerald-500 text-slate-950' : 'border-slate-600 bg-slate-800'">
                                <svg x-show="paymentMethod === 'qris'" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <div @click="paymentMethod = 'va'"
                             :class="paymentMethod === 'va' ? 'border-emerald-500 bg-emerald-500/10 shadow-sm shadow-emerald-950/50' : 'border-slate-750 bg-slate-900/60 hover:border-slate-650'"
                             class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition select-none">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-lg bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-xs shrink-0">VA</span>
                                <div>
                                    <div class="font-semibold text-slate-200 text-xs">Virtual Account Otomatis</div>
                                    <div class="text-3xs text-slate-400">BCA, Mandiri, BRI, BNI 24 Jam</div>
                                </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                                 :class="paymentMethod === 'va' ? 'border-emerald-400 bg-emerald-500 text-slate-950' : 'border-slate-600 bg-slate-800'">
                                <svg x-show="paymentMethod === 'va'" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-750 text-3xs text-slate-400 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-emerald-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Pembayaran Online & Konfirmasi Jadwal
                        </div>
                        <p class="leading-relaxed">
                            SportCourt Arena menerapkan sistem pembayaran online 100% di muka (QRIS & VA). Setelah pembayaran lunas, jadwal langsung diteruskan ke pengelola venue untuk verifikasi kesiapan fisik lapangan.
                        </p>
                    </div>
                </div>

                <div x-show="paymentModalOpen"
                     x-cloak
                     class="fixed inset-0 z-[60] overflow-y-auto bg-slate-950/85 backdrop-blur-sm flex items-center justify-center p-4">
                    <div @click.away="if (!isProcessingPayment) paymentModalOpen = false" class="bg-slate-800 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl relative">

                        <template x-if="!isProcessingPayment && !paymentSuccess">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-black text-2xs tracking-wider">GATEWAY</span>
                                        <h3 class="text-sm font-bold text-white">Selesaikan Pembayaran Online</h3>
                                    </div>
                                    <button @click="paymentModalOpen = false" class="text-slate-400 hover:text-white text-base w-8 h-8 flex items-center justify-center rounded-lg">&times;</button>
                                </div>

                                <div class="mt-4 space-y-4">
                                    <div class="p-3.5 bg-slate-900 rounded-xl border border-slate-750 text-xs space-y-1.5">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-2xs">Lapangan:</span>
                                            <span class="font-bold text-white" x-text="courtNameText"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-2xs">Jadwal Main:</span>
                                            <span class="font-semibold text-slate-200" x-text="bookingDateText + ' (' + bookingTimeText + ')'"></span>
                                        </div>
                                        <div class="pt-2 border-t border-slate-800 flex justify-between items-center">
                                            <span class="text-slate-300 font-bold text-2xs uppercase">Total Pembayaran:</span>
                                            <span class="text-xl font-black text-emerald-400" x-text="totalAmountText"></span>
                                        </div>
                                    </div>

                                    <div class="flex rounded-xl bg-slate-900 p-1 border border-slate-750 text-xs">
                                        <button type="button" @click="paymentMethod = 'qris'"
                                                :class="paymentMethod === 'qris' ? 'bg-emerald-500 text-slate-950 font-bold shadow' : 'text-slate-400 hover:text-white'"
                                                class="flex-1 py-1.5 rounded-lg text-center text-2xs transition">
                                            QRIS Instan
                                        </button>
                                        <button type="button" @click="paymentMethod = 'va'"
                                                :class="paymentMethod === 'va' ? 'bg-emerald-500 text-slate-950 font-bold shadow' : 'text-slate-400 hover:text-white'"
                                                class="flex-1 py-1.5 rounded-lg text-center text-2xs transition">
                                            Virtual Account
                                        </button>
                                    </div>

                                    <template x-if="paymentMethod === 'qris'">
                                        <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-inner border border-slate-200">
                                            <div class="text-2xs font-black tracking-widest text-slate-950 mb-1">QRIS STANDAR NASIONAL</div>
                                            <div class="text-3xs text-slate-500 mb-2">Merchant: SPORTSCOURT ARENA INDONESIA</div>
                                            <svg class="w-40 h-40 text-slate-950" viewBox="0 0 100 100" fill="currentColor">
                                                <rect width="100" height="100" fill="white" />
                                                <path fill="#020617" d="M10 10h24v24H10zM14 14h16v16H14zM18 18h8v8h-8zM66 10h24v24H66zM70 14h16v16H70zM74 18h8v8h-8zM10 66h24v24H10zM14 70h16v16H14zM18 74h8v8h-8zM42 10h8v8h-8zM50 18h8v8h-8zM42 26h8v8h-8zM58 10h8v8h-8zM10 42h8v8h-8zM18 50h8v8h-8zM26 42h8v8h-8zM42 42h16v8H42zM50 50h8v16h-8zM42 58h8v8h-8zM66 42h8v8h-8zM82 42h8v8h-8zM74 50h16v8H74zM66 58h8v8h-8zM82 58h8v16h-8zM42 74h8v16h-8zM50 82h16v8H50zM66 74h8v8h-8zM74 82h8v8h-8zM82 74h8v8h-8zM66 90h24v-4H66z"/>
                                            </svg>
                                            <div class="text-3xs text-slate-600 mt-2 text-center">
                                                Scan dengan BCA, Mandiri Livin', GoPay, OVO, Dana, ShopeePay
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="paymentMethod === 'va'">
                                        <div class="space-y-2 p-3 bg-slate-900 rounded-xl border border-slate-750 text-2xs">
                                            <div class="p-2.5 bg-slate-800/80 rounded-lg border border-slate-700 flex justify-between items-center">
                                                <div>
                                                    <div class="text-3xs text-slate-400 font-bold uppercase">BCA Virtual Account</div>
                                                    <div class="font-mono text-sm font-bold text-white mt-0.5">8801 2345 6789 0001</div>
                                                </div>
                                                <span class="text-3xs px-2 py-1 rounded bg-slate-700 text-slate-300">Salin</span>
                                            </div>
                                            <div class="p-2.5 bg-slate-800/80 rounded-lg border border-slate-700 flex justify-between items-center">
                                                <div>
                                                    <div class="text-3xs text-slate-400 font-bold uppercase">Mandiri Virtual Account</div>
                                                    <div class="font-mono text-sm font-bold text-white mt-0.5">8901 2345 6789 0002</div>
                                                </div>
                                                <span class="text-3xs px-2 py-1 rounded bg-slate-700 text-slate-300">Salin</span>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="text-center text-3xs text-slate-400">
                                        Sisa batas waktu pembayaran: <span class="font-mono text-amber-300 font-bold" x-text="String(timerMin).padStart(2, '0') + ':' + String(timerSec).padStart(2, '0')"></span>
                                    </div>
                                    <div class="flex items-center gap-3 pt-2">
                                        <button type="button" @click="paymentModalOpen = false" class="flex-1 px-5 py-3 min-h-[48px] bg-slate-700 hover:bg-slate-650 text-slate-200 text-sm font-semibold rounded-xl transition text-center cursor-pointer">
                                            Batal
                                        </button>
                                        <button type="button"
                                                @click="confirmPayAndSubmit()"
                                                class="flex-[2] px-6 py-3 min-h-[48px] bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition text-center flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer">
                                            <span>✓</span> Bayar Sekarang & Pesan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="isProcessingPayment">
                            <div class="py-8 text-center space-y-4">
                                <div class="w-12 h-12 border-3 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">Memproses Pembayaran Online...</h4>
                                    <p class="text-xs text-slate-400 mt-1">Menghubungi gateway pembayaran & memverifikasi transaksi Anda.</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="paymentSuccess">
                            <div class="py-8 text-center space-y-4">
                                <div class="w-14 h-14 bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 rounded-full flex items-center justify-center mx-auto text-2xl font-black">
                                    ✓
                                </div>
                                <div>
                                    <h4 class="text-base font-extrabold text-white">Pembayaran Sukses Terverifikasi!</h4>
                                    <p class="text-xs text-emerald-300 mt-1">Status Lunas • Menghubungkan ke sistem reservasi venue...</p>
                                </div>
                            </div>
                        </template>

                    </div>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-sm text-xs text-slate-400 space-y-2">
                    <h4 class="font-bold text-white uppercase tracking-wider text-2xs">Ketentuan Sewa SportCourt</h4>
                    <ul class="list-disc list-inside space-y-1.5 leading-relaxed">
                        <li>Jam operasional arena: <strong>06:00 - 23:00 WIB</strong>.</li>
                        <li>Durasi sewa: Minimal <strong>1 jam (60 menit)</strong>, maksimal <strong>6 jam</strong>.</li>
                        <li>Booking pada hari yang sama tidak boleh memilih jam yang sudah terlewat.</li>
                        <li>Wajib hadir 15 menit sebelum waktu main dimulai dan membawa sepatu olahraga khusus indoor.</li>
                        <li>Perubahan jadwal (reschedule) & pembatalan dapat dilakukan selama status booking masih <span class="text-amber-400 font-semibold">Menunggu Konfirmasi</span>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courtSelect = document.getElementById('court_id');
            const dateInput = document.getElementById('booking_date');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const displayRate = document.getElementById('displayRate');
            const displayDuration = document.getElementById('displayDuration');
            const displayTotal = document.getElementById('displayTotal');
            const slotsContainer = document.getElementById('slotsContainer');
            const scheduleStatusText = document.getElementById('scheduleStatusText');
            const clientAlert = document.getElementById('clientAlert');
            const submitBtn = document.getElementById('submitBtn');

            let currentBookedSlots = [];

            async function fetchBookedSlots() {
                const courtId = courtSelect.value;
                const date = dateInput.value;

                if (!courtId || !date) {
                    slotsContainer.innerHTML = '<span class="text-xs text-slate-500 italic">Pilih lapangan dan tanggal terlebih dahulu.</span>';
                    scheduleStatusText.textContent = '';
                    currentBookedSlots = [];
                    calculateAndValidate();
                    return;
                }

                scheduleStatusText.textContent = 'Memuat ketersediaan...';

                try {
                    const response = await fetch(`/courts/${courtId}/booked-slots?date=${date}`);
                    if (!response.ok) throw new Error('Gagal memuat jadwal.');
                    const data = await response.json();
                    currentBookedSlots = data.booked_slots || [];

                    if (currentBookedSlots.length === 0) {
                        scheduleStatusText.textContent = 'Semua jam tersedia';
                        slotsContainer.innerHTML = '<span class="px-3 py-1 bg-emerald-950/60 border border-emerald-500/30 text-emerald-300 rounded-lg text-xs font-medium">✓ Bebas booking! Seluruh slot jam hari ini masih kosong.</span>';
                    } else {
                        scheduleStatusText.textContent = `${currentBookedSlots.length} slot terisi`;
                        slotsContainer.innerHTML = currentBookedSlots.map(slot => {
                            const badgeColor = slot.status === 'approved' 
                                ? 'bg-rose-950/60 text-rose-300 border-rose-500/30' 
                                : 'bg-amber-950/60 text-amber-300 border-amber-500/30';
                            return `<span class="px-2.5 py-1 border rounded-lg text-xs font-mono font-semibold ${badgeColor}">
                                🔒 ${slot.start_time} - ${slot.end_time} (${slot.status_label})
                            </span>`;
                        }).join('');
                    }
                } catch (e) {
                    scheduleStatusText.textContent = 'Gagal memuat';
                    slotsContainer.innerHTML = '<span class="text-xs text-rose-400">Gagal mengambil jadwal lapangan.</span>';
                    currentBookedSlots = [];
                }

                calculateAndValidate();
            }

            function calculateAndValidate() {
                clientAlert.className = 'hidden p-3.5 rounded-xl text-xs font-medium border';
                clientAlert.innerHTML = '';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                const selectedOption = courtSelect.options[courtSelect.selectedIndex];
                const pricePerHour = selectedOption ? parseInt(selectedOption.getAttribute('data-price')) || 0 : 0;
                displayRate.textContent = 'Rp ' + pricePerHour.toLocaleString('id-ID');

                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                const bookingDate = dateInput.value;

                if (!startTime || !endTime) {
                    displayDuration.textContent = '0 Jam';
                    displayTotal.textContent = 'Rp 0';
                    return;
                }

                const [startH, startM] = startTime.split(':').map(Number);
                const [endH, endM] = endTime.split(':').map(Number);
                const startMinutes = startH * 60 + startM;
                const endMinutes = endH * 60 + endM;
                const diffMinutes = endMinutes - startMinutes;

                if (diffMinutes <= 0) {
                    displayDuration.textContent = '0 Jam';
                    displayTotal.textContent = 'Rp 0';
                    showWarning('Jam selesai harus lebih lambat dari jam mulai.');
                    return;
                }

                if (diffMinutes < 60) {
                    displayDuration.textContent = (diffMinutes / 60).toFixed(1) + ' Jam';
                    displayTotal.textContent = 'Rp 0';
                    showWarning('Durasi sewa minimal adalah 1 jam (60 menit).');
                    return;
                }

                if (diffMinutes > 360) {
                    displayDuration.textContent = (diffMinutes / 60).toFixed(1) + ' Jam';
                    displayTotal.textContent = 'Rp 0';
                    showWarning('Durasi sewa maksimal adalah 6 jam per transaksi.');
                    return;
                }

                if (startTime < '06:00' || endTime > '23:00') {
                    showWarning('Jam operasional lapangan adalah pukul 06:00 hingga 23:00 WIB.');
                    return;
                }

                const todayStr = localDateString(new Date());
                if (bookingDate === todayStr) {
                    const now = new Date();
                    const nowMinutes = now.getHours() * 60 + now.getMinutes();
                    if (startMinutes <= nowMinutes) {
                        const nowText = minutesToTime(nowMinutes);
                        const nextStart = Math.max(Math.ceil((nowMinutes + 1) / 30) * 30, 6 * 60);

                        if (nextStart > 22 * 60) {
                            showWarning(`Sekarang pukul ${nowText}, sudah tidak ada slot yang bisa dipesan untuk hari ini. Silakan pilih tanggal besok atau setelahnya.`);
                        } else {
                            showWarning(`Jam ${startTime} sudah terlewat (sekarang pukul ${nowText}). Pilih jam mulai ${minutesToTime(nextStart)} atau lebih, atau ganti tanggal.`);
                        }
                        return;
                    }
                }

                for (const slot of currentBookedSlots) {
                    const [sH, sM] = slot.start_time.split(':').map(Number);
                    const [eH, eM] = slot.end_time.split(':').map(Number);
                    const slotStart = sH * 60 + sM;
                    const slotEnd = eH * 60 + eM;

                    if (startMinutes < slotEnd && endMinutes > slotStart) {
                        showWarning(`⚠️ Jadwal Bentrok! Jam yang dipilih bertubrukan dengan booking lain (${slot.start_time} - ${slot.end_time} WIB).`);
                        return;
                    }
                }

                const durationHours = Math.ceil(diffMinutes / 60);
                displayDuration.textContent = durationHours + ' Jam';
                const total = durationHours * pricePerHour;
                displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            function showWarning(msg) {
                clientAlert.className = 'block p-3.5 rounded-xl text-xs font-semibold bg-rose-950/60 border border-rose-500/40 text-rose-300';
                clientAlert.innerHTML = `⚠️ ${msg}`;
            }
            const hasOldInput = @json(old('start_time') !== null || old('end_time') !== null);

            function localDateString(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            }

            function minutesToTime(total) {
                const h = String(Math.floor(total / 60)).padStart(2, '0');
                const m = String(total % 60).padStart(2, '0');
                return `${h}:${m}`;
            }

            function applyDefaultTimes() {
                if (hasOldInput) {
                    return;
                }

                const now = new Date();
                if (dateInput.value !== localDateString(now)) {
                    return;
                }

                const nowMinutes = now.getHours() * 60 + now.getMinutes();
                const defaultStart = 19 * 60;
                if (defaultStart > nowMinutes) {
                    return;
                }

                const nextStart = Math.max(Math.ceil((nowMinutes + 1) / 30) * 30, 6 * 60);

                if (nextStart > 22 * 60) {
                    const tomorrow = new Date(now);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    dateInput.value = localDateString(tomorrow);
                    return;
                }

                startTimeInput.value = minutesToTime(nextStart);
                endTimeInput.value = minutesToTime(Math.min(nextStart + 60, 23 * 60));
            }

            courtSelect.addEventListener('change', fetchBookedSlots);
            dateInput.addEventListener('change', fetchBookedSlots);
            startTimeInput.addEventListener('input', calculateAndValidate);
            endTimeInput.addEventListener('input', calculateAndValidate);
           
            applyDefaultTimes();
            if (courtSelect.value) {
                fetchBookedSlots();
            } else {
                calculateAndValidate();
            }
        });

        function bookingPaymentGateway() {
            return {
                paymentModalOpen: false,
                paymentMethod: 'qris',
                isProcessingPayment: false,
                paymentSuccess: false,
                courtNameText: '-',
                bookingDateText: '-',
                bookingTimeText: '-',
                durationText: '0 Jam',
                totalAmountText: 'Rp 0',
                timerMin: 14,
                timerSec: 59,
                timerInterval: null,
                openPaymentGateway() {
                    const form = document.getElementById('bookingForm');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    const alertBox = document.getElementById('clientAlert');
                    if (alertBox && !alertBox.classList.contains('hidden') && alertBox.className.includes('rose')) {
                        alert('Silakan periksa kembali jadwal sewa lapangan Anda karena masih terdapat bentrok atau kesalahan durasi.');
                        return;
                    }

                    const courtSelect = document.getElementById('court_id');
                    const selectedOpt = courtSelect.options[courtSelect.selectedIndex];
                    this.courtNameText = selectedOpt ? selectedOpt.text.split(' (')[0] : '-';
                    this.bookingDateText = document.getElementById('booking_date').value || '-';
                    this.bookingTimeText = (document.getElementById('start_time').value || '') + ' - ' + (document.getElementById('end_time').value || '') + ' WIB';
                    this.durationText = document.getElementById('displayDuration').textContent || '0 Jam';
                    this.totalAmountText = document.getElementById('displayTotal').textContent || 'Rp 0';

                    this.paymentSuccess = false;
                    this.isProcessingPayment = false;
                    this.paymentModalOpen = true;

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
                },
                confirmPayAndSubmit() {
                    this.isProcessingPayment = true;
                    setTimeout(() => {
                        this.isProcessingPayment = false;
                        this.paymentSuccess = true;
                        setTimeout(() => {
                            document.getElementById('bookingForm').submit();
                        }, 800);
                    }, 1200);
                }
            };
        }
    </script>
</x-app-layout>
