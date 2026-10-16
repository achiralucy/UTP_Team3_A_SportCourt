<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtController extends Controller
{
    /**
     * Tampilkan daftar seluruh lapangan.
     */
    public function index(Request $request)
    {
        $query = Court::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('facilities', 'like', "%{$search}%");
        }

        $courts = $query->latest()->paginate(9)->withQueryString();

        if (Auth::check() && Auth::user()->isAdmin()) {
            return view('courts.admin-index', compact('courts'));
        }

        return view('courts.index', compact('courts'));
    }

    /**
     * Form tambah lapangan baru (Admin).
     */
    public function create()
    {
        return view('courts.create');
    }

    /**
     * Simpan lapangan baru ke database (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:futsal,badminton',
            'price_per_hour' => 'required|integer|min:10000',
            'status' => 'required|in:available,maintenance',
            'facilities' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama lapangan wajib diisi.',
            'category.required' => 'Kategori olahraga wajib dipilih.',
            'category.in' => 'Kategori harus futsal atau badminton.',
            'price_per_hour.required' => 'Harga sewa per jam wajib diisi.',
            'price_per_hour.min' => 'Harga sewa minimal Rp 10.000.',
            'status.required' => 'Status operasional lapangan wajib dipilih.',
        ]);

        Court::create($validated);

        return redirect()->route('courts.index')->with('success', 'Data lapangan baru berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail lapangan.
     */
    public function show(Court $court)
    {
        $court->load(['bookings' => function ($query) {
            $query->where('booking_date', '>=', now()->toDateString())
                  ->whereIn('status', ['approved', 'pending'])
                  ->orderBy('booking_date')
                  ->orderBy('start_time');
        }]);

        return view('courts.show', compact('court'));
    }

    /**
     * Form edit data lapangan (Admin).
     */
    public function edit(Court $court)
    {
        return view('courts.edit', compact('court'));
    }

    /**
     * Perbarui data lapangan (Admin).
     */
    public function update(Request $request, Court $court)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:futsal,badminton',
            'price_per_hour' => 'required|integer|min:10000',
            'status' => 'required|in:available,maintenance',
            'facilities' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama lapangan wajib diisi.',
            'category.required' => 'Kategori olahraga wajib dipilih.',
            'price_per_hour.required' => 'Harga sewa per jam wajib diisi.',
            'status.required' => 'Status operasional lapangan wajib dipilih.',
        ]);

        $court->update($validated);

        return redirect()->route('courts.index')->with('success', 'Data lapangan berhasil diperbarui!');
    }

    /**
     * Hapus data lapangan (Admin).
     */
    public function destroy(Court $court)
    {
        // Cek jika lapangan masih memiliki jadwal booking aktif
        $hasActiveBookings = $court->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('booking_date', '>=', now()->toDateString())
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->route('courts.index')->with('error', 'Tidak dapat menghapus lapangan: Masih ada jadwal booking aktif di masa mendatang!');
        }

        $court->delete();

        return redirect()->route('courts.index')->with('success', 'Data lapangan berhasil dihapus!');
    }
}
