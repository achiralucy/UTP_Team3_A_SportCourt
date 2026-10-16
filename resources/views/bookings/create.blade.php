<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Sewa Lapangan Olahraga
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Pilih lapangan, tentukan waktu sewa, dan konfirmasi jadwal latihanmu
                </p>
            </div>
            <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">
                &larr; Riwayat Booking
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Input (2 Cols) -->
            <div class="lg:col-span-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm" class="space-y-5">
                    @csrf

                    <!-- Pilih Lapangan -->
                    <div>
                        <label for="court_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Pilih Lapangan <span class="text-rose-400">*</span>
                        </label>
                        <select name="court_id" id="court_id" required
                                class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('court_id') border-rose-500 @enderror">
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

                    <!-- Pilih Tim Komunitas (Fitur Faris) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="team_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                Bermain Bersama Tim / Komunitas (Opsional)
                            </label>
                            <a href="{{ route('teams.create') }}" class="text-2xs text-indigo-400 hover:text-indigo-300 font-semibold" target="_blank">
                                + Daftarkan Tim Baru
                            </a>
                        </div>
                        <select name="team_id" id="team_id"
                                class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('team_id') border-rose-500 @enderror">
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

                    <!-- Tanggal Booking -->
                    <div>
                        <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Tanggal Main <span class="text-rose-400">*</span>
                        </label>
                        <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('booking_date') border-rose-500 @enderror">
                        @error('booking_date')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu Mulai & Selesai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                Jam Mulai <span class="text-rose-400">*</span>
                            </label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '19:00') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('start_time') border-rose-500 @enderror">
                            @error('start_time')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                Jam Selesai <span class="text-rose-400">*</span>
                            </label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', '21:00') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('end_time') border-rose-500 @enderror">
                            @error('end_time')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-700 flex items-center justify-end gap-3">
                        <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-650 text-slate-200 text-sm font-medium transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                            Kirim Pengajuan Booking
                        </button>
                    </div>
                </form>
            </div>

            <!-- Estimasi Biaya & Info Panel (1 Col) -->
            <div class="space-y-4">
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Estimasi Tarif Sewa</h3>

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
                        💡 <strong>Sistem Anti-Bentrok:</strong> SportCourt akan otomatis memvalidasi jadwal sebelum menyimpan. Jika jadwal yang dipilih sudah dibooking orang lain, sistem akan memberitahu Anda.
                    </div>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-sm text-xs text-slate-400 space-y-2">
                    <h4 class="font-bold text-white uppercase tracking-wider text-2xs">Aturan Penyewaan</h4>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Harap hadir 15 menit sebelum waktu main dimulai.</li>
                        <li>Wajib menggunakan sepatu olahraga standar indoor.</li>
                        <li>Pembatalan atau reschedule dapat dilakukan selama status masih <span class="text-amber-400">Menunggu</span>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Kalkulator Estimasi Biaya Otomatis -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courtSelect = document.getElementById('court_id');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const displayRate = document.getElementById('displayRate');
            const displayDuration = document.getElementById('displayDuration');
            const displayTotal = document.getElementById('displayTotal');

            function calculatePrice() {
                const selectedOption = courtSelect.options[courtSelect.selectedIndex];
                const pricePerHour = selectedOption ? parseInt(selectedOption.getAttribute('data-price')) || 0 : 0;
                displayRate.textContent = 'Rp ' + pricePerHour.toLocaleString('id-ID');

                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                if (startTime && endTime) {
                    const [startH, startM] = startTime.split(':').map(Number);
                    const [endH, endM] = endTime.split(':').map(Number);

                    const startMinutes = startH * 60 + startM;
                    const endMinutes = endH * 60 + endM;

                    if (endMinutes > startMinutes) {
                        const durationHours = Math.ceil((endMinutes - startMinutes) / 60);
                        displayDuration.textContent = durationHours + ' Jam';
                        const total = durationHours * pricePerHour;
                        displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                        return;
                    }
                }

                displayDuration.textContent = '0 Jam';
                displayTotal.textContent = 'Rp 0';
            }

            courtSelect.addEventListener('change', calculatePrice);
            startTimeInput.addEventListener('input', calculatePrice);
            endTimeInput.addEventListener('input', calculatePrice);

            calculatePrice();
        });
    </script>
</x-app-layout>
