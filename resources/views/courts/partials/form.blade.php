@php
    $isEdit = isset($court) && $court->exists;
    $field = 'w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500';
    $label = 'block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5';
@endphp

<form method="POST"
      action="{{ $isEdit ? route('admin.courts.update', $court) : route('admin.courts.store') }}"
      enctype="multipart/form-data"
      class="space-y-5"
      x-data="{
          preview: null,
          removeImage: {{ old('remove_image') ? 'true' : 'false' }},
          price: {{ (int) old('price_per_hour', $court->price_per_hour ?? 100000) }},
          pick(event) {
              const file = event.target.files[0];
              this.preview = file ? URL.createObjectURL(file) : null;
              if (file) this.removeImage = false;
          },
          clear() {
              this.preview = null;
              this.$refs.image.value = '';
          },
          get formatted() {
              return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.price || 0) + ' / jam';
          }
      }">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="{{ $label }}">Nama Lapangan <span class="text-rose-400">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $court->name ?? '') }}" required maxlength="100"
               placeholder="Contoh: Arena Futsal A (Vinyl Pro)"
               class="{{ $field }} @error('name') border-rose-500 @enderror">
        @error('name')
            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="category" class="{{ $label }}">Jenis Olahraga <span class="text-rose-400">*</span></label>
            <select name="category" id="category" required class="{{ $field }} @error('category') border-rose-500 @enderror">
                @foreach (\App\Models\Court::CATEGORIES as $value => $text)
                    <option value="{{ $value }}" @selected(old('category', $court->category ?? 'futsal') === $value)>{{ $text }}</option>
                @endforeach
            </select>
            @error('category')
                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="price_per_hour" class="{{ $label }}">Harga Sewa (Rp / Jam) <span class="text-rose-400">*</span></label>
            <input type="number" name="price_per_hour" id="price_per_hour" x-model.number="price" step="5000" min="10000" max="5000000" required
                   class="{{ $field }} @error('price_per_hour') border-rose-500 @enderror">
            <p class="text-2xs text-emerald-400 mt-1" x-text="formatted"></p>
            @error('price_per_hour')
                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="status" class="{{ $label }}">Status Ketersediaan <span class="text-rose-400">*</span></label>
        <select name="status" id="status" required class="{{ $field }} @error('status') border-rose-500 @enderror">
            <option value="available" @selected(old('status', $court->status ?? 'available') === 'available')>Tersedia (bisa dibooking)</option>
            <option value="maintenance" @selected(old('status', $court->status ?? 'available') === 'maintenance')>Maintenance (tidak bisa dibooking)</option>
        </select>
        @error('status')
            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="facilities" class="{{ $label }}">Fasilitas</label>
        <input type="text" name="facilities" id="facilities" value="{{ old('facilities', $court->facilities ?? '') }}" maxlength="255"
               placeholder="Pisahkan dengan koma, contoh: Lampu LED, Ruang Ganti, Parkir Luas"
               class="{{ $field }} @error('facilities') border-rose-500 @enderror">
        <p class="text-2xs text-slate-500 mt-1">Pisahkan setiap fasilitas dengan tanda koma.</p>
        @error('facilities')
            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="{{ $label }}">Deskripsi</label>
        <textarea name="description" id="description" rows="3" maxlength="1000"
                  placeholder="Jenis lantai, ukuran lapangan, atau peraturan khusus"
                  class="{{ $field }} @error('description') border-rose-500 @enderror">{{ old('description', $court->description ?? '') }}</textarea>
        @error('description')
            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image" class="{{ $label }}">Foto Lapangan</label>

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-48 h-32 rounded-xl overflow-hidden bg-slate-900 border border-slate-700 flex items-center justify-center shrink-0">
                <template x-if="preview">
                    <img :src="preview" alt="Pratinjau foto" class="w-full h-full object-cover">
                </template>
                @if ($isEdit && $court->image_url)
                    <img x-show="!preview && !removeImage" src="{{ $court->image_url }}" alt="Foto {{ $court->name }}" class="w-full h-full object-cover">
                @endif
                <span x-show="!preview && {{ $isEdit && $court->image_url ? '(removeImage)' : 'true' }}" class="text-2xs text-slate-500 text-center px-3">Belum ada foto</span>
            </div>

            <div class="flex-grow space-y-2">
                <input type="file" name="image" id="image" x-ref="image" accept="image/png,image/jpeg,image/webp" @change="pick($event)"
                       class="block w-full text-xs text-slate-300 file:me-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-slate-700 file:text-slate-100 file:text-xs file:font-semibold hover:file:bg-slate-650 @error('image') border border-rose-500 rounded-lg @enderror">
                <p class="text-2xs text-slate-500">Format JPG, PNG, atau WEBP. Ukuran maksimal 2 MB.</p>
                @error('image')
                    <p class="text-xs text-rose-400">{{ $message }}</p>
                @enderror

                <button type="button" x-show="preview" @click="clear()" class="text-2xs font-semibold text-slate-400 hover:text-white underline">
                    Batalkan foto yang dipilih
                </button>

                @if ($isEdit && $court->image_url)
                    <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                        <input type="checkbox" name="remove_image" value="1" x-model="removeImage" class="rounded bg-slate-900 border-slate-600 text-rose-500 focus:ring-rose-500">
                        Hapus foto saat ini
                    </label>
                @endif
            </div>
        </div>
    </div>

    <div class="pt-4 border-t border-slate-700 flex items-center justify-end gap-3">
        <a href="{{ route('courts.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-650 text-slate-200 text-sm font-medium transition">
            Batal
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Lapangan' }}
        </button>
    </div>
</form>