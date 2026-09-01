<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PhotoController as AdminPhotoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GalleryController;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Potret Diri (Self-Photo Booth Management)
|--------------------------------------------------------------------------
*/

// --- OPENING / SPLASH SCREEN & HALAMAN UTAMA ---
Route::get('/', function () {
    return view('splash');
})->name('splash');

Route::get('/home', function () {
    $packages = Package::where('is_active', true)->get();
    return view('welcome', compact('packages'));
})->name('home');

// --- AUTENTIKASI ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PENDAFTARAN ADMIN BARU / ONBOARDING (5 TAHAP) ---
Route::get('/register', [\App\Http\Controllers\OnboardingController::class, 'step1'])->name('register');
Route::post('/onboarding/step-1', [\App\Http\Controllers\OnboardingController::class, 'postStep1'])->name('onboarding.postStep1');
Route::get('/onboarding/step-2', [\App\Http\Controllers\OnboardingController::class, 'step2'])->name('onboarding.step2');
Route::post('/onboarding/step-2', [\App\Http\Controllers\OnboardingController::class, 'postStep2'])->name('onboarding.postStep2');
Route::get('/onboarding/step-3', [\App\Http\Controllers\OnboardingController::class, 'step3'])->name('onboarding.step3');
Route::post('/onboarding/step-3', [\App\Http\Controllers\OnboardingController::class, 'postStep3'])->name('onboarding.postStep3');
Route::get('/onboarding/success', [\App\Http\Controllers\OnboardingController::class, 'success'])->name('onboarding.success');
Route::get('/onboarding/set-pin', [\App\Http\Controllers\OnboardingController::class, 'setPin'])->name('onboarding.setPin');
Route::post('/onboarding/set-pin', [\App\Http\Controllers\OnboardingController::class, 'postSetPin'])->name('onboarding.postSetPin');

// --- PEMESANAN SESI FOTO (PELANGGAN) ---
Route::get('/book', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/book/success/{booking_code}', [BookingController::class, 'success'])->name('bookings.success');
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->middleware('auth')->name('bookings.my');

// --- SESI PHOTO BOOTH & FRAME KOLASE 6 FOTO ---
Route::get('/booth', [\App\Http\Controllers\BoothController::class, 'index'])->name('booth.index');
Route::get('/booth/start/template', [\App\Http\Controllers\BoothController::class, 'selectTemplate'])->name('booth.start.template');
Route::post('/booth/start/template', [\App\Http\Controllers\BoothController::class, 'postTemplate'])->name('booth.start.postTemplate');
Route::get('/booth/start/copies', [\App\Http\Controllers\BoothController::class, 'selectCopies'])->name('booth.start.copies');
Route::post('/booth/start/copies', [\App\Http\Controllers\BoothController::class, 'postCopies'])->name('booth.start.postCopies');
Route::get('/booth/start/payment', [\App\Http\Controllers\BoothController::class, 'paymentQris'])->name('booth.start.payment');
Route::post('/booth/start/payment/confirm', [\App\Http\Controllers\BoothController::class, 'confirmPayment'])->name('booth.start.confirmPayment');
Route::get('/booth/start/success', [\App\Http\Controllers\BoothController::class, 'paymentSuccess'])->name('booth.start.success');
Route::post('/booth/search', [\App\Http\Controllers\BoothController::class, 'search'])->name('booth.search');
Route::get('/booth/{booking_code}', [\App\Http\Controllers\BoothController::class, 'session'])->name('booth.session');
Route::post('/booth/{booking_code}/save', [\App\Http\Controllers\BoothController::class, 'saveSession'])->name('booth.save');

// --- AKSES GALERI & DOWNLOAD HASIL SESI ---
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery/search', [GalleryController::class, 'search'])->name('gallery.search');
Route::get('/gallery/{booking_code}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/gallery/{booking_code}/download-zip', [GalleryController::class, 'downloadZip'])->name('gallery.downloadZip');

// --- PANEL ADMINISTRATOR (Auth + Role Admin) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // 5 Halaman Utama Studio Sesuai Desain
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/kiosk/status', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updateKioskStatus'])->name('kiosk.status');
    Route::get('/session-control', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'sessionControl'])->name('session-control');
    Route::post('/session-control', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updateSessionControl'])->name('session-control.update');
    Route::get('/gallery', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'gallery'])->name('gallery');
    Route::get('/qris', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'qris'])->name('qris');
    Route::post('/qris', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updateQris'])->name('qris.update');
    Route::get('/templates', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'templates'])->name('templates');
    Route::get('/status', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'status'])->name('status');

    // Manajemen Reservasi
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Manajemen Paket Foto
    Route::resource('packages', AdminPackageController::class);

    // Manajemen Upload & Hapus Foto
    Route::post('/bookings/{booking}/photos', [AdminPhotoController::class, 'store'])->name('photos.store');
    Route::delete('/photos/{photo}', [AdminPhotoController::class, 'destroy'])->name('photos.destroy');
});
