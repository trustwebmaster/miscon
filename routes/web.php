<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Registration API Routes
Route::prefix('api/registration')->name('registration.')->group(function () {
    Route::post('/', [RegistrationController::class, 'store'])->name('store');
    Route::post('/payment', [RegistrationController::class, 'processPayment'])->name('payment');
    Route::get('/status/{reference}', [RegistrationController::class, 'status'])->name('status');
    Route::get('/stats', [RegistrationController::class, 'stats'])->name('stats');
});

require __DIR__.'/auth.php';
