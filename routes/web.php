<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WhatsAppWebHookController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// WhatsApp Webhook Routes (must be before auth middleware)
Route::prefix('webhook/whatsapp')->group(function () {
    Route::get('/', [WhatsAppWebHookController::class, 'verify'])->name('whatsapp.verify');
    Route::post('/', [WhatsAppWebHookController::class, 'handle'])->name('whatsapp.handle');
    Route::post('/flow', [WhatsAppWebHookController::class, 'handleFlowEndpoint'])->name('whatsapp.flow');
});

// Simple test route to verify server is accessible
Route::get('/webhook/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Webhook endpoint is accessible',
        'time' => now()->toIso8601String(),
    ]);
});

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

    // Prayer Requests
    Route::get('/prayer-requests', [AdminController::class, 'prayerRequests'])->name('prayer-requests');
    Route::post('/prayer-requests/{prayerRequest}/prayed', [AdminController::class, 'markPrayerRequestPrayed'])->name('prayer-requests.prayed');

    // Guest Speakers
    Route::get('/guest-speakers', [AdminController::class, 'guestSpeakers'])->name('guest-speakers');
    Route::post('/guest-speakers', [AdminController::class, 'storeGuestSpeaker'])->name('guest-speakers.store');
    Route::put('/guest-speakers/{speaker}', [AdminController::class, 'updateGuestSpeaker'])->name('guest-speakers.update');
    Route::delete('/guest-speakers/{speaker}', [AdminController::class, 'deleteGuestSpeaker'])->name('guest-speakers.destroy');

    // Program Schedule
    Route::get('/program-schedule', [AdminController::class, 'programSchedule'])->name('program-schedule');
    Route::post('/program-schedule', [AdminController::class, 'storeSchedule'])->name('program-schedule.store');
    Route::put('/program-schedule/{schedule}', [AdminController::class, 'updateSchedule'])->name('program-schedule.update');
    Route::delete('/program-schedule/{schedule}', [AdminController::class, 'deleteSchedule'])->name('program-schedule.destroy');
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
    Route::post('/pay', [RegistrationController::class, 'registerAndPay'])->name('pay'); // Register + pay in one request (mobile)
    Route::post('/pay-web', [RegistrationController::class, 'registerAndPayWeb'])->name('pay.web'); // Register + pay via Paynow web redirect
    Route::post('/payment', [RegistrationController::class, 'processPayment'])->name('payment');
    Route::post('/payment-web', [RegistrationController::class, 'processPaymentWeb'])->name('payment.web'); // Pay via Paynow web redirect
    Route::post('/payment/poll', [RegistrationController::class, 'pollPaymentStatus'])->name('payment.poll');
    Route::post('/test-email', [RegistrationController::class, 'testEmail'])->name('email.test');
    Route::post('/check', [RegistrationController::class, 'checkByIdNumber'])->name('check');
    Route::get('/status/{reference}', [RegistrationController::class, 'status'])->name('status');
    Route::get('/stats', [RegistrationController::class, 'stats'])->name('stats');
});

// Paynow callback routes
Route::post('/paynow/result', [RegistrationController::class, 'paynowCallback'])->name('paynow.result');
Route::get('/paynow/return', [RegistrationController::class, 'paynowReturn'])->name('paynow.return');

// Donation API Routes
Route::prefix('api/donation')->name('donation.')->group(function () {
    Route::post('/pay', [DonationController::class, 'pay'])->name('pay'); // Mobile payment
    Route::post('/pay-web', [DonationController::class, 'payWeb'])->name('pay.web'); // Web/browser payment
    Route::post('/poll', [DonationController::class, 'pollPaymentStatus'])->name('poll');
    Route::get('/stats', [DonationController::class, 'stats'])->name('stats');
});

// Paynow callback for donations
Route::post('/paynow/donation/result', [DonationController::class, 'paynowCallback'])->name('paynow.donation.result');

require __DIR__.'/auth.php';
