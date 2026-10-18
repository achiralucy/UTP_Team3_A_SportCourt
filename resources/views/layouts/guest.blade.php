<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SportCourt') }} - Arena Futsal & Badminton</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{
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
    }" class="font-sans antialiased bg-slate-100 dark:bg-slate-950 text-slate-850 dark:text-slate-100 min-h-screen selection:bg-emerald-500 selection:text-slate-950 flex flex-col justify-between transition-colors duration-200">
        
        <!-- Top Navigation / Theme Switcher -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="/" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-1.5 min-h-[44px]">
                <span>Beranda SportCourt</span>
            </a>
            <button type="button"
                    @click="toggleTheme()"
                    class="p-2 min-h-[40px] min-w-[40px] rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition flex items-center justify-center cursor-pointer shadow-sm"
                    :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'">
                <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="!isDark" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </header>

        <div class="relative isolate flex-grow flex flex-col justify-center items-center px-4 py-8 overflow-hidden">
            <!-- Ambient Glow -->
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

            <div class="relative z-10 w-full max-w-md">
                <!-- Brand Header -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex flex-col items-center group">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-2xl shadow-xl shadow-emerald-500/25 mb-3 group-hover:scale-105 transition transform">
                            SC
                        </div>
                        <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                            Sport<span class="text-emerald-500">Court</span>
                        </span>
                        <span class="text-3xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-0.5">
                            Arena & Sport Center
                        </span>
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl dark:shadow-2xl backdrop-blur-md transition-colors">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Minimal Footer -->
        <footer class="py-4 text-center text-2xs text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-900 bg-white dark:bg-slate-950 transition-colors">
            <div class="max-w-md mx-auto px-4 flex items-center justify-between">
                <span>&copy; 2026 SportCourt Arena</span>
                <a href="/" class="text-emerald-600 dark:text-emerald-400 hover:underline font-medium transition min-h-[44px] inline-flex items-center">Kembali ke Beranda</a>
            </div>
        </footer>
    </body>
</html>
