<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Services\StockTransaction\StockTransactionService;

class StockTransactionController extends Controller
{
    protected $stockTransactionService;

    public function __construct(StockTransactionService $stockTransactionService) {
        $this->stockTransactionService = $stockTransactionService;
    }

    private function transactionValidation() {
        return [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|',
            'date' => 'nullable|date',
            'status' => 'required|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes' => 'nullable|string',
        ];
    }

    private function isPdfRequest(Request $request) {
        return in_array($request->input('action'), [
            'print-transaction',
            'print-stock', 
            'printTypeManager'
        ]);
    }

    private function handlePdfGenerate(Request $request, $filters = []) {
        $action = $request->input('action', 'view');

        if ($action === 'print-transaction') {
            return $this->stockTransactionService->generatePdfByType($request->type);
        } elseif ($action === 'print-stock') {
            return $this->stockTransactionService->generatePdfByCriteria($filters);
        }
    }

    public function historyTransaction(Request $request) {
        $type = $request->input('type');
        $query = \App\Models\StockTransaction::with(['product', 'user']);

        if ($type && in_array($type, ['Masuk', 'Keluar'])) {
            $query->where('type', $type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest('date')->latest('id')->paginate(5)->withQueryString();

        return view('roles.Admin.Transactions.history', [
            'title' => 'Riwayat Transaksi Stok',
            'transactions' => $transactions,
            'selectedType' => $type,
        ]);
    }


    public function minimumStockView() {
        $minimumStock = $this->stockTransactionService->getMinimumQuantityStock();

        return view('roles.Admin.Transactions.minimum-stock', [
            'title' => 'Pengaturan Stok Minimum',
            'minimumStock' => $minimumStock,
        ]);
    }

    public function index(Request $request) {
        $products = \App\Models\Product::orderBy('name')->get();

        $type = $request->input('type');
        $query = \App\Models\StockTransaction::with(['product', 'user']);

        if ($type && in_array($type, ['Masuk', 'Keluar'])) {
            $query->where('type', $type);
        }

        $transactions = $query->latest('date')->latest('id')->paginate(10)->withQueryString();

        return view('roles.Admin.Transactions.index', [
            'title'        => 'Kelola Transaksi Stok Barang',
            'products'     => $products,
            'transactions' => $transactions,
        ]);
    }

    public function mainTransaction(Request $request) { 
        $categoriesData = $this->stockTransactionService->getAllCategoryByStock();
        $suppliersData = $this->stockTransactionService->getAllSuppliersByStock();
        $productData = $this->stockTransactionService->getAllProductByStock();
        $stockByType = $this->stockTransactionService->getTransactionByType($request->type);
        $filters = $request->only(['periods', 'categories', 'start_date', 'end_date']);
        
        if (isset($filters['categories'])) {
            $categoryName = $filters['categories'];
            $category = Categories::where('name', $categoryName)->first();
            $filters['categories'] = $category ? $category->id : null;
        }

        $stockByCriteria = $this->stockTransactionService->getTransactionByCriteria($filters);

        if($this->isPdfRequest($request)) {
            return $this->handlePdfGenerate($request, $filters);
        }

        return view('roles.manager.stock.index', [
            'title' => 'Management Stock Transaction',
            'category' => $categoriesData,
            'supplier' => $suppliersData,
            'product' => $productData,
            'stockByType' => $stockByType,
            'stockByCriteria' => $stockByCriteria,
        ]);
    }

    public function opnameStockView() {
        $minimumStock = $this->stockTransactionService->getMinimumQuantityStock();
        $allTransaction = $this->stockTransactionService->getAllStockTransaction();

        return view('roles.Admin.Transactions.stock-opname', [
            'title' => 'Stock Opname',
            'minimumStock' => $minimumStock,
            'transaction' => $allTransaction,
        ]);
    }

    public function opnameStockManagerView() {
        $allTransaction = $this->stockTransactionService->getAllStockTransaction();

        return view('roles.manager.stock.opname', [
            'title' => 'Stock Opname',
            'transaction' => $allTransaction,
        ]);
    }

    public function confirmationStockView() {
        $getAllStock = $this->stockTransactionService->getAllStockWithoutPageRestrict();
        $getPendingStatus = $getAllStock->filter(function($item) {
            return $item->status === 'Pending';
        });

        return view('roles.staff.confirmation-stock', [
            'title' => 'Stock Check Confirmation',
            'data' => $getPendingStatus,
        ]);
    }

    public function inboundTransaction() {
        $products = \App\Models\Product::orderBy('name')->get();
        $transactions = \App\Models\StockTransaction::where('type', 'Masuk')
            ->with(['product', 'user'])
            ->latest('date')
            ->latest('id')
            ->simplePaginate(10);

        return view('roles.Admin.Transactions.inbound', [
            'title' => 'Transaksi Barang Masuk',
            'products' => $products,
            'transactions' => $transactions,
        ]);
    }

    public function outboundTransaction() {
        $products = \App\Models\Product::orderBy('name')->get();
        $transactions = \App\Models\StockTransaction::where('type', 'Keluar')
            ->with(['product', 'user'])
            ->latest('date')
            ->latest('id')
            ->simplePaginate(10);

        return view('roles.Admin.Transactions.outbound', [
            'title' => 'Transaksi Barang Keluar',
            'products' => $products,
            'transactions' => $transactions,
        ]);
    }

    public function store(Request $request) {
        $transaction = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:Masuk,Keluar',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date',
            'status'     => 'required|string',
            'notes'      => 'nullable|string',
        ]);
        $transaction['user_id'] = auth()->id() ?? 1;

        $this->stockTransactionService->createTransaction($transaction, $request->quantity);

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }

    public function confirmInboundView() {
        $transactions = \App\Models\StockTransaction::where('type', 'Masuk')
            ->where('status', 'Pending')
            ->with(['product', 'user'])
            ->latest('date')
            ->simplePaginate(10);

        return view('roles.Admin.Transactions.confirm-inbound', [
            'title' => 'Konfirmasi Penerimaan Barang',
            'transactions' => $transactions,
        ]);
    }

    public function confirmOutboundView() {
        $transactions = \App\Models\StockTransaction::where('type', 'Keluar')
            ->where('status', 'Pending')
            ->with(['product', 'user'])
            ->latest('date')
            ->simplePaginate(10);

        return view('roles.Admin.Transactions.confirm-outbound', [
            'title' => 'Konfirmasi Pengeluaran Barang',
            'transactions' => $transactions,
        ]);
    }

    public function updateConfirmationStatus(Request $request, $id) {
        $validated = $request->validate([
            'status' => 'required|in:Diterima,Ditolak,Dikeluarkan,Completed,Cancelled',
        ]);

        $stock = \App\Models\StockTransaction::findOrFail($id);
        $stock->status = $validated['status'];
        $stock->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui menjadi ' . $validated['status']);
    }
    
    public function opnameData(Request $request) {
        $stockId = $request->input('stock_id');
        $types = $request->input('type');
        $status = $request->input('status');
        $quantity = $request->input('minimum_stock');

        foreach($stockId as $index => $id) {
            $data = array_filter([
                'type' => $types[$index] ?? null,
                'status' => $status[$index] ?? null,
                'quantity' => $quantity[$index] ?? null,
            ]);

            if(!empty($data)) {
                $this->stockTransactionService->updateTransaction($id, $data);
            }
        }

        $redirectByAuth = auth()->user()->role;

        if($redirectByAuth === 'Admin') {
            return redirect()->route('stock.opname')->with('success');
        } elseif ($redirectByAuth === 'Manajer Gudang') {
            return redirect()->route('stock.manager-opname')->with('success');
        }
    }

    public function downloadReportByType(Request $request) {
        $type = $request->input('type');
        return $this->stockTransactionService->generatePdfByType($type);
    }

    public function downloadReportByCriteria(Request $request) {
        $criteria = $this->stockTransactionService->getTransactionByCriteria(
            $request->only(['periods', 'categories', 'start_date', 'end_date'])
        );
        return $this->stockTransactionService->generatePdfByCriteria($criteria, $request->all());
    }

    public function updateStockMinimum(Request $request) {
        $validated = $request->validate([
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $this->stockTransactionService->updateMinimumQuantityStock($validated['minimum_stock']);
        return redirect()->back()->with('success', 'Stok minimum berhasil diperbarui.');
    }

    public function show($id) {
        $transaction = \App\Models\StockTransaction::with(['product', 'user'])->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($transaction);
        }

        return redirect()->route('transactions.index');
    }

    public function destroy($id) {
        $this->stockTransactionService->deleteTransaction($id);
        return redirect()->back()->with('success', 'Transaksi stok berhasil dihapus.');
    }
}
