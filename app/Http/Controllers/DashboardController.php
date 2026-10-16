<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk role Admin.
     */
    public function admin()
    {
        $stats = [
            'total_courts' => Court::count(),
            'available_courts' => Court::where('status', 'available')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
            'total_teams' => Team::count(),
            'total_penyewa' => User::where('role', 'penyewa')->count(),
        ];

        // 5 booking pending terbaru yang butuh persetujuan segera
        $pendingBookings = Booking::where('status', 'pending')
            ->with(['user', 'court', 'team'])
            ->latest()
            ->take(5)
            ->get();

        // Jadwal booking hari ini & mendatang yang disetujui
        $upcomingBookings = Booking::where('status', 'approved')
            ->where('booking_date', '>=', now()->toDateString())
            ->with(['user', 'court', 'team'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingBookings', 'upcomingBookings'));
    }

    /**
     * Dashboard untuk role Penyewa.
     */
    public function penyewa()
    {
        $user = Auth::user();

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_bookings' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved_bookings' => Booking::where('user_id', $user->id)->where('status', 'approved')->count(),
            'my_teams' => Team::where('user_id', $user->id)->count(),
        ];

        // Jadwal main berikutnya yang sudah disetujui
        $nextBooking = Booking::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('booking_date', '>=', now()->toDateString())
            ->with(['court', 'team'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->first();

        // Riwayat 5 booking terakhir
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['court', 'team'])
            ->latest()
            ->take(5)
            ->get();

        // Tim milik pengguna
        $myTeams = Team::where('user_id', $user->id)->latest()->take(3)->get();

        return view('penyewa.dashboard', compact('stats', 'nextBooking', 'recentBookings', 'myTeams'));
    }
}
