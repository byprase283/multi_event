<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\ParticipantController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Event detail
Route::get('/event/{event}', [HomeController::class, 'show'])->name('event.show');

// Registration
Route::get('/register/{event}', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/register/success/{participant}', [RegistrationController::class, 'success'])->name('registration.success');

// Voucher check API
Route::post('/api/check-voucher', [RegistrationController::class, 'checkVoucher'])->name('api.check-voucher');

// Ticket verification
Route::get('/ticket/{token}', [RegistrationController::class, 'verifyTicket'])->name('ticket.verify');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events CRUD
    Route::resource('events', EventController::class);

    // Vouchers CRUD
    Route::resource('vouchers', VoucherController::class);

    // Participants
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{participant}', [ParticipantController::class, 'show'])->name('participants.show');
    Route::post('/participants/{participant}/validate', [ParticipantController::class, 'validate'])->name('participants.validate');
    Route::post('/participants/{participant}/invalidate', [ParticipantController::class, 'invalidate'])->name('participants.invalidate');
    Route::get('/participants/{participant}/send-ticket', [ParticipantController::class, 'sendTicket'])->name('participants.send-ticket');
    Route::post('/participants/{participant}/redeem', [ParticipantController::class, 'redeem'])->name('participants.redeem');
    Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy'])->name('participants.destroy');

    // Scanner
    Route::get('/scan', [App\Http\Controllers\Admin\ScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scan/process', [App\Http\Controllers\Admin\ScannerController::class, 'process'])->name('scanner.process');
});
