<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $categories = \App\Models\Category::all();

        return view('roles.Admin.Reports.stock', [
            'title' => 'Laporan Stok Barang',
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function transactionReport(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest('date')->get();

        return view('roles.Admin.Reports.transactions', [
            'title' => 'Laporan Transaksi Barang Masuk dan Keluar',
            'transactions' => $transactions,
        ]);
    }

    public function activityReport()
    {
        $activities = \App\Models\UserActivity::with('user')->latest()->get();

        return view('roles.Admin.Reports.activities', [
            'title' => 'Laporan Aktivitas Pengguna',
            'activities' => $activities,
        ]);
    }
}
