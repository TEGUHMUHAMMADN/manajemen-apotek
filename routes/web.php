<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // 1. Kategori Obat
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // 2. Data Obat & Inventaris
    Route::get('/medicines', [MedicineController::class, 'index'])
        ->name('medicines.index');

    Route::get('/medicines/create', [MedicineController::class, 'create'])
        ->name('medicines.create');

    Route::post('/medicines', [MedicineController::class, 'store'])
        ->name('medicines.store');

    Route::post('/medicines/find-by-code', [MedicineController::class, 'findByCode'])
        ->name('medicines.findByCode');

    Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])
        ->name('medicines.show');

    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])
        ->name('medicines.edit');

    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])
        ->name('medicines.update');

    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])
        ->name('medicines.destroy');

    // 3. Kasir / POS & Logika FEFO
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');

    // 4. Laporan Bulanan
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // Export laporan bulanan ke Excel
    Route::get('/reports/export', [ReportController::class, 'exportMonthly'])
        ->name('reports.exportMonthly');

});

// Settings
require __DIR__.'/settings.php';
