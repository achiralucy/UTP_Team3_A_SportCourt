<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Tampilkan seluruh data booking untuk Admin dengan filter status & pencarian.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'court', 'team'])->latest();

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal booking
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Filter berdasarkan kategori lapangan
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('court', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Pencarian nama penyewa, tim, atau nama lapangan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('court', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                })->orWhereHas('team', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $bookings = $query->paginate(12)->withQueryString();

        // Ringkasan status untuk tab/badge
        $counts = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'counts'));
    }

    /**
     * Tampilkan detail booking spesifik.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'court', 'team']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Setujui (Approve) permohonan booking.
     */
    public function approve(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:255',
        ]);

        // Cek apakah jadwal sudah lewat dari waktu saat ini
        $bookingDateTime = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->start_time);
        if ($bookingDateTime->isPast()) {
            return back()->with('error', 'Gagal menyetujui: Jadwal pertandingan ini sudah lewat waktu. Silakan tolak booking dan proses pengembalian dana (refund).');
        }

        // Cek kembali apakah ada booking lain yang bertabrakan sebelum disetujui
        $conflict = Booking::where('id', '!=', $booking->id)
            ->where('court_id', $booking->court_id)
            ->where('booking_date', $booking->booking_date->toDateString())
            ->where('status', 'approved')
            ->where(function ($query) use ($booking) {
                $query->where('start_time', '<', $booking->end_time)
                      ->where('end_time', '>', $booking->start_time);
            })->exists();

        if ($conflict) {
            return back()->with('error', 'Gagal menyetujui: Sudah ada jadwal booking lain yang disetujui pada jam dan lapangan tersebut!');
        }

        $booking->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes ?? 'Kesiapan lapangan telah dikonfirmasi oleh pengelola venue. Selamat bermain!',
        ]);

        return back()->with('success', "Booking #{$booking->id} berhasil dikonfirmasi. Lapangan siap digunakan oleh penyewa.");
    }

    /**
     * Tolak (Reject) permohonan booking.
     */
    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:255',
        ], [
            'admin_notes.required' => 'Wajib memberikan alasan penolakan booking untuk penyewa.',
        ]);

        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Booking #{$booking->id} telah ditolak. Alasan dan proses refund telah dicatat.");
    }

    /**
     * Hapus data booking dari sistem.
     */
    public function destroy(Booking $booking)
    {
        $bookingId = $booking->id;
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', "Data booking #{$bookingId} berhasil dihapus.");
    }
}
