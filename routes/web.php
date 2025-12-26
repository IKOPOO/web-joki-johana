<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

  // ADMIN ONLY ROUTES  
  Route::middleware(['role:LENSIA_ADMIN'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management (Admin only)
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Studio Management (Admin only - CRUD on studios)
    Route::get('/admin/studios', [App\Http\Controllers\Admin\StudioPackageController::class, 'index'])->name('admin.studios.index');
    Route::post('/admin/studios', [App\Http\Controllers\Admin\StudioPackageController::class, 'storeStudio'])->name('admin.studios.store');
    Route::put('/admin/studios/{studio}', [App\Http\Controllers\Admin\StudioPackageController::class, 'updateStudio'])->name('admin.studios.update');
    Route::delete('/admin/studios/{studio}', [App\Http\Controllers\Admin\StudioPackageController::class, 'destroyStudio'])->name('admin.studios.destroy');
  });

  // ADMIN + STAFF ROUTES
  Route::middleware(['role:LENSIA_ADMIN,STUDIO_STAF'])->group(function () {
    // Staff Dashboard
    Route::get('/staff/dashboard', [App\Http\Controllers\Staff\StaffController::class, 'dashboard'])->name('staff.dashboard');

    // Booking Management (filtered by studio for staff)
    Route::get('/admin/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('admin.bookings.index');
    Route::post('/admin/bookings', [App\Http\Controllers\Admin\BookingController::class, 'store'])->name('admin.bookings.store');
    Route::put('/admin/bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/admin/bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('admin.bookings.destroy');

    // Package Management (filtered by studio for staff)
    Route::get('/admin/studios/{studio}/packages', [App\Http\Controllers\Admin\StudioPackageController::class, 'showPackages'])->name('admin.packages.index');
    Route::post('/admin/studios/{studio}/packages', [App\Http\Controllers\Admin\StudioPackageController::class, 'storePackage'])->name('admin.packages.store');
    Route::put('/admin/studios/{studio}/packages/{package}', [App\Http\Controllers\Admin\StudioPackageController::class, 'updatePackage'])->name('admin.packages.update');
    Route::delete('/admin/studios/{studio}/packages/{package}', [App\Http\Controllers\Admin\StudioPackageController::class, 'destroyPackage'])->name('admin.packages.destroy');
  });
});
