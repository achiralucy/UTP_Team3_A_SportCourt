<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Katalog Lapangan Olahraga
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Temukan lapangan futsal dan badminton terbaik dengan fasilitas berstandar turnamen
                </p>
            </div>
            @auth
                @if (Auth::user()->isPenyewa())
                    <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/25 transition">
                        + Sewa Lapangan
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter Bar -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('courts.index') }}" class="flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lapangan, fasilitas..."
                           class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Kategori -->
                <div>
                    <select name="category" class="w-full sm:w-auto px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        <option value="all">Semua Kategori</option>
                        <option value="futsal" {{ request('category') === 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="badminton" {{ request('category') === 'badminton' ? 'selected' : '' }}>Badminton</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <select name="status" class="w-full sm:w-auto px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        <option value="all">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia (Ready)</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold transition">
                    Terapkan
                </button>

                @if (request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('courts.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-650 text-slate-300 rounded-xl text-xs transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Courts Grid -->
        @if ($courts->isEmpty())
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-12 text-center text-slate-400">
                Belum ada data lapangan yang sesuai kriteria pencarian Anda.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($courts as $court)
                    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl overflow-hidden shadow-sm hover:border-emerald-500/40 hover:shadow-xl transition flex flex-col justify-between group">
                        <div>
                            <!-- Visual Banner -->
                            <div class="h-44 bg-gradient-to-tr {{ $court->category == 'futsal' ? 'from-blue-900 via-indigo-950 to-slate-900' : 'from-emerald-900 via-teal-950 to-slate-900' }} p-5 flex flex-col justify-between relative overflow-hidden">
                                <div class="flex items-center justify-between z-10">
                                    <span class="px-2.5 py-1 text-2xs font-extrabold uppercase rounded-lg tracking-wider {{ $court->category == 'futsal' ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-emerald-500 text-slate-950 shadow-lg shadow-emerald-500/30' }}">
                                        {{ $court->category }}
                                    </span>

                                    @if ($court->status === 'available')
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                            ● Siap Pakai
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">
                                            ● Maintenance
                                        </span>
                                    @endif
                                </div>

                                <div class="z-10">
                                    <h3 class="text-xl font-black text-white group-hover:text-emerald-300 transition">{{ $court->name }}</h3>
                                    <div class="text-xs text-slate-300 font-medium mt-1">
                                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-slate-400">/ jam</span>
                                    </div>
                                </div>

                                <!-- Background Decorative Icon -->
                                <div class="absolute -right-4 -bottom-4 text-white/5 pointer-events-none">
                                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Body Info -->
                            <div class="p-5 space-y-3">
                                @if ($court->facilities)
                                    <div>
                                        <span class="text-2xs font-bold uppercase tracking-wider text-slate-400">Fasilitas Utama:</span>
                                        <p class="text-xs text-slate-300 mt-1 line-clamp-2">
                                            {{ $court->facilities }}
                                        </p>
                                    </div>
                                @endif

                                @if ($court->description)
                                    <p class="text-xs text-slate-400 line-clamp-2 pt-2 border-t border-slate-700/60">
                                        {{ $court->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-5 pt-0 flex items-center justify-between gap-3">
                            <a href="{{ route('courts.show', $court) }}" class="text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 rounded-xl bg-slate-700 hover:bg-slate-650 transition">
                                Jadwal & Detail
                            </a>

                            @if ($court->status === 'available')
                                <a href="{{ route('bookings.create', ['court_id' => $court->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-emerald-600/30 transition">
                                    Sewa Lapangan &rarr;
                                </a>
                            @else
                                <span class="text-xs font-medium text-slate-500 px-3 py-2 bg-slate-900 rounded-xl">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $courts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
