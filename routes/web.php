<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Services\User\UserService;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. PUBLIC & AUTHENTICATION (GUEST)
// =========================================================================

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('authentication.sign-in');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $role = str_replace(' ', '_', strtolower(trim(Auth::user()->role ?? '')));

            if ($role === 'admin') {
                return redirect()->route('admin.index');
            } elseif (in_array($role, ['manajer_gudang', 'manajer', 'manager', 'staff_gudang', 'staff'])) {
                return redirect('/dashboard');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    });
});

// =========================================================================
// 2. ROUTE PRACTICE (TEMPLATE/DEV)
// =========================================================================

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

// =========================================================================
// 3. DASHBOARDS
// =========================================================================

Route::get('/dashboard', function () {
    $userRole = strtolower(trim(Auth::user()->role ?? ''));

    // Admin fallback jika mengakses /dashboard
    if (in_array($userRole, ['admin'])) {
        return redirect()->route('admin.index');
    }

    // ── Dashboard Manajer Gudang ──
    if (in_array($userRole, ['manajer gudang', 'manajer_gudang', 'manajer'])) {
        $products = Product::withCount([
            'transactions as stok_masuk'  => fn($q) => $q->where('type', 'Masuk')->where('status', '!=', 'Cancelled'),
            'transactions as stok_keluar' => fn($q) => $q->where('type', 'Keluar')->where('status', '!=', 'Cancelled'),
        ])->get();

        $lowStock = $products->filter(fn($p) => (($p->stok_masuk ?? 0) - ($p->stok_keluar ?? 0)) <= $p->minimum_stock)->count();

        // Ditampilkan berdasarkan 1 bulan terakhir (30 hari terakhir)
        $incomingTransaction = StockTransaction::where('type', 'Masuk')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();
            
        $outgoingTransaction = StockTransaction::where('type', 'Keluar')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();
            
        $activities = app(UserService::class)->getAllUserActivities();

        return view('roles.Manajer-Gudang.index', compact('lowStock', 'incomingTransaction', 'outgoingTransaction', 'activities'))
            ->with('title', 'Dashboard Manajer Gudang');
    }

    // ── Dashboard Staff Gudang ──
    if (in_array($userRole, ['staff gudang', 'staff_gudang', 'staff'])) {
        // Disamakan: Ditampilkan berdasarkan 1 bulan terakhir (30 hari terakhir)
        $incomingTransaction = StockTransaction::where('type', 'Masuk')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();
            
        $outgoingTransaction = StockTransaction::where('type', 'Keluar')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();

        return view('roles.Staff.index', [
            'title'        => 'Dashboard Staff Gudang',
            'incomingItem' => $incomingTransaction,
            'outgoingItem' => $outgoingTransaction,
        ]);
    }

    return redirect()->route('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.index');

// =========================================================================
// 4. MODUL APLIKASI (PERLU AUTHENTICATION)
// =========================================================================

Route::middleware(['auth'])->group(function () {

    // --- Profile & Session ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::match(['get', 'post'], '/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // ---------------------------------------------------------------------
    // A. KHUSUS ADMIN
    // ---------------------------------------------------------------------
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        
        Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('setting.update');
        
        Route::get('/reports/activities', [ReportController::class, 'activityReport'])->name('reports.activities');
    });

    // ---------------------------------------------------------------------
    // B. MANAJEMEN STOK & LAPORAN (ADMIN, MANAJER, & STAFF)
    // ---------------------------------------------------------------------
    Route::middleware(['role:admin,manajer_gudang,staff_gudang'])->group(function () {
        // Import / Export Produk
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');

        // Laporan Stok & Transaksi
        Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
        Route::get('/reports/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');

       // Route Simpan, Edit/Update, dan Hapus
Route::post('/stock/store', [StockTransactionController::class, 'store'])->name('stock.store');
Route::put('/stock/update/{id}', [StockTransactionController::class, 'update'])->name('stock.update');
Route::delete('/stock/destroy/{id}', [StockTransactionController::class, 'destroy'])->name('stock.destroy');

        // Stock Opname
        Route::get('/stock-opname', [StockTransactionController::class, 'opnameStockView'])->name('stock.opname');
        Route::post('/stock-opname', [StockTransactionController::class, 'opnameData'])->name('stock.update');
        Route::get('/opname', [StockTransactionController::class, 'opnameStockManagerView'])->name('opname');
        Route::post('/opname', [StockTransactionController::class, 'opnameData'])->name('opname.update');

        // Stok Minimum & Konfirmasi Transaksi
        Route::post('/stock-minimum', [StockTransactionController::class, 'updateStockMinimum'])->name('stock.update-minimum');
        Route::get('/stock/minimum', [StockTransactionController::class, 'minimumStockView'])->name('stock.minimum');
        Route::get('/api/stock/minimum', [StockTransactionController::class, 'getMinimumStockApi'])->name('api.stock.minimum');
        Route::get('/stock/confirm-inbound', [StockTransactionController::class, 'confirmInboundView'])->name('stock.confirm-inbound');
        Route::get('/stock/confirm-outbound', [StockTransactionController::class, 'confirmOutboundView'])->name('stock.confirm-outbound');
        Route::post('/stock-status/{id}', [StockTransactionController::class, 'updateConfirmationStatus'])->name('stock.confirm-status');

        // Detail Transaksi
        Route::get('/transactions/inbound', [StockTransactionController::class, 'inboundTransaction'])->name('transactions.inbound');
        Route::get('/transactions/outbound', [StockTransactionController::class, 'outboundTransaction'])->name('transactions.outbound');
        Route::get('/transactions/history', [StockTransactionController::class, 'historyTransaction'])->name('transactions.history');
    });

    // ---------------------------------------------------------------------
    // C. RESOURCE UTAMA (DAPAT DIAKSES PENGGUNA TEROTENTIKASI)
    // ---------------------------------------------------------------------
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('transactions', StockTransactionController::class);
    Route::resource('stock-transactions', StockTransactionController::class);
    Route::resource('attributes', ProductAttributeController::class);
    Route::resource('product-attributes', ProductAttributeController::class);

});