<?php

use Illuminate\Support\Facades\Route;

// Welcome page
Route::view('/', 'welcome');

// Dashboard - redirect berdasarkan role
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isMekanik()) {
        return redirect()->route('mekanik.dashboard');
    }
    // Default: user biasa → halaman booking customer
    return redirect()->route('user.dashboard');
})->name('dashboard');

// === ADMIN ROUTES ===
Route::middleware(['auth', 'verified', 'isAdmin'])->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/admin/services', 'admin.services')->name('admin.services');
});

// === MEKANIK ROUTES ===
Route::middleware(['auth', 'verified'])->get('/mekanik/dashboard', function () {
    if (!auth()->user()->isMekanik() && !auth()->user()->isAdmin()) {
        return redirect()->route('user.dashboard');
    }
    return view('mekanik.dashboard');
})->name('mekanik.dashboard');

// === USER ROUTES (pelanggan) ===
Route::middleware(['auth', 'verified'])->get('/booking', function () {
    $myBookings = \App\Models\Booking::where('user_id', auth()->id())
        ->with('service')
        ->orderBy('booking_time', 'desc')
        ->limit(5)
        ->get();

    $pendingCount = \App\Models\Booking::where('user_id', auth()->id())
        ->whereIn('status', ['pending', 'processing'])
        ->count();

    return view('user.dashboard', compact('myBookings', 'pendingCount'));
})->name('user.dashboard');

// Profile
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
