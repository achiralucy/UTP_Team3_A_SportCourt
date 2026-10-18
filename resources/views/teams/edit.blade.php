<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Edit Tim: {{ $team->name }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Perbarui informasi kapten, kontak, atau deskripsi komunitas
                </p>
            </div>
            <a href="{{ route('teams.index') }}" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3 py-2 min-h-[44px] inline-flex items-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm transition-colors">
            <form method="POST" action="{{ route('teams.update', $team) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama Tim -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Nama Tim / Klub / Komunitas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" required
                           class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Kapten -->
                    <div>
                        <label for="captain_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Nama Kapten / Penanggung Jawab <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="captain_name" id="captain_name" value="{{ old('captain_name', $team->captain_name) }}" required
                               class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 @error('captain_name') border-rose-500 @enderror">
                        @error('captain_name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak / WA Kapten -->
                    <div>
                        <label for="captain_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            No. WhatsApp Kapten <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="captain_phone" id="captain_phone" value="{{ old('captain_phone', $team->captain_phone) }}" required
                               class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 @error('captain_phone') border-rose-500 @enderror">
                        @error('captain_phone')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jumlah Anggota -->
                <div>
                    <label for="members_count" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Estimasi Jumlah Anggota / Pemain <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="members_count" id="members_count" value="{{ old('members_count', $team->members_count) }}" min="2" max="50" required
                           class="w-full px-4 py-2.5 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 @error('members_count') border-rose-500 @enderror">
                    @error('members_count')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Deskripsi / Catatan Komunitas
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-500 @error('description') border-rose-500 @enderror">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                    <a href="{{ route('teams.index') }}" class="px-5 py-2.5 min-h-[44px] inline-flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-650 dark:text-slate-200 text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 min-h-[44px] inline-flex items-center justify-center rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition cursor-pointer">
                        Perbarui Tim
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
