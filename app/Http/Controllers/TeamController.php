<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Tampilkan daftar tim.
     * Penyewa hanya melihat tim miliknya, Admin dapat melihat seluruh tim.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $query = Team::with('user')->withCount('bookings');
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('captain_name', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            }
            $teams = $query->latest()->paginate(10);
        } else {
            $query = Team::where('user_id', $user->id)->withCount('bookings');
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('captain_name', 'like', "%{$search}%");
                });
            }
            $teams = $query->latest()->paginate(10);
        }

        return view('teams.index', compact('teams'));
    }

    /**
     * Tampilkan form pembuatan tim baru.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Simpan tim baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'captain_name' => 'required|string|max:100',
            'captain_phone' => 'required|string|max:20',
            'members_count' => 'required|integer|min:2|max:50',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama tim atau komunitas wajib diisi.',
            'name.max' => 'Nama tim maksimal 100 karakter.',
            'captain_name.required' => 'Nama kapten tim wajib diisi.',
            'captain_phone.required' => 'Nomor kontak kapten wajib diisi.',
            'members_count.required' => 'Jumlah anggota wajib diisi.',
            'members_count.min' => 'Jumlah anggota tim minimal 2 orang.',
            'members_count.max' => 'Jumlah anggota tim maksimal 50 orang.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
        ]);

        $validated['user_id'] = Auth::id();

        Team::create($validated);

        return redirect()->route('teams.index')->with('success', 'Data tim/komunitas berhasil didaftarkan!');
    }

    /**
     * Tampilkan detail tim beserta riwayat bookingnya.
     */
    public function show(Team $team)
    {
        $user = Auth::user();

        // Penyewa hanya bisa melihat detail tim miliknya
        if (!$user->isAdmin() && $team->user_id !== $user->id) {
            abort(403);
        }

        $team->load(['user', 'bookings.court']);

        return view('teams.show', compact('team'));
    }

    /**
     * Tampilkan form edit data tim.
     */
    public function edit(Team $team)
    {
        $user = Auth::user();

        // Hanya pemilik tim atau admin yang boleh mengedit
        if (!$user->isAdmin() && $team->user_id !== $user->id) {
            abort(403);
        }

        return view('teams.edit', compact('team'));
    }

    /**
     * Perbarui data tim di database.
     */
    public function update(Request $request, Team $team)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $team->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'captain_name' => 'required|string|max:100',
            'captain_phone' => 'required|string|max:20',
            'members_count' => 'required|integer|min:2|max:50',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama tim atau komunitas wajib diisi.',
            'captain_name.required' => 'Nama kapten tim wajib diisi.',
            'captain_phone.required' => 'Nomor kontak kapten wajib diisi.',
            'members_count.required' => 'Jumlah anggota wajib diisi.',
            'members_count.min' => 'Jumlah anggota tim minimal 2 orang.',
        ]);

        $team->update($validated);

        return redirect()->route('teams.index')->with('success', 'Data tim berhasil diperbarui!');
    }

    /**
     * Hapus data tim dari database.
     */
    public function destroy(Team $team)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $team->user_id !== $user->id) {
            abort(403);
        }

        // Cek jika tim memiliki booking aktif yang pending/approved
        $hasActiveBookings = $team->bookings()->whereIn('status', ['pending', 'approved'])->exists();

        if ($hasActiveBookings) {
            return redirect()->route('teams.index')->with('error', 'Tim tidak dapat dihapus karena masih memiliki jadwal booking yang aktif!');
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Data tim berhasil dihapus!');
    }
}
