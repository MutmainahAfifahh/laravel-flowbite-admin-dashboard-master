<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductService;
use App\Services\StockTransaction\StockTransactionService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    protected $productService, $stockTransactionService, $userService;

    public function __construct(
        ProductService $productService,
        StockTransactionService $stockTransactionService,
        UserService $userService
    ) {
        $this->productService = $productService;
        $this->stockTransactionService = $stockTransactionService;
        $this->userService = $userService;
    }

    public function redirectTo(){
        if (Auth::check()){
            if (Auth::user()->role('Admin')){
                return redirect()->route('Admin/dashboard');
            } else if (Auth::user()->role('Staff Gudang')){
                return redirect()->route('Staff Gudang/dashboard');
            }else if (Auth::user()->role('Manajer Gudang')){
                return redirect()->route('Manajer Gudang/dashboard');
            }
        }
        return redirect()->route('login');
    }

    public function downloadUserActivityReport(Request $request){
        $request->input('action', 'view');
        return $this->userService->generateActivityReport();
    }

    public function index() {
        $MinQuantity = $this->stockTransactionService->getMinimumQuantityStock();
        
        $userRole = strtolower(trim(Auth::user()->role ?? ''));

        if (in_array($userRole, ['admin'])) {
            $getAllProducts = $this->productService->getAllProducts();
            $activitiesUser = $this->userService->getAllUserActivities();
            $totalLowStock = $this->stockTransactionService->countLowStock($MinQuantity);
            $transactionLastSixMonth = $this->stockTransactionService->getTransactionByMonthAndYear();
            $IncomingTransactionInMonth = $this->stockTransactionService->getTransactionByTypeAndPeriod('Masuk', 30);
            $outgoingTransactionInMonth = $this->stockTransactionService->getTransactionByTypeAndPeriod('Keluar', 30);

            // Data grafik: 6 bulan terakhir (seperti di dashboard lama)
            $chartLabels = [];
            $chartMasuk  = [];
            $chartKeluar = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $chartLabels[] = $month->format('M Y');
                $chartMasuk[]  = \App\Models\StockTransaction::where('type', 'Masuk')
                                    ->whereYear('created_at', $month->year)
                                    ->whereMonth('created_at', $month->month)
                                    ->sum('quantity');
                $chartKeluar[] = \App\Models\StockTransaction::where('type', 'Keluar')
                                    ->whereYear('created_at', $month->year)
                                    ->whereMonth('created_at', $month->month)
                                    ->sum('quantity');
            }

            return view('roles.Admin.index', [
                'title' => 'Dashboard Admin',
                'activities' => $activitiesUser,
                'totalProducts' => count($getAllProducts),
                'totalLowStock' => $totalLowStock,
                'incomingTransaction' => $IncomingTransactionInMonth,
                'outgoingTransaction' => $outgoingTransactionInMonth,
                'transactionData' => $transactionLastSixMonth,
                'chartLabels' => $chartLabels,
                'chartMasuk' => $chartMasuk,
                'chartKeluar' => $chartKeluar,
            ]);
        } elseif (in_array($userRole, ['staff gudang', 'staff', 'staff_gudang'])) {
            $incomingTransactionByType = $this->stockTransactionService->getTransactionByType('Masuk');
            $outgoingTransactionByType = $this->stockTransactionService->getTransactionByType('Keluar');
            return view('roles.Staff.index', [
                'title' => 'Dashboard Staff Gudang',
                'incomingItem' => count($incomingTransactionByType),
                'outgoingItem' => count($outgoingTransactionByType),
            ]);
        } else {
            $totalLowStock = $this->stockTransactionService->countLowStock($MinQuantity);
            $IncomingTransactionInDay = $this->stockTransactionService->getTransactionByTypeAndPeriod('Masuk', 1);
            $outgoingTransactionInDay = $this->stockTransactionService->getTransactionByTypeAndPeriod('Keluar', 1);
            $activitiesUser = $this->userService->getAllUserActivities();
            return view('roles.Manajer-Gudang.index', [
                'title' => 'Dashboard Manajer Gudang',
                'incomingTransaction' => $IncomingTransactionInDay,
                'outgoingTransaction' => $outgoingTransactionInDay,
                'lowStock' => $totalLowStock,
                'activities' => $activitiesUser,
            ]);
        }
    }
}
