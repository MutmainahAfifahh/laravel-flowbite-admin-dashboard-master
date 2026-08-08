<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ProductAttributeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Buka http://127.0.0.1:8000 langsung lempar ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Practice Template Bawaan
Route::name('index-practice')->get('/practice', function () {
    return view('pages.practice.index');
});

Route::name('practice.')->group(function () {
    Route::name('first')->get('/practice/1', function () {
        return view('pages.practice.1');
    });
    Route::name('second')->get('/practice/2', function () {
        return view('pages.practice.2');
    });
});

// 3. Dashboard setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Modul Stockify yang Dilindungi Login & RoleMiddleware
Route::middleware(['auth'])->group(function () {

    // Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Atribut Produk (bisa diakses saat kelola produk)
    Route::post('product-attributes', [ProductAttributeController::class, 'store'])->name('product-attributes.store');
    Route::delete('product-attributes/{id}', [ProductAttributeController::class, 'destroy'])->name('product-attributes.destroy');

    // Route Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // Route Manajer Gudang & Admin
    Route::middleware(['role:admin,manajer_gudang'])->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class);
    });

    // Route Semua Role (Admin, Manajer, Staff)
    Route::middleware(['role:admin,manajer_gudang,staff_gudang'])->group(function () {
        Route::resource('transactions', StockTransactionController::class);
        Route::resource('stock-transactions', StockTransactionController::class);
    });

    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    });

});

require __DIR__.'/auth.php';