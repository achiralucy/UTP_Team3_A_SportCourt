<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Daftar Akun Penyewa</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan akun untuk mulai memesan lapangan dan mengelola tim Anda di SportCourt.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Contoh: Faris Adithya" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-rose-500" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat Email Aktif" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-500" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-500" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi password di atas" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-rose-500" />
        </div>

        <div class="pt-3">
            <x-primary-button class="w-full py-3 text-sm font-bold shadow-lg shadow-emerald-600/25">
                Daftar Akun Sekarang
            </x-primary-button>
        </div>

        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 text-center">
            <p class="text-xs text-slate-600 dark:text-slate-400">
                Sudah memiliki akun terdaftar?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline transition min-h-[44px] inline-flex items-center">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
