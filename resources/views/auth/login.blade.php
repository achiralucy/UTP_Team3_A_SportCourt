<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-black text-white tracking-tight">Masuk ke Akun Anda</h2>
        <p class="text-xs text-slate-400 mt-1">Silakan masuk untuk mengelola pemesanan lapangan dan tim Anda.</p>
    </div>

    <!-- Session Status (e.g. Registrasi berhasil) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a class="text-2xs text-emerald-400 hover:text-emerald-300 transition min-h-[44px] inline-flex items-center" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer min-h-[44px]">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded bg-slate-900 border-slate-700 text-emerald-500 shadow-sm focus:ring-emerald-500 focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-xs text-slate-400 select-none">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-3">
            <x-primary-button class="w-full py-3 text-sm font-bold shadow-lg shadow-emerald-600/30">
                Masuk Sekarang &rarr;
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="pt-4 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-400">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-emerald-400 hover:text-emerald-300 transition min-h-[44px] inline-flex items-center">
                    Daftar sebagai Penyewa
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
