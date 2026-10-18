<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white tracking-tight">{{ $court->name }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Spesifikasi lapangan dan jadwal yang sudah terisi</p>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.courts.edit', $court) }}" class="min-h-[44px] text-xs font-semibold text-amber-600 dark:text-amber-300 hover:underline px-4 py-2.5 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-500/30 transition inline-flex items-center">
                            Edit Lapangan
                        </a>
                    @endif
                @endauth
                <a href="{{ route('courts.index') }}" class="min-h-[44px] text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 rounded-xl border border-slate-200 dark:border-slate-700 transition inline-flex items-center">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden transition-colors">
                    <x-court-cover :court="$court" aspect="aspect-video sm:aspect-[21/9]">
                        <div class="flex items-center justify-between">
                            <x-court-badges :court="$court" class="backdrop-blur-sm" />
                            <x-court-status :court="$court" class="backdrop-blur-sm" />
                        </div>
                        <div>
                            <span class="text-2xs font-bold uppercase tracking-wider text-emerald-400">SportCourt Arena Standard</span>
                            <h3 class="text-2xl sm:text-3xl font-bold text-white drop-shadow mt-0.5">{{ $court->name }}</h3>
                        </div>
                    </x-court-cover>

                    <div class="p-6 sm:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-xs text-slate-600 dark:text-slate-300 font-medium">Harga Sewa Resmi</span>
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}
                                <span class="text-xs text-slate-500 dark:text-slate-300 font-normal">/ jam</span>
                            </div>
                        </div>

                        <div class="py-6 space-y-6">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-3">Spesifikasi Teknis Lapangan</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-750 rounded-xl transition-colors">
                                        <div class="text-2xs text-slate-500 dark:text-slate-400 uppercase font-semibold">Jenis Lantai</div>
                                        <div class="text-xs font-semibold text-slate-800 dark:text-slate-100 mt-1">
                                            {{ $court->category === 'futsal' ? 'Interlock Futsal FIFA' : 'Vinyl Mat BWF Certified' }}
                                        </div>
                                    </div>
                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-750 rounded-xl transition-colors">
                                        <div class="text-2xs text-slate-500 dark:text-slate-400 uppercase font-semibold">Dimensi Lapangan</div>
                                        <div class="text-xs font-semibold text-slate-800 dark:text-slate-100 mt-1">
                                            {{ $court->category === 'futsal' ? '25 x 15 Meter' : '13.4 x 6.1 Meter' }}
                                        </div>
                                    </div>
                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-750 rounded-xl transition-colors">
                                        <div class="text-2xs text-slate-500 dark:text-slate-400 uppercase font-semibold">Kapasitas Main</div>
                                        <div class="text-xs font-semibold text-slate-800 dark:text-slate-100 mt-1">
                                            {{ $court->category === 'futsal' ? '10 Pemain (5 vs 5)' : '2 - 4 Pemain' }}
                                        </div>
                                    </div>
                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-750 rounded-xl transition-colors">
                                        <div class="text-2xs text-slate-500 dark:text-slate-400 uppercase font-semibold">Pencahayaan</div>
                                        <div class="text-xs font-semibold text-slate-800 dark:text-slate-100 mt-1">
                                            LED High-Bay Anti-Silau
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Fasilitas Pendukung</h4>
                                @if (count($court->facility_list))
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($court->facility_list as $facility)
                                            <span class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                                                <span class="text-emerald-600 dark:text-emerald-400">✓</span> {{ $facility }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Fasilitas umum arena tersedia di lokasi.</p>
                                @endif
                            </div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Deskripsi & Catatan Lapangan</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-200 mt-2 leading-relaxed">
                                    {{ $court->description ?: 'Lapangan standar turnamen dengan pemeliharaan rutin setiap hari untuk menjamin kenyamanan bermain.' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-3">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Diperbarui {{ $court->updated_at ? $court->updated_at->translatedFormat('d F Y') : $court->created_at->translatedFormat('d F Y') }}</span>

                            @if ($court->isAvailable())
                                @auth
                                    @if (Auth::user()->isPenyewa())
                                        <a href="{{ route('bookings.create', ['court_id' => $court->id]) }}" class="min-h-[44px] px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-600/30 transition inline-flex items-center justify-center">
                                            Booking Lapangan Ini 
                                        </a>
                                    @elseif (Auth::user()->isAdmin())
                                        <a href="{{ route('admin.courts.edit', $court) }}" class="min-h-[44px] px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-semibold shadow-md shadow-blue-600/30 transition inline-flex items-center justify-center">
                                            Kelola / Edit Lapangan Ini 
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="min-h-[44px] px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-600/30 transition inline-flex items-center justify-center">
                                        Masuk untuk Booking
                                    </a>
                                @endauth
                            @else
                                <span class="min-h-[44px] text-xs font-medium text-slate-500 dark:text-slate-400 px-4 py-2.5 bg-slate-100 dark:bg-slate-900 rounded-xl inline-flex items-center justify-center">Tidak dapat dibooking saat ini</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Jadwal Terisi -->
            <div>
                <div class="bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden transition-colors">
                    <div class="p-5 border-b border-slate-100 dark:border-slate-700/80">
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">Jadwal Terisi</h4>
                        <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">Booking menunggu dan disetujui, mulai hari ini</p>
                    </div>

                    @if ($court->bookings->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-500 dark:text-slate-400">
                            Belum ada jadwal yang terisi. Lapangan masih kosong.
                        </div>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-700/60 p-2 max-h-[28rem] overflow-y-auto">
                            @foreach ($court->bookings as $cb)
                                <div class="p-3 text-xs">
                                    <div class="font-semibold text-slate-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($cb->booking_date)->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="text-emerald-600 dark:text-emerald-400 font-mono mt-0.5">
                                        {{ substr($cb->start_time, 0, 5) }} - {{ substr($cb->end_time, 0, 5) }} WIB
                                    </div>
                                    <div class="mt-1">
                                        @if ($cb->status === 'approved')
                                            <span class="text-2xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30">Disetujui</span>
                                        @else
                                            <span class="text-2xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/30">Menunggu</span>
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