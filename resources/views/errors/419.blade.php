<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir (419) - SportCourt</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 antialiased">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-2xl p-8 text-center shadow-2xl relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-blue-500/10 rounded-full blur-2xl"></div>
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-blue-500/10 text-blue-400 mb-6 border border-blue-500/20">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-400 bg-blue-500/10 border border-blue-500/20 rounded-full mb-3">
            Error 419 • Page Expired
        </span>
        <h1 class="text-2xl font-bold text-white mb-2">Sesi Telah Berakhir</h1>
        <p class="text-slate-400 text-sm mb-6 leading-relaxed">
            Halaman ini sudah tidak aktif karena terlalu lama tidak ada interaksi. Silakan muat ulang atau masuk kembali.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:location.reload()" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium transition">
                Muat Ulang Halaman
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium shadow-lg shadow-emerald-600/30 transition">
                Login Kembali
            </a>
        </div>
    </div>
</body>
</html>
