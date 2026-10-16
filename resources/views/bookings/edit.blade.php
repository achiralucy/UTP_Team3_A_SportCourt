<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Ubah Jadwal Booking #{{ $booking->id }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Sesuaikan kembali tanggal, jam sewa, atau tim sebelum disetujui admin
                </p>
            </div>
            <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Pilih Lapangan -->
                <div>
                    <label for="court_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Lapangan <span class="text-rose-400">*</span>
                    </label>
                    <select name="court_id" id="court_id" required
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('court_id') border-rose-500 @enderror">
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}"
                                    data-price="{{ $court->price_per_hour }}"
                                    {{ old('court_id', $booking->court_id) == $court->id ? 'selected' : '' }}>
                                {{ $court->name }} ({{ strtoupper($court->category) }}) — Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam
                            </option>
                        @endforeach
                    </select>
                    @error('court_id')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tim -->
                <div>
                    <label for="team_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tim Komunitas (Opsional)
                    </label>
                    <select name="team_id" id="team_id"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('team_id') border-rose-500 @enderror">
                        <option value="">-- Tanpa Tim (Sewa Perorangan) --</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id', $booking->team_id) == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} (Kapten: {{ $team->captain_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Booking -->
                <div>
                    <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tanggal Main <span class="text-rose-400">*</span>
                    </label>
                    <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', $booking->booking_date->toDateString()) }}" min="{{ date('Y-m-d') }}" required
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
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', substr($booking->start_time, 0, 5)) }}" required
                               class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('start_time') border-rose-500 @enderror">
                        @error('start_time')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Jam Selesai <span class="text-rose-400">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', substr($booking->end_time, 0, 5)) }}" required
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
                        Simpan Perubahan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
