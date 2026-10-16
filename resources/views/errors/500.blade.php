<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kendala Sistem (500) - SportCourt</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 antialiased">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-2xl p-8 text-center shadow-2xl relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-rose-500/10 rounded-full blur-2xl"></div>
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-rose-500/10 text-rose-400 mb-6 border border-rose-500/20">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-full mb-3">
            Error 500 • Server Error
        </span>
        <h1 class="text-2xl font-bold text-white mb-2">Terjadi Kendala Teknis</h1>
        <p class="text-slate-400 text-sm mb-6 leading-relaxed">
            Sistem kami sedang mengalami sedikit kendala dalam memproses permintaan. Tim kami sedang menanganinya.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:history.back()" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium transition">
                Kembali
            </a>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium shadow-lg shadow-emerald-600/30 transition">
                Ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
