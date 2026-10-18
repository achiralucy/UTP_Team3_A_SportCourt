<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Konfirmasi Keamanan</h2>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed">
            Halaman ini membutuhkan otorisasi keamanan tambahan. Silakan masukkan kata sandi akun Anda untuk melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi Akun" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-2.5">
                Konfirmasi Kata Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
