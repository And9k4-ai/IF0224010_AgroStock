<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;

// ==========================================
// PUBLIC / GUEST ROUTES
// ==========================================
Route::get('/', function () {
    return redirect('/login');
});

// ==========================================
// AUTHENTICATED ROUTES (Bisa Diakses Admin & User Biasa)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Kategori (Hanya View & List Data Ajax)
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index')->name('categories.index');
        Route::get('/list', 'list')->name('categories.list');
    });

    // Master Barang (Hanya View & List Data Ajax)
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('/', 'index')->name('products.index');
        Route::get('/list', 'list')->name('products.list');
    });
});

// ==========================================
// ADMIN ONLY ROUTES (Hanya Bisa Diakses Akun Ber-role Admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {

   
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::post('/', 'store')->name('categories.store');
        Route::get('/{id}/edit', 'edit')->name('categories.edit');
        Route::put('/{id}', 'update')->name('categories.update');
        Route::delete('/{id}', 'destroy')->name('categories.destroy');
    });

    
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::post('/', 'store')->name('products.store');
        Route::get('/{id}/edit', 'edit')->name('products.edit');
        Route::put('/{id}', 'update')->name('products.update');
        Route::delete('/{id}', 'destroy')->name('products.destroy');
    });

    
    Route::controller(StockInController::class)->prefix('stock-ins')->group(function () {
        Route::get('/', 'index')->name('stockins.index');
        Route::get('/list', 'list')->name('stockins.list');
        Route::post('/', 'store')->name('stockins.store');
    });

  
    Route::controller(StockOutController::class)->prefix('stock-outs')->group(function () {
        Route::get('/', 'index')->name('stockouts.index');
        Route::get('/list', 'list')->name('stockouts.list');
        Route::post('/', 'store')->name('stockouts.store');
    });
});


require __DIR__.'/auth.php';