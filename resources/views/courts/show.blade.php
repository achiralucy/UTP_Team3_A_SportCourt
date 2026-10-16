<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">{{ $court->name }}</h2>
                <p class="text-xs text-slate-400 mt-1">Spesifikasi lapangan dan jadwal yang sudah terisi</p>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.courts.edit', $court) }}" class="text-xs font-semibold text-amber-300 hover:text-white px-3 py-2 bg-amber-500/10 hover:bg-amber-500/20 rounded-xl border border-amber-500/30 transition">
                            Edit Lapangan
                        </a>
                    @endif
                @endauth
                <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
                    <x-court-cover :court="$court" height="h-56 sm:h-72">
                        <div class="h-full p-5 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <x-court-badges :court="$court" class="backdrop-blur-sm" />
                                <x-court-status :court="$court" class="backdrop-blur-sm" />
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-black text-white drop-shadow">{{ $court->name }}</h3>
                        </div>
                    </x-court-cover>

                    <div class="p-6 sm:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-slate-700">
                            <span class="text-xs text-slate-400">Harga sewa</span>
                            <div class="text-2xl font-black text-emerald-400">
                                Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}
                                <span class="text-xs text-slate-400 font-normal">/ jam</span>
                            </div>
                        </div>

                        <div class="py-6 space-y-6">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Fasilitas</h4>
                                @if (count($court->facility_list))
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($court->facility_list as $facility)
                                            <span class="px-3 py-1 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-200 flex items-center gap-1.5">
                                                <span class="text-emerald-400">✓</span> {{ $facility }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-slate-500 mt-2">Belum ada data fasilitas.</p>
                                @endif
                            </div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi</h4>
                                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                                    {{ $court->description ?: 'Belum ada deskripsi untuk lapangan ini.' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-700 flex flex-wrap items-center justify-between gap-3">
                            <span class="text-xs text-slate-400">Ditambahkan {{ $court->created_at->translatedFormat('d F Y') }}</span>

                            @if ($court->isAvailable())
                                @auth
                                    @if (Auth::user()->isPenyewa())
                                        <a href="{{ route('bookings.create', ['court_id' => $court->id]) }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                                            Booking Lapangan Ini &rarr;
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                                        Masuk untuk Booking &rarr;
                                    </a>
                                @endauth
                            @else
                                <span class="text-xs font-medium text-slate-500 px-3 py-2 bg-slate-900 rounded-xl">Tidak dapat dibooking saat ini</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-700/80">
                        <h4 class="font-bold text-white text-sm">Jadwal Terisi</h4>
                        <p class="text-2xs text-slate-400 mt-0.5">Booking menunggu dan disetujui, mulai hari ini</p>
                    </div>

                    @if ($court->bookings->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-400">
                            Belum ada jadwal yang terisi. Lapangan masih kosong.
                        </div>
                    @else
                        <div class="divide-y divide-slate-700/60 p-2 max-h-[28rem] overflow-y-auto">
                            @foreach ($court->bookings as $cb)
                                <div class="p-3 text-xs">
                                    <div class="font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($cb->booking_date)->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="text-emerald-400 font-mono mt-0.5">
                                        {{ substr($cb->start_time, 0, 5) }} - {{ substr($cb->end_time, 0, 5) }} WIB
                                    </div>
                                    <div class="mt-1">
                                        @if ($cb->status === 'approved')
                                            <span class="text-2xs px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Disetujui</span>
                                        @else
                                            <span class="text-2xs px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">Menunggu</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>