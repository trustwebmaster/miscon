<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
    Route::get('/registration/{registration}', [AdminController::class, 'show'])->name('registration.show');
});

// Logout Route for admin panel (non-Livewire)
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// Registration API Routes
Route::prefix('api/registration')->name('registration.')->group(function () {
    Route::post('/', [RegistrationController::class, 'store'])->name('store');
    Route::post('/pay', [RegistrationController::class, 'registerAndPay'])->name('pay'); // Register + pay in one request
    Route::post('/payment', [RegistrationController::class, 'processPayment'])->name('payment');
    Route::post('/payment/poll', [RegistrationController::class, 'pollPaymentStatus'])->name('payment.poll');
    Route::post('/test-email', [RegistrationController::class, 'testEmail'])->name('email.test');
    Route::post('/check', [RegistrationController::class, 'checkByIdNumber'])->name('check');
    Route::get('/status/{reference}', [RegistrationController::class, 'status'])->name('status');
    Route::get('/stats', [RegistrationController::class, 'stats'])->name('stats');
});

// Paynow callback routes
Route::post('/paynow/result', [RegistrationController::class, 'paynowCallback'])->name('paynow.result');
Route::get('/paynow/return', [RegistrationController::class, 'paynowReturn'])->name('paynow.return');

require __DIR__.'/auth.php';
