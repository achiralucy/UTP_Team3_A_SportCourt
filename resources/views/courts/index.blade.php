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

        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <form method="GET" action="{{ route('courts.index') }}" class="flex flex-col sm:flex-row gap-3">

                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lapangan, fasilitas..."
                           class="w-full min-h-[44px] px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <select name="category" class="w-full sm:w-auto min-h-[44px] px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        <option value="all">Semua Kategori</option>
                        <option value="futsal" {{ request('category') === 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="badminton" {{ request('category') === 'badminton' ? 'selected' : '' }}>Badminton</option>
                    </select>
                </div>

                <div>
                    <select name="status" class="w-full sm:w-auto min-h-[44px] px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        <option value="all">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia (Ready)</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <div>
                    <select name="sort" class="w-full sm:w-auto min-h-[44px] px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                        <option value="name" @selected(request('sort') === 'name')>Nama A-Z</option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>Harga Terendah</option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>Harga Tertinggi</option>
                    </select>
                </div>

                <button type="submit" class="min-h-[44px] px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold transition shadow-md shadow-emerald-600/20 inline-flex items-center justify-center">
                    Terapkan
                </button>

                @if (request()->hasAny(['search', 'category', 'status', 'sort']))
                    <a href="{{ route('courts.index') }}" class="min-h-[44px] px-4 py-2.5 bg-slate-700 hover:bg-slate-650 text-slate-200 rounded-xl text-xs transition inline-flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if ($courts->isEmpty())
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-12 text-center text-slate-300">
                Tidak ada lapangan yang sesuai dengan pencarian atau filter yang dipilih.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($courts as $court)
                    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl overflow-hidden shadow-sm hover:border-emerald-500/40 hover:shadow-xl transition flex flex-col justify-between group">
                        <div>
                            <x-court-cover :court="$court">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-1 text-2xs font-extrabold uppercase rounded-lg tracking-wider border {{ $court->category == 'futsal' ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' : 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' }}">
                                        {{ $court->category }}
                                    </span>
                                    <x-court-status :court="$court" class="backdrop-blur-sm" />
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white group-hover:text-emerald-300 transition">{{ $court->name }}</h3>
                                    <div class="text-xs text-slate-200 font-semibold mt-1">
                                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-slate-300 font-normal">/ jam</span>
                                    </div>
                                </div>
                            </x-court-cover>

                            <div class="p-5 space-y-3">
                                @if ($court->facilities)
                                    <div>
                                        <span class="text-2xs font-bold uppercase tracking-wider text-slate-300">Fasilitas Utama:</span>
                                        <p class="text-xs text-slate-300 mt-1 line-clamp-2">
                                            {{ $court->facilities }}
                                        </p>
                                    </div>
                                @endif

                                @if ($court->description)
                                    <p class="text-xs text-slate-300 line-clamp-2 pt-2 border-t border-slate-700/60">
                                        {{ $court->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="p-5 pt-0 flex items-center justify-between gap-3">
                            <a href="{{ route('courts.show', $court) }}" class="min-h-[44px] text-xs font-semibold text-slate-200 hover:text-white px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-650 transition inline-flex items-center justify-center">
                                Jadwal & Detail
                            </a>

                            @if ($court->status === 'available')
                                <a href="{{ route('bookings.create', ['court_id' => $court->id]) }}" class="min-h-[44px] px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-600/30 transition inline-flex items-center justify-center">
                                    Sewa Lapangan 
                                </a>
                            @else
                                <span class="min-h-[44px] text-xs font-medium text-slate-400 px-4 py-2.5 bg-slate-900 rounded-xl inline-flex items-center justify-center">
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