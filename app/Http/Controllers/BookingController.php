<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
   
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.bookings.index');
        }

        $query = Booking::where('user_id', $user->id)->with(['court', 'team']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('court', function ($courtQuery) use ($search) {
                    $courtQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('category', 'like', "%{$search}%");
                })->orWhereHas('team', function ($teamQuery) use ($search) {
                    $teamQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')
                          ->orderBy('start_time', 'desc')
                          ->paginate(9)
                          ->withQueryString();

        $stats = [
            'all' => Booking::where('user_id', $user->id)->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Booking::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Booking::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        $courts = Court::orderBy('name')->get();

        return view('bookings.index', compact('bookings', 'stats', 'courts'));
    }

    public function bookedSlots(Request $request, Court $court)
    {
        $date = $request->query('date', now()->toDateString());
        $excludeId = $request->query('exclude_booking_id');

        $query = Booking::where('court_id', $court->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'approved']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $bookings = $query->orderBy('start_time')->get(['id', 'start_time', 'end_time', 'status', 'team_id']);

        $slots = $bookings->map(function ($b) {
            return [
                'id' => $b->id,
                'start_time' => substr($b->start_time, 0, 5),
                'end_time' => substr($b->end_time, 0, 5),
                'status' => $b->status,
                'status_label' => $b->status === 'approved' ? 'Disetujui' : 'Menunggu',
            ];
        });

        return response()->json([
            'court_id' => $court->id,
            'court_name' => $court->name,
            'date' => $date,
            'operating_hours' => [
                'open' => '06:00',
                'close' => '23:00',
            ],
            'booked_slots' => $slots,
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akun Administrator tidak dapat melakukan pemesanan lapangan. Silakan gunakan akun Penyewa.');
        }

        $courts = Court::where('status', 'available')->get();
        $teams = Team::where('user_id', $user->id)->get();
        $selectedCourtId = $request->query('court_id');
        $selectedDate = $request->query('date', now()->toDateString());

        return view('bookings.create', compact('courts', 'teams', 'selectedCourtId', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akun Administrator tidak diizinkan melakukan pemesanan lapangan. Silakan gunakan akun Penyewa.');
        }

        $maxFutureDate = now()->addDays(30)->toDateString();

        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'team_id' => 'nullable|exists:teams,id',
            'booking_date' => 'required|date|after_or_equal:today|before_or_equal:' . $maxFutureDate,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'court_id.required' => 'Pilih lapangan yang ingin disewa.',
            'court_id.exists' => 'Lapangan yang dipilih tidak valid.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'booking_date.before_or_equal' => 'Pemesanan maksimal dapat dilakukan 30 hari ke depan.',
            'start_time.required' => 'Jam mulai sewa wajib diisi.',
            'start_time.date_format' => 'Format jam mulai tidak valid (HH:mm).',
            'end_time.required' => 'Jam selesai sewa wajib diisi.',
            'end_time.date_format' => 'Format jam selesai tidak valid (HH:mm).',
            'end_time.after' => 'Jam selesai harus lebih lama dari jam mulai.',
        ]);

        $court = Court::findOrFail($validated['court_id']);

        if ($court->status !== 'available') {
            return back()->with('error', 'Lapangan ini sedang dalam pemeliharaan (maintenance) dan belum dapat disewa.')->withInput();
        }

        if (!empty($validated['team_id'])) {
            $team = Team::where('id', $validated['team_id'])->where('user_id', $user->id)->first();
            if (!$team) {
                return back()->with('error', 'Tim komunitas yang dipilih tidak valid atau bukan milik Anda.')->withInput();
            }
        }

        $operationalStart = '06:00';
        $operationalEnd = '23:00';
        if ($validated['start_time'] < $operationalStart || $validated['end_time'] > $operationalEnd) {
            return back()->with('error', "Jam operasional lapangan adalah pukul {$operationalStart} hingga {$operationalEnd} WIB.")->withInput();
        }

        if ($validated['booking_date'] === now()->toDateString()) {
            $currentHourMinute = now()->format('H:i');
            if ($validated['start_time'] <= $currentHourMinute) {
                return back()->with('error', "Jam mulai sewa tidak boleh lebih awal dari waktu saat ini ({$currentHourMinute} WIB).")->withInput();
            }
        }

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        $diffMinutes = $start->diffInMinutes($end);

        if ($diffMinutes < 60) {
            return back()->with('error', 'Durasi sewa minimal adalah 1 jam (60 menit).')->withInput();
        }

        if ($diffMinutes > 360) {
            return back()->with('error', 'Durasi sewa maksimal adalah 6 jam per pemesanan.')->withInput();
        }

        $durationHours = max(1, (int) ceil($start->floatDiffInHours($end)));
        $totalPrice = $durationHours * $court->price_per_hour;

        $conflictMessage = null;

        $booking = DB::transaction(function () use ($validated, $user, $court, $totalPrice, &$conflictMessage) {
            $conflict = Booking::where('court_id', $validated['court_id'])
                ->whereDate('booking_date', $validated['booking_date'])
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($validated) {
                    $query->where('start_time', '<', $validated['end_time'])
                          ->where('end_time', '>', $validated['start_time']);
                })
                ->lockForUpdate()
                ->first();

            if ($conflict) {
                $conflictStart = substr($conflict->start_time, 0, 5);
                $conflictEnd = substr($conflict->end_time, 0, 5);
                $conflictStatus = $conflict->status === 'approved' ? 'Telah Disetujui' : 'Menunggu Konfirmasi';
                $conflictMessage = "Jadwal Bentrok! Lapangan sudah terisi pada rentang waktu {$conflictStart} - {$conflictEnd} WIB ({$conflictStatus}). Silakan tentukan jam lain.";
                return null;
            }

            return Booking::create([
                'user_id' => $user->id,
                'court_id' => $court->id,
                'team_id' => $validated['team_id'] ?? null,
                'booking_date' => $validated['booking_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'admin_notes' => null,
            ]);
        });

        if (!$booking) {
            return back()->with('error', $conflictMessage ?? 'Jadwal Bentrok! Lapangan sudah terisi pada rentang waktu tersebut. Silakan tentukan jam lain.')->withInput();
        }

        return redirect()->route('bookings.index')->with('success', "Pembayaran online terverifikasi lunas! Booking #{$booking->id} untuk {$court->name} berhasil dipesan dan sedang menunggu konfirmasi kesiapan lapangan.");
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->load(['court', 'team', 'user']);

        $start = Carbon::createFromFormat('H:i', substr($booking->start_time, 0, 5));
        $end = Carbon::createFromFormat('H:i', substr($booking->end_time, 0, 5));
        $durationHours = max(1, (int) ceil($start->floatDiffInHours($end)));

        $bookingDateTime = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->end_time);
        $isPast = $bookingDateTime->isPast();

        return view('bookings.show', compact('booking', 'durationHours', 'isPast'));
    }

    public function edit(Booking $booking)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.bookings.show', $booking);
        }

        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')->with('error', 'Jadwal booking yang sudah disetujui, ditolak, atau dibatalkan tidak dapat diubah.');
        }

        $courts = Court::where('status', 'available')->get();
        $teams = Team::where('user_id', $user->id)->get();

        return view('bookings.edit', compact('booking', 'courts', 'teams'));
    }

    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.bookings.show', $booking);
        }

        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')->with('error', 'Booking ini sudah tidak dapat diubah.');
        }

        $maxFutureDate = now()->addDays(30)->toDateString();

        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'team_id' => 'nullable|exists:teams,id',
            'booking_date' => 'required|date|after_or_equal:today|before_or_equal:' . $maxFutureDate,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'court_id.required' => 'Pilih lapangan yang ingin disewa.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'booking_date.before_or_equal' => 'Pemesanan maksimal dapat dilakukan 30 hari ke depan.',
            'start_time.required' => 'Jam mulai sewa wajib diisi.',
            'end_time.required' => 'Jam selesai sewa wajib diisi.',
            'end_time.after' => 'Jam selesai harus lebih lama dari jam mulai.',
        ]);

        $court = Court::findOrFail($validated['court_id']);

        if ($court->status !== 'available') {
            return back()->with('error', 'Lapangan ini sedang dalam pemeliharaan (maintenance) dan belum dapat disewa.')->withInput();
        }

        if (!empty($validated['team_id'])) {
            $team = Team::where('id', $validated['team_id'])->where('user_id', $user->id)->first();
            if (!$team) {
                return back()->with('error', 'Tim komunitas yang dipilih tidak valid.')->withInput();
            }
        }

        $operationalStart = '06:00';
        $operationalEnd = '23:00';
        if ($validated['start_time'] < $operationalStart || $validated['end_time'] > $operationalEnd) {
            return back()->with('error', "Jam operasional lapangan adalah pukul {$operationalStart} hingga {$operationalEnd} WIB.")->withInput();
        }

        if ($validated['booking_date'] === now()->toDateString()) {
            $currentHourMinute = now()->format('H:i');
            if ($validated['start_time'] <= $currentHourMinute) {
                return back()->with('error', "Jam mulai sewa tidak boleh lebih awal dari waktu saat ini ({$currentHourMinute} WIB).")->withInput();
            }
        }

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        $diffMinutes = $start->diffInMinutes($end);

        if ($diffMinutes < 60) {
            return back()->with('error', 'Durasi sewa minimal adalah 1 jam (60 menit).')->withInput();
        }

        if ($diffMinutes > 360) {
            return back()->with('error', 'Durasi sewa maksimal adalah 6 jam per pemesanan.')->withInput();
        }

        $conflict = Booking::where('id', '!=', $booking->id)
            ->where('court_id', $validated['court_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            })->first();

        if ($conflict) {
            $conflictStart = substr($conflict->start_time, 0, 5);
            $conflictEnd = substr($conflict->end_time, 0, 5);
            $conflictStatus = $conflict->status === 'approved' ? 'Telah Disetujui' : 'Menunggu Konfirmasi';
            return back()->with('error', "Jadwal Bentrok! Lapangan sudah terisi pada rentang waktu {$conflictStart} - {$conflictEnd} WIB ({$conflictStatus}). Silakan tentukan jam lain.")->withInput();
        }

        $durationHours = max(1, (int) ceil($start->floatDiffInHours($end)));
        $totalPrice = $durationHours * $court->price_per_hour;

        $booking->update([
            'court_id' => $court->id,
            'team_id' => $validated['team_id'] ?? null,
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('bookings.index')->with('success', "Jadwal booking #{$booking->id} berhasil diperbarui!");
    }

    public function cancel(Request $request, Booking $booking)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.bookings.show', $booking);
        }

        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            return back()->with('error', 'Booking ini sudah tidak aktif.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ], [
            'reason.max' => 'Alasan pembatalan maksimal 255 karakter.',
        ]);

        $bookingDateTime = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->start_time);
        if ($bookingDateTime->isPast()) {
            return back()->with('error', 'Jadwal pertandingan sudah dimulai atau telah lewat, sehingga booking tidak dapat dibatalkan.');
        }

        $reason = $request->input('reason');
        $cancellationText = 'Dibatalkan oleh pemesan' . ($reason ? ' (Alasan: ' . trim($reason) . ')' : '.');
        $updatedNotes = $booking->admin_notes ? $booking->admin_notes . "\n[" . $cancellationText . ']' : $cancellationText;

        $booking->update([
            'status' => 'cancelled',
            'admin_notes' => $updatedNotes,
        ]);

        return back()->with('success', "Booking #{$booking->id} berhasil dibatalkan.");
    }
}