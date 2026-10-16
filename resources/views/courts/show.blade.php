<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    {{ $court->name }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Spesifikasi lapangan dan jadwal ketersediaan penggunaan
                </p>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.courts.edit', $court) }}" class="text-xs font-semibold text-amber-300 hover:text-white px-3 py-2 bg-amber-500/10 hover:bg-amber-500/20 rounded-xl border border-amber-500/30 transition">
                            Edit Lapangan
                        </a>
                    @endif
                @endauth
                <a href="{{ route('courts.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white px-3 py-2 bg-slate-800 rounded-xl border border-slate-700 transition">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Overview Card Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-slate-700">
                        <div>
                            <span class="px-2.5 py-1 text-2xs font-extrabold uppercase rounded {{ $court->category == 'futsal' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                {{ $court->category }}
                            </span>
                            <h3 class="text-2xl font-black text-white mt-2">{{ $court->name }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-400">Tarif Sewa:</span>
                            <div class="text-2xl font-black text-emerald-400">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/ jam</span></div>
                        </div>
                    </div>

                    <div class="py-6 space-y-4">
                        @if ($court->facilities)
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Fasilitas Lengkap</h4>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach (explode(',', $court->facilities) as $facility)
                                        <span class="px-3 py-1 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-200 flex items-center gap-1.5">
                                            <span class="text-emerald-400">✓</span> {{ trim($facility) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($court->description)
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Lapangan</h4>
                                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                                    {{ $court->description }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-6 border-t border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400">Status Operasional:</span>
                            @if ($court->status === 'available')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    ● Tersedia & Siap Digunakan
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                    ● Sedang Perawatan (Maintenance)
                                </span>
                            @endif
                        </div>

                        @if ($court->status === 'available')
                            <a href="{{ route('bookings.create', ['court_id' => $court->id]) }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-emerald-600/30 transition">
                                Booking Lapangan Ini &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Jadwal Terisi -->
            <div>
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-700/80">
                        <h4 class="font-bold text-white text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Jadwal Booking Terkonfirmasi
                        </h4>
                        <p class="text-2xs text-slate-400 mt-0.5">Jadwal pada lapangan ini yang telah dibooking</p>
                    </div>

                    @if ($court->bookings->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-400">
                            Belum ada jadwal booking untuk lapangan ini di masa mendatang. Lapangan bebas disewa kapan saja!
                        </div>
                    @else
                        <div class="divide-y divide-slate-700/60 p-2">
                            @foreach ($court->bookings as $cb)
                                <div class="p-3 text-xs">
                                    <div class="font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($cb->booking_date)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-emerald-400 font-mono mt-0.5">
                                        {{ substr($cb->start_time, 0, 5) }} - {{ substr($cb->end_time, 0, 5) }} WIB
                                    </div>
                                    <div class="text-slate-400 text-2xs mt-1">
                                        Status: <span class="text-slate-300 font-medium">{{ ucfirst($cb->status) }}</span>
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
