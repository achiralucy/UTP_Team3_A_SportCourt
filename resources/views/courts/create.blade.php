<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Tambah Lapangan Baru
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Masukkan spesifikasi lapangan, harga per jam, dan fasilitas pendukung
                </p>
            </div>
            <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.courts.store') }}" class="space-y-5">
                @csrf

                <!-- Nama Lapangan -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Nama Lapangan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Arena Futsal A (Vinyl Pro) atau Court Badminton 1"
                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori Olahraga -->
                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Kategori Olahraga <span class="text-rose-400">*</span>
                        </label>
                        <select name="category" id="category" required
                                class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('category') border-rose-500 @enderror">
                            <option value="futsal" {{ old('category') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="badminton" {{ old('category') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Sewa per Jam -->
                    <div>
                        <label for="price_per_hour" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Tarif Sewa (Rp / Jam) <span class="text-rose-400">*</span>
                        </label>
                        <input type="number" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', 100000) }}" step="5000" min="10000" required
                               class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('price_per_hour') border-rose-500 @enderror">
                        @error('price_per_hour')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Ketersediaan -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Status Ketersediaan <span class="text-rose-400">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 focus:outline-none focus:border-emerald-500 @error('status') border-rose-500 @enderror">
                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia (Ready untuk disewa)</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Dalam Perawatan (Maintenance)</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fasilitas -->
                <div>
                    <label for="facilities" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Fasilitas Pendukung
                    </label>
                    <input type="text" name="facilities" id="facilities" value="{{ old('facilities') }}"
                           placeholder="Contoh: Rumput Interlock, Lampu LED Stadium, Ruang Ganti Ber-AC, Rompi Gratis"
                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('facilities') border-rose-500 @enderror">
                    @error('facilities')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Lapangan -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Deskripsi Lengkap Lapangan
                    </label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Deskripsi keunggulan lapangan, jenis lantai, luas, dan peraturan khusus..."
                              class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('description') border-rose-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-700 flex items-center justify-end gap-3">
                    <a href="{{ route('courts.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-650 text-slate-200 text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                        Simpan Lapangan Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
