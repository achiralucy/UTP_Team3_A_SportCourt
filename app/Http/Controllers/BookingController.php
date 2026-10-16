<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Tampilkan riwayat booking milik penyewa yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Booking::where('user_id', $user->id)->with(['court', 'team'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Tampilkan form pembuatan booking baru.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $courts = Court::where('status', 'available')->get();
        $teams = Team::where('user_id', $user->id)->get();
        $selectedCourtId = $request->query('court_id');

        return view('bookings.create', compact('courts', 'teams', 'selectedCourtId'));
    }

    /**
     * Simpan pemesanan lapangan baru dengan validasi jadwal bentrok.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'team_id' => 'nullable|exists:teams,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'court_id.required' => 'Pilih lapangan yang ingin disewa.',
            'court_id.exists' => 'Lapangan yang dipilih tidak valid.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'start_time.required' => 'Jam mulai sewa wajib diisi.',
            'end_time.required' => 'Jam selesai sewa wajib diisi.',
            'end_time.after' => 'Jam selesai harus lebih lama dari jam mulai.',
        ]);

        $court = Court::findOrFail($validated['court_id']);

        if ($court->status !== 'available') {
            return back()->with('error', 'Lapangan ini sedang dalam pemeliharaan (maintenance) dan belum bisa disewa.')->withInput();
        }

        // Jika user memilih team_id, pastikan tim tersebut memang miliknya
        if (!empty($validated['team_id'])) {
            $team = Team::where('id', $validated['team_id'])->where('user_id', $user->id)->first();
            if (!$team) {
                return back()->with('error', 'Tim yang dipilih tidak valid.')->withInput();
            }
        }

        // CEK BENTROK JADWAL (CONFLICT CHECK)
        // Cek apakah ada booking aktif (pending atau approved) di lapangan dan tanggal yang sama dengan waktu bertubrukan
        $conflict = Booking::where('court_id', $validated['court_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            })->exists();

        if ($conflict) {
            return back()->with('error', 'Jadwal Bentrok! Lapangan ini sudah dibooking pada tanggal dan rentang jam tersebut. Silakan pilih waktu lain.')->withInput();
        }

        // Hitung total harga berdasarkan durasi jam
        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        $durationHours = max(1, ceil($start->floatDiffInHours($end)));
        $totalPrice = $durationHours * $court->price_per_hour;

        Booking::create([
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

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diajukan! Menunggu persetujuan admin.');
    }

    /**
     * Tampilkan detail booking penyewa.
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->load(['court', 'team', 'user']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Tampilkan form edit jadwal booking (hanya jika status pending).
     */
    public function edit(Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')->with('error', 'Jadwal booking yang sudah disetujui, ditolak, atau dibatalkan tidak dapat diubah.');
        }

        $courts = Court::where('status', 'available')->get();
        $teams = Team::where('user_id', $user->id)->get();

        return view('bookings.edit', compact('booking', 'courts', 'teams'));
    }

    /**
     * Perbarui jadwal booking penyewa.
     */
    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')->with('error', 'Booking ini tidak dapat diubah.');
        }

        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'team_id' => 'nullable|exists:teams,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'court_id.required' => 'Pilih lapangan yang ingin disewa.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'start_time.required' => 'Jam mulai sewa wajib diisi.',
            'end_time.required' => 'Jam selesai sewa wajib diisi.',
            'end_time.after' => 'Jam selesai harus lebih lama dari jam mulai.',
        ]);

        $court = Court::findOrFail($validated['court_id']);

        // CEK BENTROK KECUALI BOOKING INI SENDIRI
        $conflict = Booking::where('id', '!=', $booking->id)
            ->where('court_id', $validated['court_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            })->exists();

        if ($conflict) {
            return back()->with('error', 'Jadwal Bentrok! Lapangan ini sudah dibooking pada tanggal dan rentang jam tersebut.')->withInput();
        }

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        $durationHours = max(1, ceil($start->floatDiffInHours($end)));
        $totalPrice = $durationHours * $court->price_per_hour;

        $booking->update([
            'court_id' => $court->id,
            'team_id' => $validated['team_id'] ?? null,
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Jadwal booking berhasil diperbarui!');
    }

    /**
     * Batalkan booking oleh penyewa.
     */
    public function cancel(Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            return back()->with('error', 'Booking ini sudah tidak aktif.');
        }

        $booking->update([
            'status' => 'cancelled',
            'admin_notes' => 'Dibatalkan oleh pemesan.',
        ]);

        return back()->with('success', "Booking #{$booking->id} berhasil dibatalkan.");
    }
}
