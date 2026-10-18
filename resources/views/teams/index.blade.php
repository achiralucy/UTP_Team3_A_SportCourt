<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">
                    Data Tim & Komunitas Olahraga
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ Auth::user()->isAdmin() ? 'Daftar seluruh tim/komunitas penyewa yang terdaftar di SportCourt' : 'Daftar tim dan komunitas olahraga milikmu' }}
                </p>
            </div>
            <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Tim Baru
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Search Bar -->
        <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 shadow-sm transition-colors">
            <form method="GET" action="{{ route('teams.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tim, nama kapten..."
                           class="w-full pl-10 pr-4 py-2 min-h-[44px] bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>
                <button type="submit" class="px-5 py-2 min-h-[44px] inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white rounded-xl text-sm font-medium transition cursor-pointer">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('teams.index') }}" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-slate-800 dark:hover:bg-slate-750 dark:text-slate-400 text-sm rounded-xl transition inline-flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Teams Grid / Table -->
        @if ($teams->isEmpty())
            <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-12 text-center transition-colors">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-4 border border-indigo-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum Ada Data Tim</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto mt-1 mb-6">
                    Daftarkan tim futsal atau badminton untuk mempermudah reservasi jadwal tanding bersama komunitasmu.
                </p>
                <a href="{{ route('teams.create') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition">
                    + Daftarkan Tim Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($teams as $team)
                    <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm hover:border-emerald-500/40 dark:hover:border-slate-600 transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 text-2xs font-bold uppercase rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 mb-1">
                                        Komunitas Aktif
                                    </span>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $team->name }}</h3>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $team->members_count }} Pemain
                                </span>
                            </div>

                            <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-300 my-3">
                                <div class="flex items-center">
                                    <span class="text-slate-400 w-24">Kapten:</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $team->captain_name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-slate-400 w-24">WhatsApp:</span>
                                    <span class="font-mono text-emerald-600 dark:text-emerald-400">{{ $team->captain_phone }}</span>
                                </div>
                                @if (Auth::user()->isAdmin())
                                    <div class="flex items-center">
                                        <span class="text-slate-400 w-24">Pemilik:</span>
                                        <span class="text-slate-700 dark:text-slate-300">{{ $team->user->name }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <span class="text-slate-400 w-24">Total Booking:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $team->bookings_count }} kali</span>
                                </div>
                            </div>

                            @if ($team->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-2 pt-2 border-t border-slate-100 dark:border-slate-700/50">
                                    {{ $team->description }}
                                </p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700/80 flex items-center justify-between gap-2 mt-4">
                            <a href="{{ route('teams.show', $team) }}" class="text-xs font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3 py-2 min-h-[44px] inline-flex items-center rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 transition">
                                Detail
                            </a>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('teams.edit', $team) }}" class="text-xs font-medium text-amber-700 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 px-3 py-2 min-h-[44px] inline-flex items-center rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 transition">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('teams.destroy', $team) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tim ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-rose-700 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 px-3 py-2 min-h-[44px] inline-flex items-center rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $teams->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
