<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\StockTransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::name('index-practice')->get('/', function () {
    return view('pages.practice.index');
});

Route::name('practice.')->group(function () {
    Route::name('first')->get('practice/1', function () {
        return view('pages.practice.1');
    });
    Route::name('second')->get('practice/2', function () {
        return view('pages.practice.2');
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);
});

Route::middleware(['auth', 'role:admin,manajer_gudang'])->group(function () {
    Route::get('/laporan/stok', [LaporanController::class, 'index']);
    Route::get('/approval/barang-masuk', [ApprovalController::class, 'index']);
});

Route::middleware(['auth', 'role:admin,manajer_gudang,staff_gudang'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang-masuk', [TransaksiController::class, 'storeBarangMasuk']);
});

Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);
Route::resource('stock-transactions', StockTransactionController::class);
Route::post('product-attributes', [ProductAttributeController::class, 'store'])->name('product-attributes.store');
Route::delete('product-attributes/{id}', [ProductAttributeController::class, 'destroy'])->name('product-attributes.destroy');