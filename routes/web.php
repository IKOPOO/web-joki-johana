<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudioPackageController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\OperationalController;
use App\Http\Controllers\Staff\SessionSlotController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;



// --- Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Authentication Routes ---
Route::controller(AuthController::class)->group(function () {
  Route::get('/login', 'showLoginForm')->name('login');
  Route::post('/login', 'login')->name('login.post');
  Route::get('/register', 'showRegisterForm')->name('register');
  Route::post('/register', 'register');
  Route::post('/logout', 'logout')->name('logout');
});

// --- Authenticated Routes ---
Route::middleware(['auth'])->group(function () {

  // ==========================================
  // ADMIN ROUTES (Lensia Admin ONLY)
  // ==========================================
  Route::middleware(['role:LENSIA_ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    // Studio Management
    Route::controller(StudioPackageController::class)->prefix('studios')->name('studios.')->group(function () {
      Route::get('/', 'index')->name('index');
      Route::post('/', 'storeStudio')->name('store');
      Route::put('/{studio}', 'updateStudio')->name('update');
      Route::delete('/{studio}', 'destroyStudio')->name('destroy');
    });
  });

  // ==========================================
  // SHARED ADMIN & STAFF ROUTES
  // ==========================================
  Route::middleware(['role:LENSIA_ADMIN,STUDIO_STAF'])->group(function () {

    // --- Staff Specific ---
    Route::prefix('staff')->name('staff.')->controller(StaffController::class)->group(function () {
      Route::get('/dashboard', 'dashboard')->name('dashboard');
      Route::get('/studio/preview', 'preview')->name('studio.preview');
      Route::put('/studio', 'updateStudio')->name('studio.update');
    });

    // --- Booking Management (Admin & Staff) ---
    Route::prefix('admin')->name('admin.')->group(function () {
      Route::get('verification', [BookingController::class, 'verificationIndex'])->name('bookings.verification');

      Route::controller(BookingController::class)->prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{booking}', 'update')->name('update');
        Route::delete('/{booking}', 'destroy')->name('destroy');

        // Payment Actions
        Route::post('/{booking}/verify-payment', 'verifyPayment')->name('verify');
        Route::post('/{booking}/reject-payment', 'rejectPayment')->name('reject');
      });

      // --- Package Management ---
      Route::controller(StudioPackageController::class)->prefix('studios/{studio}/packages')->name('packages.')->group(function () {
        Route::get('/', 'showPackages')->name('index');
        Route::post('/', 'storePackage')->name('store');
        Route::put('/{package}', 'updatePackage')->name('update');
        Route::delete('/{package}', 'destroyPackage')->name('destroy');
      });
    });

    // --- Operational Hours ---
    Route::controller(OperationalController::class)->prefix('operational')->name('staff.operational.')->group(function () {
      Route::get('/', 'index')->name('index');
      Route::post('/', 'update')->name('update');
    });

    // --- Session Slots ---
    Route::controller(SessionSlotController::class)->prefix('session-slots')->name('staff.session-slots.')->group(function () {
      Route::get('/', 'index')->name('index');
      Route::post('/', 'store')->name('store');
      Route::post('/generate', 'generate')->name('generate');
      Route::post('/reset', 'reset')->name('reset');
      Route::put('/{sessionSlot}', 'update')->name('update');
      Route::delete('/{sessionSlot}', 'destroy')->name('destroy');
      Route::post('/{sessionSlot}/toggle', 'toggleStatus')->name('toggle');
    });
  });

  // ==========================================
  // CUSTOMER ROUTES
  // ==========================================
  Route::middleware(['role:CUSTOMER,LENSIA_ADMIN,STUDIO_STAF'])->group(function () {

    // Profile & History
    Route::get('/profile', [CustomerBookingController::class, 'profile'])->name('customer.profile');
    Route::get('/my-reservations', [CustomerBookingController::class, 'history'])->name('customer.reservations.index');

    // Booking Process
    Route::controller(CustomerBookingController::class)->group(function () {
      Route::get('/booking', 'index')->name('customer.booking.index');
      Route::get('/get-slots', 'getSlots')->name('customer.booking.slots'); // Dynamic slots

      Route::prefix('booking')->name('customer.booking.')->group(function () {
        Route::post('/', 'store')->name('store');
        Route::get('/{studio}', 'create')->name('create'); // Step 1: Form

        // Specific Booking Actions
        Route::prefix('{booking}')->group(function () {
          Route::get('/payment', 'payment')->name('payment');
          Route::post('/pay', 'processPayment')->name('pay');
          Route::get('/print', 'print')->name('print');
        });
      });
    });
  });

});
