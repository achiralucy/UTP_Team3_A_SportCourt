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
                &larr; Riwayat Booking
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <button type="submit" id="submitBtn" class="inline-flex items-center justify-center px-6 py-2.5 min-h-[44px] rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                            Kirim Pengajuan Booking
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

                <div x-data="{ paymentMethod: 'qris' }" class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-sm text-xs space-y-3.5">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-white uppercase tracking-wider text-2xs flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Pilihan Pembayaran & Check-In
                        </h4>
                        <span class="text-3xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 font-semibold">Resmi</span>
                    </div>

                    <p class="text-slate-300 leading-relaxed text-2xs">
                        Pilih preferensi transaksi Anda. Reservasi online langsung mengunci slot jadwal pertandingan Anda:
                    </p>

                    <div class="space-y-2 pt-1 text-2xs">
                        <!-- Card 1: QRIS -->
                        <div @click="paymentMethod = 'qris'"
                             :class="paymentMethod === 'qris' ? 'border-emerald-500 bg-emerald-500/10 shadow-sm shadow-emerald-950/50' : 'border-slate-750 bg-slate-900/60 hover:border-slate-650'"
                             class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition select-none">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-lg bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-xs shrink-0">QRIS</span>
                                <div>
                                    <div class="font-semibold text-slate-200 text-xs">QRIS Instan Nasional</div>
                                    <div class="text-3xs text-slate-400">GoPay, OVO, Dana, ShopeePay, BCA, Livin'</div>
                                </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                                 :class="paymentMethod === 'qris' ? 'border-emerald-400 bg-emerald-500 text-slate-950' : 'border-slate-600 bg-slate-800'">
                                <svg x-show="paymentMethod === 'qris'" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Card 2: Transfer Bank -->
                        <div @click="paymentMethod = 'va'"
                             :class="paymentMethod === 'va' ? 'border-emerald-500 bg-emerald-500/10 shadow-sm shadow-emerald-950/50' : 'border-slate-750 bg-slate-900/60 hover:border-slate-650'"
                             class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition select-none">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-lg bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-xs shrink-0">VA</span>
                                <div>
                                    <div class="font-semibold text-slate-200 text-xs">Transfer Bank / Virtual Account</div>
                                    <div class="text-3xs text-slate-400">BCA, Mandiri, BRI, BNI</div>
                                </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                                 :class="paymentMethod === 'va' ? 'border-emerald-400 bg-emerald-500 text-slate-950' : 'border-slate-600 bg-slate-800'">
                                <svg x-show="paymentMethod === 'va'" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Card 3: Bayar Kasir -->
                        <div @click="paymentMethod = 'cash'"
                             :class="paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-500/10 shadow-sm shadow-emerald-950/50' : 'border-slate-750 bg-slate-900/60 hover:border-slate-650'"
                             class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition select-none">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-lg bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-xs shrink-0">CASH</span>
                                <div>
                                    <div class="font-semibold text-slate-200 text-xs">Bayar di Kasir Venue</div>
                                    <div class="text-3xs text-slate-400">Tunai / EDC Debit saat check-in di arena</div>
                                </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                                 :class="paymentMethod === 'cash' ? 'border-emerald-400 bg-emerald-500 text-slate-950' : 'border-slate-600 bg-slate-800'">
                                <svg x-show="paymentMethod === 'cash'" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-750 text-3xs text-slate-400 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-slate-300">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Alur Transaksi & Validasi
                        </div>
                        <p class="leading-relaxed">
                            Jadwal Anda langsung terkunci saat reservasi diajukan. Instruksi pembayaran QRIS dinamis dan verifikasi kasir dapat diakses langsung pada halaman <strong class="text-slate-300">E-Tiket</strong> Anda setelah konfirmasi diajukan.
                        </p>
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

                const todayStr = new Date().toISOString().slice(0, 10);
                if (bookingDate === todayStr) {
                    const now = new Date();
                    const nowMinutes = now.getHours() * 60 + now.getMinutes();
                    if (startMinutes <= nowMinutes) {
                        showWarning('Jam mulai sewa untuk hari ini tidak boleh lebih awal dari waktu sekarang.');
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

            courtSelect.addEventListener('change', fetchBookedSlots);
            dateInput.addEventListener('change', fetchBookedSlots);
            startTimeInput.addEventListener('input', calculateAndValidate);
            endTimeInput.addEventListener('input', calculateAndValidate);

            if (courtSelect.value) {
                fetchBookedSlots();
            } else {
                calculateAndValidate();
            }
        });
    </script>
</x-app-layout>
