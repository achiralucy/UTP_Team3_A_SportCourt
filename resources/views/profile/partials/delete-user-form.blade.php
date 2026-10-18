<section class="space-y-6">
    <header>
        <h2 class="text-base font-bold text-rose-400">
            Hapus Akun Pengguna
        </h2>

        <p class="mt-1 text-xs text-slate-400">
            Setelah akun Anda dihapus, seluruh data profil, tim, dan riwayat reservasi Anda akan dihapus secara permanen. Sebelum menghapus akun, pastikan Anda telah menyelesaikan seluruh kewajiban atau menyimpan informasi yang diperlukan.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus Akun Saya</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-slate-800 text-slate-100">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-white">
                Apakah Anda yakin ingin menghapus akun Anda?
            </h2>

            <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                Tindakan ini permanen. Silakan masukkan kata sandi akun Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini.
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Kata Sandi Akun" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full sm:w-3/4"
                    placeholder="Masukkan kata sandi untuk konfirmasi"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button>
                    Ya, Hapus Akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
