<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes (Not Authenticated)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout Routes (tanpa middleware auth agar tidak stuck)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Redirect root to appropriate dashboard based on role
    Route::get('/', function () {
        $user = auth()->user();

        switch ($user->roles) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard');
            case 'cashier':
                return redirect()->route('cashier.dashboard');
            case 'storage':
                return redirect()->route('storage.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('home');
});

// Superadmin Routes
Route::prefix('superadmin')
    ->middleware(['auth', 'role:superadmin'])
    ->name('superadmin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [SuperadminController::class, 'dashboard'])->name('dashboard');
        Route::get('/stock', [SuperadminController::class, 'stock'])->name('stock');

        // Sales History per Store
        Route::get('/sales-history/{store}', [SuperadminController::class, 'salesHistory'])->name('sales.history');

        // Employee CRUD
        Route::get('/employees', [SuperadminController::class, 'employeeIndex'])->name('employees.index');
        Route::get('/employees/create', [SuperadminController::class, 'employeeCreate'])->name('employees.create');
        Route::post('/employees', [SuperadminController::class, 'employeeStore'])->name('employees.store');
        Route::get('/employees/{employee}/edit', [SuperadminController::class, 'employeeEdit'])->name('employees.edit');
        Route::put('/employees/{employee}', [SuperadminController::class, 'employeeUpdate'])->name('employees.update');
        Route::delete('/employees/{employee}', [SuperadminController::class, 'employeeDestroy'])->name('employees.destroy');

        // Supplier CRUD
        Route::get('/suppliers', [SuperadminController::class, 'supplierIndex'])->name('suppliers.index');
        Route::get('/suppliers/create', [SuperadminController::class, 'supplierCreate'])->name('suppliers.create');
        Route::post('/suppliers', [SuperadminController::class, 'supplierStore'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}', [SuperadminController::class, 'supplierShow'])->name('suppliers.show');
        Route::get('/suppliers/{supplier}/edit', [SuperadminController::class, 'supplierEdit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SuperadminController::class, 'supplierUpdate'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SuperadminController::class, 'supplierDestroy'])->name('suppliers.destroy');
    });

// Cashier Routes
Route::prefix('cashier')
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.')
    ->group(function () {
        Route::get('/', [CashierController::class, 'dashboard'])->name('dashboard');
    });

// Storage/Warehouse Routes
Route::prefix('warehouse')
    ->middleware(['auth', 'role:storage'])
    ->name('storage.')
    ->group(function () {
        Route::get('/', [StorageController::class, 'dashboard'])->name('dashboard');
    });
