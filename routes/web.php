<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profil (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Anggota
    Route::post('/events/{event}/hadir', [AttendanceController::class, 'hadir'])
        ->middleware('role:anggota')
        ->name('attendances.hadir');

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('events', EventController::class)->except('show');
        Route::patch('events/{event}/toggle', [EventController::class, 'toggle'])
            ->name('events.toggle');
        Route::get('events/{event}/attendances', [AttendanceController::class, 'index'])
            ->name('events.attendances');
    });
});

require __DIR__.'/auth.php';