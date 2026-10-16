<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SportCourt - Sistem Booking Lapangan Futsal & Badminton</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased selection:bg-emerald-500 selection:text-slate-950">

    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-emerald-500/25">
                    SC
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tight text-white">
                        Sport<span class="text-emerald-400">Court</span>
                    </span>
                    <span class="block text-3xs font-bold uppercase tracking-widest text-slate-400">Arena & Court Booking</span>
                </div>
            </div>

            <nav class="flex items-center gap-3">
                <a href="{{ route('courts.index') }}" class="hidden sm:inline-block text-sm font-medium text-slate-300 hover:text-white px-3 py-2 rounded-xl transition">
                    Katalog Lapangan
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                            Masuk ke Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-200 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-600/25 transition">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <section class="relative pt-16 pb-24 overflow-hidden">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem Reservasi Lapangan Futsal & Badminton • UTP PWL 2026
            </div>

            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-white max-w-4xl mx-auto leading-tight">
                Pesan Lapangan Olahraga Cepat, Tepat, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Bebas Bentrok</span>.
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Platform reservasi lapangan futsal dan badminton modern dengan sistem validasi jadwal anti-bentrok, manajemen data tim/komunitas, dan persetujuan instan.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('courts.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-base shadow-xl shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                    Lihat Lapangan Tersedia
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-base transition">
                    Mulai Booking Sekarang
                </a>
            </div>

            <div class="mt-12 max-w-xl mx-auto bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-2xl text-left">
                <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                    <span class="text-2xs font-extrabold uppercase tracking-wider text-emerald-400">Akun Pengujian Demo (Dosen / Penguji)</span>
                    <span class="text-2xs text-slate-500 font-mono">Password: password</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-3xs font-bold uppercase text-purple-400">Role Admin</span>
                        <div class="text-white font-semibold mt-0.5">admin@sportcourt.test</div>
                        <div class="text-2xs text-slate-400">Kelola Lapangan & Approval Booking</div>
                    </div>
                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-3xs font-bold uppercase text-emerald-400">Role Penyewa</span>
                        <div class="text-white font-semibold mt-0.5">faris@sportcourt.test</div>
                        <div class="text-2xs text-slate-400">Booking Lapangan & CRUD Tim</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-slate-900/50 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-400">Fitur Sistem</h2>
                <h3 class="text-3xl font-black text-white mt-1">Dirancang Memenuhi Seluruh Ketentuan UTP</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Fitur 1 -->
                <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-6 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Validasi Jadwal Anti-Bentrok</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Sistem cerdas memeriksa benturan jam booking pada lapangan dan tanggal yang sama secara real-time untuk mencegah double booking.
                    </p>
                </div>

                <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-6 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-4 border border-indigo-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">CRUD Data Tim & Komunitas</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Penyewa dapat mengelola klub futsal dan badminton mereka, mencatat kontak kapten, dan langsung menghubungkan tim ke tiket booking.
                    </p>
                </div>

                <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-6 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-4 border border-amber-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Error Handling Bersahabat</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Dilengkapi custom error page (403, 404, 500, 419) dan validasi formulir berbahasa Indonesia tanpa pernah menampilkan debug stack trace.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Katalog Singkat Lapangan -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-400">Pilihan Lapangan</h2>
                    <h3 class="text-2xl font-black text-white mt-0.5">Lapangan Siap Pakai Hari Ini</h3>
                </div>
                <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                    Lihat Seluruh Lapangan &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($courts as $court)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-slate-700 transition flex flex-col justify-between">
                        <div>
                            <x-court-cover :court="$court" height="h-36">
                                <div class="h-full p-4 flex items-start justify-between">
                                    <x-court-badges :court="$court" class="backdrop-blur-sm" />
                                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-bold bg-slate-950/70 text-emerald-400 backdrop-blur-sm">
                                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}<span class="text-slate-400 font-normal">/jam</span>
                                    </span>
                                </div>
                            </x-court-cover>
                            <div class="p-5 pb-0">
                                <h4 class="text-lg font-bold text-white mb-2">{{ $court->name }}</h4>
                                @if ($court->facilities)
                                    <p class="text-xs text-slate-400 line-clamp-2 mb-3">{{ $court->facilities }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="p-5 pt-4">
                            <a href="{{ route('courts.show', $court) }}" class="block text-center py-2 bg-slate-800 hover:bg-slate-750 text-white rounded-xl text-xs font-semibold transition">
                                Cek Jadwal & Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-8 bg-slate-950 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <span class="font-bold text-emerald-400">SportCourt</span> &copy; 2026 • Sistem Booking Lapangan Futsal & Badminton
            </div>
            <div>
                UTP Pemrograman Web Lanjut (PWL) Kelompok 3A • Palwa, Dinda, Achira, Faris
            </div>
        </div>
    </footer>
</body>
</html>
