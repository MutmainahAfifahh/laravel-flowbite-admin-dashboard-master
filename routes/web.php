<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('authentication.sign-in');
    })->name('login');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $role = str_replace(' ', '_', strtolower(trim(Auth::user()->role ?? '')));

            if ($role === 'admin') {
                return redirect('/categories');
            } elseif ($role === 'manajer_gudang') {
                return redirect('/products');
            } elseif ($role === 'staff_gudang') {
                return redirect('/transactions');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    });
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
    $totalProducts  = \App\Models\Product::count();

    // Produk dengan stok rendah: hitung stok efektif per produk
    $products = \App\Models\Product::withCount([
        'transactions as stok_masuk' => fn($q) => $q->where('type', 'Masuk')->where('status', '!=', 'Cancelled'),
        'transactions as stok_keluar' => fn($q) => $q->where('type', 'Keluar')->where('status', '!=', 'Cancelled'),
    ])->get();
    $stokRendah = $products->filter(fn($p) => (($p->stok_masuk ?? 0) - ($p->stok_keluar ?? 0)) <= $p->minimum_stock)->count();

    $masuk30  = \App\Models\StockTransaction::where('type', 'Masuk')
                    ->where('date', '>=', now()->subDays(30))->count();
    $keluar30 = \App\Models\StockTransaction::where('type', 'Keluar')
                    ->where('date', '>=', now()->subDays(30))->count();

    // Data grafik: 6 bulan terakhir
    $chartLabels = [];
    $chartMasuk  = [];
    $chartKeluar = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $chartLabels[] = $month->format('M Y');
        $chartMasuk[]  = \App\Models\StockTransaction::where('type', 'Masuk')
                            ->whereYear('date', $month->year)
                            ->whereMonth('date', $month->month)
                            ->sum('quantity');
        $chartKeluar[] = \App\Models\StockTransaction::where('type', 'Keluar')
                            ->whereYear('date', $month->year)
                            ->whereMonth('date', $month->month)
                            ->sum('quantity');
    }

    $transaksiTerbaru = \App\Models\StockTransaction::with(['product', 'user'])
                        ->latest('date')->latest('id')->take(5)->get();

    return view('dashboard', compact(
        'totalProducts', 'stokRendah', 'masuk30', 'keluar30',
        'chartLabels', 'chartMasuk', 'chartKeluar', 'transaksiTerbaru'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');


// 3. Modul Stockify yang Dilindungi Login & RoleMiddleware
Route::middleware(['auth'])->group(function () {

    // Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Khusus Admin & Manajer (Didefinisikan SEBELUM Route::resource agar tidak tertimpa wildcard {id})
    Route::middleware(['role:admin,manajer_gudang,staff_gudang'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
        Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
        Route::get('/reports/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');
        Route::get('/reports/activities', [ReportController::class, 'activityReport'])->name('reports.activities');

        // Stock Opname & Settings Routes
        Route::get('/stock-opname', [StockTransactionController::class, 'opnameStockView'])->name('stock.opname');
        Route::post('/stock-opname', [StockTransactionController::class, 'opnameData'])->name('stock.update');
        Route::post('/stock-minimum', [StockTransactionController::class, 'updateStockMinimum'])->name('stock.update-minimum');
        
        Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('setting.update');

        Route::get('/transactions/inbound', [StockTransactionController::class, 'inboundTransaction'])->name('transactions.inbound');
        Route::get('/transactions/outbound', [StockTransactionController::class, 'outboundTransaction'])->name('transactions.outbound');
        Route::get('/transactions/history', [StockTransactionController::class, 'historyTransaction'])->name('transactions.history');
        Route::get('/stock/confirm-inbound', [StockTransactionController::class, 'confirmInboundView'])->name('stock.confirm-inbound');
        Route::get('/stock/confirm-outbound', [StockTransactionController::class, 'confirmOutboundView'])->name('stock.confirm-outbound');
        Route::get('/stock/minimum', [StockTransactionController::class, 'minimumStockView'])->name('stock.minimum');
        Route::post('/stock-status/{id}', [StockTransactionController::class, 'updateConfirmationStatus'])->name('stock.confirm-status');
    });

    // Modul Utama Inventory & Master Data (Didefinisikan SETELAH rute spesifik)
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('transactions', StockTransactionController::class);
    Route::resource('stock-transactions', StockTransactionController::class);
    Route::resource('attributes', ProductAttributeController::class);
    Route::resource('product-attributes', ProductAttributeController::class);

    Route::match(['get', 'post'], '/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

});