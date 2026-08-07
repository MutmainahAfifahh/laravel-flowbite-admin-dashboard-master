<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    protected $stockTransactionService;
    protected $productService;

    public function __construct(
        StockTransactionService $stockTransactionService,
        ProductService $productService
    ) {
        $this->stockTransactionService = $stockTransactionService;
        $this->productService = $productService;
    }

    public function index()
    {
        $transactions = $this->stockTransactionService->getAllTransactions();
        $products = $this->productService->getAllProducts();

        return view('pages.StockTransactions.index', compact('transactions', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:Masuk,Keluar',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date',
            'status'     => 'required|in:Pending,Completed,Cancelled',
            'notes'      => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id() ?? 1;

        $this->stockTransactionService->storeTransaction($validated);

        return redirect()->back()->with('success', 'Transaksi stok berhasil dicatat!');
    }

    public function destroy($id)
    {
        $this->stockTransactionService->deleteTransaction($id);

        return redirect()->back()->with('success', 'Transaksi stok berhasil dihapus!');
    }
}
