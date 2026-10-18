<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Kelola Seluruh Booking Lapangan
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Pusat persetujuan, penolakan, dan pemantauan transaksi reservasi pengguna
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-300">
                    Total: <strong class="text-white">{{ $counts['total'] }}</strong> data booking
                </span>
            </div>
        </div>
    </x-slot>

    <div x-data="{ rejectModalOpen: false, rejectAction: '', rejectBookingTitle: '' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap gap-2 border-b border-slate-800 pb-3">
            <a href="{{ route('admin.bookings.index') }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ !request('status') || request('status') === 'all' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                Semua ({{ $counts['total'] }})
            </a>
            <a href="{{ route('admin.bookings.index', array_merge(request()->query(), ['status' => 'pending'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'bg-slate-800 text-amber-400 hover:bg-slate-700' }}">
                Menunggu Kesiapan ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.bookings.index', array_merge(request()->query(), ['status' => 'approved'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-800 text-emerald-400 hover:bg-slate-700' }}">
                Disetujui ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.bookings.index', array_merge(request()->query(), ['status' => 'rejected'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-800 text-rose-400 hover:bg-slate-700' }}">
                Ditolak ({{ $counts['rejected'] }})
            </a>
            <a href="{{ route('admin.bookings.index', array_merge(request()->query(), ['status' => 'cancelled'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ request('status') === 'cancelled' ? 'bg-slate-700 text-white shadow-md' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">
                Dibatalkan ({{ $counts['cancelled'] }})
            </a>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <!-- Search -->
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penyewa, tim, lapangan..."
                           class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Kategori Lapangan -->
                <div>
                    <select name="category" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        <option value="all">Semua Kategori</option>
                        <option value="futsal" {{ request('category') === 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="badminton" {{ request('category') === 'badminton' ? 'selected' : '' }}>Badminton</option>
                    </select>
                </div>

                <!-- Tanggal Booking -->
                <div>
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-650 text-slate-300 rounded-xl text-xs transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Booking Table -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
            @if ($bookings->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <p class="text-sm font-medium text-slate-300">Tidak ada data booking yang sesuai filter.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-900/60 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700">
                                <th class="py-3.5 px-4 font-semibold">ID / Pemesan</th>
                                <th class="py-3.5 px-4 font-semibold">Lapangan</th>
                                <th class="py-3.5 px-4 font-semibold">Jadwal Main</th>
                                <th class="py-3.5 px-4 font-semibold">Total Biaya</th>
                                <th class="py-3.5 px-4 font-semibold">Status</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Persetujuan & Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/60">
                            @foreach ($bookings as $b)
                                <tr class="hover:bg-slate-750 transition">
                                    <!-- ID / Pemesan -->
                                    <td class="py-3.5 px-4">
                                        <div class="text-xs font-mono text-emerald-400">#{{ $b->id }}</div>
                                        <div class="font-bold text-white">{{ $b->user->name }}</div>
                                        <div class="text-xs text-slate-400">
                                            @if ($b->team)
                                                Tim: <span class="text-indigo-400 font-semibold">{{ $b->team->name }}</span>
                                            @else
                                                <span class="italic text-slate-500">Perorangan</span>
                                            @endif
                                            • {{ $b->user->phone ?? '-' }}
                                        </div>
                                    </td>

                                    <!-- Lapangan -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-slate-200">{{ $b->court->name }}</div>
                                        <span class="inline-block px-2 py-0.5 text-2xs font-bold rounded uppercase mt-0.5 {{ $b->court->category == 'futsal' ? 'bg-blue-500/20 text-blue-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                                            {{ $b->court->category }}
                                        </span>
                                    </td>

                                    <!-- Jadwal Main -->
                                    <td class="py-3.5 px-4 text-slate-300">
                                        <div class="font-medium text-white">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d M Y') }}</div>
                                        <div class="text-xs font-mono text-slate-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</div>
                                    </td>

                                    <!-- Total Biaya & Status Bayar -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-white">Rp {{ number_format($b->total_price, 0, ',', '.') }}</div>
                                        @if (in_array($b->status, ['pending', 'approved']))
                                            <span class="inline-flex items-center gap-1 text-3xs font-semibold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20 mt-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Lunas Online
                                            </span>
                                        @elseif ($b->status === 'rejected')
                                            <span class="inline-flex items-center gap-1 text-3xs font-semibold text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20 mt-1">
                                                Refund
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4">
                                        @if ($b->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                ✓ Siap Main
                                            </span>
                                        @elseif ($b->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse">
                                                ⏳ Cek Kesiapan
                                            </span>
                                        @elseif ($b->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                ✕ Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-700 text-slate-400">
                                                Dibatalkan
                                            </span>
                                        @endif

                                        @if ($b->admin_notes)
                                            <div class="text-2xs text-slate-400 mt-1 max-w-xs italic line-clamp-1" title="{{ $b->admin_notes }}">
                                                "{{ $b->admin_notes }}"
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if ($b->status === 'pending')
                                                <!-- Tombol Setujui Langsung -->
                                                <form method="POST" action="{{ route('admin.bookings.approve', $b) }}">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold transition" title="Konfirmasi Kesiapan Lapangan & ACC">
                                                        ACC Lapangan
                                                    </button>
                                                </form>

                                                <!-- Tombol Tolak Cepat (Buka Modal) -->
                                                <button type="button"
                                                        @click="rejectAction = '{{ route('admin.bookings.reject', $b) }}'; rejectBookingTitle = '#{{ $b->id }} - {{ addslashes($b->user->name) }} ({{ addslashes($b->court->name) }})'; rejectModalOpen = true;"
                                                        class="px-2.5 py-1 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 rounded-lg text-xs font-semibold transition"
                                                        title="Tolak Jadwal & Refund">
                                                    Tolak
                                                </button>
                                            @endif

                                            <a href="{{ route('admin.bookings.show', $b) }}" class="px-2.5 py-1 bg-slate-700 hover:bg-slate-650 text-slate-200 rounded-lg text-xs font-medium transition">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-700">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Penolakan / Refund Cepat Admin -->
        <div x-show="rejectModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="rejectModalOpen = false"
                 class="bg-slate-800 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
                <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="text-sm font-bold text-white">Tolak Booking & Refund Dana</h3>
                    </div>
                    <button @click="rejectModalOpen = false" class="text-slate-400 hover:text-white text-lg">&times;</button>
                </div>

                <form :action="rejectAction" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <div class="text-2xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pemesanan Yang Ditolak:</div>
                        <div class="font-mono text-xs text-rose-300 bg-slate-900/80 p-2.5 rounded-xl border border-slate-750 font-bold" x-text="rejectBookingTitle"></div>
                    </div>

                    <div>
                        <label for="reject_reason" class="block text-2xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                            Alasan Penolakan / Pengembalian Dana <span class="text-rose-400">*</span>
                        </label>
                        <textarea id="reject_reason" name="admin_notes" rows="3" required
                                  placeholder="Contoh: Lapangan sedang dalam perbaikan darurat / Bentrok acara resmi pemda"
                                  class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-rose-500 placeholder-slate-500"></textarea>
                        <p class="text-3xs text-slate-400 mt-1">
                            Catatan ini akan dikirimkan kepada penyewa dan tertera di e-tiket sebagai bukti penerusan refund online.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button type="button" @click="rejectModalOpen = false"
                                class="flex-1 py-2 bg-slate-700 hover:bg-slate-650 text-slate-200 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-rose-600/30 transition">
                            ✕ Tolak & Refund
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
