<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SportCourt - Arena Booking Lapangan Futsal & Badminton Premium</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Theme Initialization (Prevent FOUC) -->
    <script>
        if (localStorage.theme === 'light') {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased selection:bg-emerald-500 selection:text-slate-950 transition-colors duration-200">

    <!-- Header Navigation -->
    <header x-data="{
        isDark: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.theme = 'light';
            }
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
        }
    }" class="border-b border-slate-200 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md sticky top-0 z-40 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-emerald-500/25">
                    SC
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                        Sport<span class="text-emerald-500">Court</span>
                    </span>
                    <span class="block text-3xs font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400/90">Arena & Sport Center</span>
                </div>
            </a>

            <nav class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button type="button"
                        @click="toggleTheme()"
                        class="p-2 min-h-[40px] min-w-[40px] rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition flex items-center justify-center cursor-pointer shadow-sm"
                        :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'">
                    <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="!isDark" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <a href="{{ route('courts.index') }}" class="hidden sm:inline-flex min-h-[44px] items-center text-sm font-medium text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white px-3 py-2 rounded-xl transition">
                    Katalog Lapangan
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="min-h-[44px] px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-md shadow-emerald-600/30 transition inline-flex items-center justify-center">
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="min-h-[44px] px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-slate-950 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition inline-flex items-center justify-center">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="min-h-[44px] px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-md shadow-emerald-600/25 transition inline-flex items-center justify-center">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative isolate overflow-hidden pt-16 pb-20">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex flex-wrap items-center justify-center gap-2 px-4 py-2 rounded-full bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-300 text-xs font-semibold mb-6 max-w-full">
                <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                <span>Arena Futsal & Badminton • Buka Setiap Hari 06:00 - 23:00 WIB</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-slate-900 dark:text-white max-w-4xl mx-auto leading-tight">
                Pesan Lapangan Olahraga Cepat, Tepat, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-500 to-cyan-500 dark:from-emerald-400 dark:via-teal-300 dark:to-cyan-400">Bebas Bentrok</span>.
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Platform reservasi resmi SportCourt Arena. Nikmati fasilitas lapangan berstandar turnamen, pengecekan jadwal terverifikasi, dan kemudahan booking untuk komunitas Anda.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-4">
                <a href="{{ route('courts.index') }}" class="min-h-[44px] w-full sm:w-auto px-8 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-base shadow-lg shadow-emerald-600/30 transition inline-flex items-center justify-center">
                    Lihat Lapangan & Jadwal
                </a>
                <a href="{{ route('register') }}" class="min-h-[44px] w-full sm:w-auto px-8 py-3 rounded-2xl bg-white hover:bg-slate-100 text-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100 border border-slate-200 dark:border-slate-700 font-semibold text-base shadow-sm transition inline-flex items-center justify-center">
                    Daftar Akun Penyewa
                </a>
            </div>

            <div class="mt-14 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
                <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white">06:00 - 23:00</div>
                        <div class="text-xs text-slate-500 dark:text-slate-300">Buka Setiap Hari</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-600 dark:text-teal-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white">Bebas Bentrok</div>
                        <div class="text-xs text-slate-500 dark:text-slate-300">Validasi Slot Otomatis</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white">Standar Turnamen</div>
                        <div class="text-xs text-slate-500 dark:text-slate-300">Vinyl BWF & Interlock</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-3 shadow-sm transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white">Komunitas Olahraga</div>
                        <div class="text-xs text-slate-500 dark:text-slate-300">Manajemen Jadwal Tim</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-slate-100/60 dark:bg-slate-900/60 border-y border-slate-200 dark:border-slate-800/80 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Keunggulan Venue</h2>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-1">Fasilitas & Standar Kelas Turnamen SportCourt Arena</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-2">Dikelola secara profesional dengan infrastruktur olahraga berkualitas tinggi untuk pengalaman bermain terbaik Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 rounded-2xl p-6 shadow-sm hover:border-emerald-500/40 transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Jadwal Terverifikasi & Bebas Bentrok</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Pengecekan otomatis memastikan setiap pemesanan tidak bertabrakan dengan jadwal penyewa lain. Slot yang Anda pilih terkonfirmasi secara pasti.
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 rounded-2xl p-6 shadow-sm hover:border-teal-500/40 transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 border border-teal-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Fasilitas Standar Turnamen</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Lantai Karpet Vinyl BWF untuk badminton dan Interlock High-Grip untuk futsal, dilengkapi penerangan LED High-Bay anti-silau berstandar kompetisi nasional.
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 rounded-2xl p-6 shadow-sm hover:border-indigo-500/40 transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 border border-indigo-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Layanan Komunitas & Sparring</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Daftarkan klub atau tim olahraga Anda untuk kemudahan booking rutin mingguan, pencatatan jadwal tanding, dan koordinasi antar-pemain dalam satu akun.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Pilihan Lapangan</h2>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">Lapangan Siap Pakai Hari Ini</h3>
                </div>
                <a href="{{ route('courts.index') }}" class="min-h-[44px] text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center">
                    Lihat Seluruh Lapangan
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($courts as $court)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden hover:border-emerald-500/40 dark:hover:border-slate-700 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <x-court-cover :court="$court">
                                <div class="flex items-start justify-between">
                                    <x-court-badges :court="$court" class="backdrop-blur-sm" />
                                    <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-black/60 dark:bg-slate-950/80 text-white dark:text-emerald-300 border border-white/20 dark:border-emerald-500/20 backdrop-blur-sm">
                                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}<span class="text-slate-200 dark:text-slate-300 font-normal">/jam</span>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-white">{{ $court->name }}</h4>
                                </div>
                            </x-court-cover>
                            <div class="p-5">
                                @if ($court->facilities)
                                    <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">{{ $court->facilities }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a href="{{ route('courts.show', $court) }}" class="min-h-[44px] w-full text-center py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100 rounded-xl text-xs font-semibold transition inline-flex items-center justify-center">
                                Cek Jadwal & Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 bg-slate-100/40 dark:bg-slate-900/40 border-t border-slate-200 dark:border-slate-800/80 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Lokasi & Fasilitas Gedung</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">SportCourt Arena Sport Center</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-3 leading-relaxed">
                        Arena olahraga terpadu yang dirancang nyaman untuk pemain dan penonton. Terletak di lokasi strategis dengan akses mudah dan area parkir yang luas.
                    </p>

                    <div class="grid grid-cols-2 gap-3 mt-6 text-xs text-slate-700 dark:text-slate-200">
                        <div class="flex items-center gap-2 p-2.5 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span> Parkir Mobil & Motor Luas
                        </div>
                        <div class="flex items-center gap-2 p-2.5 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span> Ruang Ganti & Toilet Bersih
                        </div>
                        <div class="flex items-center gap-2 p-2.5 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span> Kantin & Minuman Dingin
                        </div>
                        <div class="flex items-center gap-2 p-2.5 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span> Musholla Nyaman Ber-AC
                        </div>
                        <div class="flex items-center gap-2 p-2.5 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span> Free Wi-Fi High-Speed
                        </div>
                        <div class="flex items-center gap-2 p-2.5 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span> Sewa Raket & Rompi Tim
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-5 shadow-lg transition-colors">
                    <h4 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Informasi Kunjungan & Reservasi
                    </h4>

                    <div class="space-y-3.5 text-xs text-slate-700 dark:text-slate-200">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-2xs">Alamat Gedung</span>
                            <div class="text-slate-900 dark:text-slate-100 font-medium mt-0.5">Komp. Gelanggang Olahraga SportCourt, Jl. Utama Arena No. 88</div>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-2xs">Jam Operasional</span>
                            <div class="text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">Senin - Minggu: 06:00 - 23:00 WIB (Tanpa Libur)</div>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-2xs">Layanan Pelanggan & WhatsApp</span>
                            <div class="text-slate-900 dark:text-slate-100 font-mono mt-0.5">+62 821-7788-9900 (Resepsionis & Booking)</div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('courts.index') }}" class="min-h-[44px] w-full text-center py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-600/25 transition inline-flex items-center justify-center">
                            Cek Ketersediaan & Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-slate-900 py-8 bg-white dark:bg-slate-950 text-xs text-slate-500 dark:text-slate-400 transition-colors">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <span class="font-bold text-emerald-600 dark:text-emerald-400">SportCourt Arena</span> &copy; 2026 • Sistem Booking Lapangan Futsal & Badminton. All rights reserved.
            </div>
            <div class="text-slate-500 dark:text-slate-400 font-medium">
                Pusat Olahraga Futsal & Badminton Modern
            </div>
        </div>
    </footer>
</body>
</html>
