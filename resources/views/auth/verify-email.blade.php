<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Verifikasi Email Anda</h2>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed">
            Terima kasih telah mendaftar di SportCourt! Sebelum melanjutkan, mohon periksa inbox email Anda dan klik tautan verifikasi yang baru saja kami kirimkan.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-500/30 rounded-xl text-xs font-medium text-emerald-800 dark:text-emerald-300">
            ✓ Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda daftarkan.
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <x-primary-button>
                    Kirim Ulang Email Verifikasi
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-slate-400 hover:text-rose-400 transition">
                Keluar (Logout)
            </button>
        </form>
    </div>
</x-guest-layout>
