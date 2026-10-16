<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourtRequest;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtController extends Controller
{
    public function index(Request $request)
    {
        $sorts = [
            'latest' => ['created_at', 'desc'],
            'name' => ['name', 'asc'],
            'price_low' => ['price_per_hour', 'asc'],
            'price_high' => ['price_per_hour', 'desc'],
        ];

        [$column, $direction] = $sorts[$request->input('sort')] ?? $sorts['latest'];

        $courts = Court::query()
            ->search($request->input('search'))
            ->category($request->input('category'))
            ->status($request->input('status'))
            ->orderBy($column, $direction)
            ->paginate(9)
            ->withQueryString();

        if (Auth::check() && Auth::user()->isAdmin()) {
            $stats = [
                'total' => Court::count(),
                'available' => Court::where('status', 'available')->count(),
                'maintenance' => Court::where('status', 'maintenance')->count(),
                'futsal' => Court::where('category', 'futsal')->count(),
                'badminton' => Court::where('category', 'badminton')->count(),
            ];

            return view('courts.admin-index', compact('courts', 'stats'));
        }

        return view('courts.index', compact('courts'));
    }

    public function create()
    {
        return view('courts.create');
    }

    public function store(CourtRequest $request)
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        $court = Court::create($data);

        return redirect()
            ->route('courts.show', $court)
            ->with('success', "Lapangan {$court->name} berhasil ditambahkan.");
    }

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

    public function edit(Court $court)
    {
        return view('courts.edit', compact('court'));
    }

    public function update(CourtRequest $request, Court $court)
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        if ($request->hasFile('image')) {
            $court->deleteImage();
            $data['image'] = $request->file('image')->store('courts', 'public');
        } elseif ($request->boolean('remove_image')) {
            $court->deleteImage();
            $data['image'] = null;
        }

        $court->update($data);

        $message = "Data lapangan {$court->name} berhasil diperbarui.";

        if ($court->status === 'maintenance' && $court->hasActiveBookings()) {
            $message .= ' Perhatian: masih ada booking aktif pada lapangan ini yang perlu ditinjau.';
        }

        return redirect()->route('courts.index')->with('success', $message);
    }

    public function destroy(Court $court)
    {
        if ($court->hasActiveBookings()) {
            return redirect()
                ->route('courts.index')
                ->with('error', 'Lapangan tidak dapat dihapus karena masih memiliki booking aktif. Tolak atau batalkan booking tersebut terlebih dahulu.');
        }

        $name = $court->name;
        $court->deleteImage();
        $court->delete();

        return redirect()->route('courts.index')->with('success', "Lapangan {$name} berhasil dihapus.");
    }
}