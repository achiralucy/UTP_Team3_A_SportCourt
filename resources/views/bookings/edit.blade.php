<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Ubah Jadwal Booking #{{ $booking->id }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Sesuaikan tanggal, jam sewa, atau tim sebelum pesanan disetujui admin
                </p>
            </div>
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition">
                &larr; Riwayat Booking
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-500/30 rounded-2xl flex items-center justify-between text-xs text-amber-800 dark:text-amber-300">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 dark:bg-amber-400 animate-pulse"></span>
                <span>
                    Anda sedang mengubah booking <strong>#{{ $booking->id }}</strong> (Status: <strong>Menunggu Konfirmasi</strong>). Perubahan tidak dapat dilakukan jika status sudah disetujui.
                </span>
            </div>
            <span class="font-mono text-2xs text-amber-800 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/50 px-2 py-0.5 rounded font-bold">PENDING</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm transition-colors">
                <form method="POST" action="{{ route('bookings.update', $booking) }}" id="bookingEditForm" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="court_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Pilih Lapangan <span class="text-rose-500">*</span>
                        </label>
                        <select name="court_id" id="court_id" required
                                class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 @error('court_id') border-rose-500 @enderror">
                            @foreach ($courts as $court)
                                <option value="{{ $court->id }}"
                                        data-price="{{ $court->price_per_hour }}"
                                        data-category="{{ $court->category }}"
                                        {{ old('court_id', $booking->court_id) == $court->id ? 'selected' : '' }}>
                                    {{ $court->name }} ({{ strtoupper($court->category) }}) — Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam
                                </option>
                            @endforeach
                        </select>
                        @error('court_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="team_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                Tim Komunitas (Opsional)
                            </label>
                            <a href="{{ route('teams.create') }}" class="min-h-[44px] inline-flex items-center text-2xs text-emerald-600 dark:text-indigo-400 hover:underline font-semibold" target="_blank">
                                + Daftarkan Tim Baru
                            </a>
                        </div>
                        <select name="team_id" id="team_id"
                                class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 @error('team_id') border-rose-500 @enderror">
                            <option value="">-- Tanpa Tim (Sewa Perorangan) --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id', $booking->team_id) == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }} (Kapten: {{ $team->captain_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('team_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Tanggal Main <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="booking_date" id="booking_date"
                               value="{{ old('booking_date', $booking->booking_date->toDateString()) }}"
                               min="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d', strtotime('+30 days')) }}" required
                               class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 @error('booking_date') border-rose-500 @enderror">
                        @error('booking_date')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="scheduleBox" class="p-4 bg-slate-50 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-750 rounded-2xl space-y-2 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                Jadwal Terisi di Lapangan Ini
                            </span>
                            <span id="scheduleStatusText" class="text-2xs text-slate-500 dark:text-slate-400">Memuat...</span>
                        </div>
                        <div id="slotsContainer" class="flex flex-wrap gap-2 pt-1">
                            <span class="text-xs text-slate-500 dark:text-slate-400 italic">Memuat ketersediaan slot jam...</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Jam Mulai (06:00 - 22:00) <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" name="start_time" id="start_time"
                                   value="{{ old('start_time', substr($booking->start_time, 0, 5)) }}"
                                   min="06:00" max="22:00" step="1800" required
                                   class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 @error('start_time') border-rose-500 @enderror">
                            @error('start_time')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Jam Selesai (Maks. 23:00) <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" name="end_time" id="end_time"
                                   value="{{ old('end_time', substr($booking->end_time, 0, 5)) }}"
                                   min="07:00" max="23:00" step="1800" required
                                   class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 @error('end_time') border-rose-500 @enderror">
                            @error('end_time')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="clientAlert" class="hidden p-3.5 rounded-xl text-xs font-medium border"></div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-650 dark:text-slate-200 text-sm font-medium transition">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn" class="inline-flex items-center justify-center px-6 py-2.5 min-h-[44px] rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                            Simpan Perubahan Jadwal
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4 lg:sticky lg:top-24 self-start">
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm transition-colors">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-400 mb-4">Estimasi Tarif Baru</h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Tarif per Jam:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" id="displayRate">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Durasi Terhitung:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" id="displayDuration">0 Jam</span>
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                            <span class="text-slate-700 dark:text-slate-300 font-bold">Total Biaya Baru:</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400" id="displayTotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-2xs text-slate-500 pt-1">
                            <span>Biaya Sebelumnya:</span>
                            <span class="font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-500/20 rounded-xl text-2xs text-emerald-800 dark:text-emerald-300 leading-relaxed">
                        💡 <strong>Anti-Bentrok Otomatis:</strong> Jadwal booking ini dikecualikan saat pengecekan, sehingga Anda bebas mengubah rentang jam selama tidak menabrak booking pengguna lain.
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm text-xs text-slate-600 dark:text-slate-400 space-y-2 transition-colors">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-2xs">Aturan Reschedule</h4>
                    <ul class="list-disc list-inside space-y-1.5 leading-relaxed">
                        <li>Jam operasional: <strong>06:00 - 23:00 WIB</strong>.</li>
                        <li>Durasi sewa: Minimal <strong>1 jam (60 menit)</strong>, maksimal <strong>6 jam</strong>.</li>
                        <li>Jika mengubah ke hari ini, jam mulai tidak boleh lebih awal dari waktu saat ini.</li>
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
            const currentBookingId = {{ $booking->id }};

            let currentBookedSlots = [];

            async function fetchBookedSlots() {
                const courtId = courtSelect.value;
                const date = dateInput.value;

                if (!courtId || !date) {
                    slotsContainer.innerHTML = '<span class="text-xs text-slate-500 italic">Pilih lapangan dan tanggal.</span>';
                    currentBookedSlots = [];
                    calculateAndValidate();
                    return;
                }

                scheduleStatusText.textContent = 'Memuat ketersediaan...';

                try {
                    const response = await fetch(`/courts/${courtId}/booked-slots?date=${date}&exclude_booking_id=${currentBookingId}`);
                    if (!response.ok) throw new Error('Gagal memuat jadwal.');
                    const data = await response.json();
                    currentBookedSlots = data.booked_slots || [];

                    if (currentBookedSlots.length === 0) {
                        scheduleStatusText.textContent = 'Semua jam tersedia';
                        slotsContainer.innerHTML = '<span class="px-3 py-1 bg-emerald-950/60 border border-emerald-500/30 text-emerald-300 rounded-lg text-xs font-medium">✓ Seluruh slot jam selain booking Anda masih kosong.</span>';
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

            fetchBookedSlots();
        });
    </script>
</x-app-layout>
