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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen selection:bg-emerald-500 selection:text-slate-950 flex flex-col justify-between">
        <div class="relative isolate flex-grow flex flex-col justify-center items-center px-4 py-12 overflow-hidden">
            <!-- Ambient Glow -->
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

            <div class="relative z-10 w-full max-w-md">
                <!-- Brand Header -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex flex-col items-center group">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-2xl shadow-xl shadow-emerald-500/25 mb-3 group-hover:scale-105 transition transform">
                            SC
                        </div>
                        <span class="text-2xl font-black tracking-tight text-white">
                            Sport<span class="text-emerald-400">Court</span>
                        </span>
                        <span class="text-3xs font-bold uppercase tracking-widest text-slate-400 mt-0.5">
                            Arena & Sport Center
                        </span>
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-md">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Minimal Footer -->
        <footer class="py-4 text-center text-2xs text-slate-400 border-t border-slate-900 bg-slate-950">
            <div class="max-w-md mx-auto px-4 flex items-center justify-between">
                <span>&copy; 2026 SportCourt Arena</span>
                <a href="/" class="text-emerald-400 hover:text-emerald-300 font-medium transition min-h-[44px] inline-flex items-center">&larr; Kembali ke Beranda</a>
            </div>
        </footer>
    </body>
</html>
