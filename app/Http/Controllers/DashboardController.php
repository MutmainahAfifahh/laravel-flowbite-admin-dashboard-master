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
        
        if (Auth::user()->role == 'Admin') {
            $getAllProducts = $this->productService->getAllProducts();
            $activitiesUser = $this->userService->getAllUserActivities();
            $totalLowStock = $this->stockTransactionService->countLowStock($MinQuantity);
            $transactionLastSixMonth = $this->stockTransactionService->getTransactionByMonthAndYear();
            $IncomingTransactionInMonth = $this->stockTransactionService->getTransactionByTypeAndPeriod('Masuk', 30);
            $outgoingTransactionInMonth = $this->stockTransactionService->getTransactionByTypeAndPeriod('Keluar', 30);

            return view('roles.Admin.index', [
                'title' => 'Dashboard Admin',
                'activities' => $activitiesUser,
                'totalProducts' => count($getAllProducts),
                'totalLowStock' => $totalLowStock,
                'incomingTransaction' => $IncomingTransactionInMonth,
                'outgoingTransaction' => $outgoingTransactionInMonth,
                'transactionData' => $transactionLastSixMonth,
            ]);
        } elseif (Auth::user()->role == "Staff Gudang") {
            $incomingTransactionByType = $this->stockTransactionService->getTransactionByType('Masuk');
            $outgoingTransactionByType = $this->stockTransactionService->getTransactionByType('Keluar');
            return view('roles.staff.index', [
                'title' => 'Dashboard Staff Gudang',
                'incomingItem' => count($incomingTransactionByType),
                'outgoingItem' => count($outgoingTransactionByType),
            ]);
        } elseif (Auth::user()->role == "Manajer Gudang") {
            $totalLowStock = $this->stockTransactionService->countLowStock($MinQuantity);
            $IncomingTransactionInDay = $this->stockTransactionService->getTransactionByTypeAndPeriod('Masuk', 1);
            $outgoingTransactionInDay = $this->stockTransactionService->getTransactionByTypeAndPeriod('Keluar', 1);
            return view('roles.manager.index', [
                'title' => 'Dashboard Manajer Gudang',
                'incomingTransaction' => $IncomingTransactionInDay,
                'outgoingTransaction' => $outgoingTransactionInDay,
                'lowStock' => $totalLowStock,
            ]);
        }
    }
}
