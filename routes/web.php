<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

// ================== AUTH ================== //
// Tampilkan form login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// Logout harus POST
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ================== DASHBOARD DEFAULT ================== //
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// ================== ADMIN ================== //
Route::prefix('admin')->middleware('auth')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Users
    Route::resource('users', UserController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Items
    Route::resource('items', ItemController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Transactions (hanya lihat data)
    Route::resource('transactions', TransactionController::class);
});

// ================== STAFF ================== //
Route::prefix('staff')->middleware('auth')->group(function () {
    // Dashboard staff
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

    // Transactions (staff bisa CRUD transaksi)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('staff.transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('staff.transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('staff.transactions.store');
});
