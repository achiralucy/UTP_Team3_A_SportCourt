<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

// Public Home & Courts
Route::get('/', function () {
    $courts = \App\Models\Court::where('status', 'available')->take(6)->get();
    return view('welcome', compact('courts'));
})->name('home');

Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

// Universal Dashboard Route with Role Redirect
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('penyewa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Admin Court Management (CRUD Lapangan)
    Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create');
    Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
    Route::get('/courts/{court}/edit', [CourtController::class, 'edit'])->name('courts.edit');
    Route::put('/courts/{court}', [CourtController::class, 'update'])->name('courts.update');
    Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');

    // Admin Booking Management (Approval & Status Management)
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
});

// Penyewa Dashboard (Role: penyewa)
Route::middleware(['auth', 'role:penyewa'])->prefix('penyewa')->name('penyewa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'penyewa'])->name('dashboard');
});

// Authenticated Routes for both Roles (Teams & Bookings)
Route::middleware('auth')->group(function () {
    // CRUD Tim / Komunitas (Fitur Faris)
    Route::resource('teams', TeamController::class);

    // CRUD Booking Lapangan (Fitur Dinda & Alur Booking)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

require __DIR__.'/auth.php';