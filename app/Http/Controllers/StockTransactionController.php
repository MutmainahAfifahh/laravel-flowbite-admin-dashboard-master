<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
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
            'type'       => 'required|in:Masuk,Keluar',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'nullable|date',
            'status'     => 'required|in:Pending,Diterima,Ditolak,Dikeluarkan',
            'notes'      => 'nullable|string',
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
            $transactions = $this->stockTransactionService->generatePdfByType($request->type);
            return view('roles.Manajer-Gudang.stock.print', compact('transactions', 'action'));
        } elseif ($action === 'print-stock') {
            $transactions = $this->stockTransactionService->generatePdfByCriteria($filters);
            return view('roles.Manajer-Gudang.stock.print', compact('transactions', 'action'));
        }
    }

    public function historyTransaction(Request $request) {
        $type = $request->input('type');
        $query = \App\Models\StockTransaction::with(['product.supplier', 'supplier', 'user']);

        if ($type && in_array($type, ['Masuk', 'Keluar'])) {
            $query->where('type', $type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest()->get();

        // AMBIL DATA PRODUCTS & SUPPLIERS UNTUK FORM MODAL
        $products  = \App\Models\Product::orderBy('name')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('roles.Admin.Transactions.history', [
            'title'        => 'Riwayat Transaksi Stok',
            'transactions' => $transactions,
            'selectedType' => $type,
            'products'     => $products,   // <-- WAJIB ADA
            'suppliers'    => $suppliers,  // <-- WAJIB ADA
        ]);
    }
    public function minimumStockView() {
        $minimumStock = $this->stockTransactionService->getMinimumQuantityStock();

        return view('roles.Admin.Transactions.minimum-stock', [
            'title'        => 'Pengaturan Stok Minimum',
            'minimumStock' => $minimumStock,
        ]);
    }

    public function index(Request $request) {
        $products = \App\Models\Product::with('supplier')->orderBy('name')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $minimumStock = $this->stockTransactionService->getMinimumQuantityStock();

        $type = $request->input('type');
        $query = \App\Models\StockTransaction::with(['product.supplier', 'product.category', 'user']);

        if ($type && in_array($type, ['Masuk', 'Keluar'])) {
            $query->where('type', $type);
        }

        $transactions = $query->latest('date')->latest('id')->paginate(50)->withQueryString();

        $role = auth()->user()->role ?? '';

        if (in_array($role, ['Staff Gudang', 'Staff'])) {
            return view('roles.Staff.confirmation-stock', [
                'title'        => 'Konfirmasi Stok Barang',
                'products'     => $products,
                'suppliers'    => $suppliers,
                'transactions' => $transactions,
                'minimumStock' => $minimumStock,
            ]);
        }

        return view('roles.Admin.Transactions.index', [
            'title'        => 'Kelola Transaksi Stok Barang',
            'products'     => $products,
            'suppliers'    => $suppliers,
            'transactions' => $transactions,
            'minimumStock' => $minimumStock,
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
            'title'           => 'Management Stock Transaction',
            'category'        => $categoriesData,
            'supplier'        => $suppliersData,
            'product'         => $productData,
            'stockByType'     => $stockByType,
            'stockByCriteria' => $stockByCriteria,
        ]);
    }

    public function opnameStockView() {
        $minimumStock = $this->stockTransactionService->getMinimumQuantityStock();
        $allTransaction = $this->stockTransactionService->getAllStockTransaction();

        $role = auth()->user()->role ?? '';

        if (in_array($role, ['Staff Gudang', 'Staff'])) {
            return view('roles.Staff.stock-opname', [
                'title'       => 'Stock Opname',
                'transaction' => $allTransaction,
            ]);
        }

        return view('roles.Admin.Transactions.stock-opname', [
            'title'        => 'Stock Opname',
            'minimumStock' => $minimumStock,
            'transaction'  => $allTransaction,
        ]);
    }

    public function opnameStockManagerView() {
        $allTransaction = $this->stockTransactionService->getAllStockTransaction();

        return view('roles.Manajer-Gudang.stock.opname', [
            'title'       => 'Stock Opname',
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
            'data'  => $getPendingStatus,
        ]);
    }

    public function inboundTransaction() {
        $products = \App\Models\Product::orderBy('name')->get();
        $transactions = \App\Models\StockTransaction::where('type', 'Masuk')
            ->with(['product', 'user'])
            ->latest('date')
            ->latest('id')
            ->get();

        return view('roles.Admin.Transactions.inbound', [
            'title'        => 'Transaksi Barang Masuk',
            'products'     => $products,
            'transactions' => $transactions,
        ]);
    }

    public function outboundTransaction() {
        $products = \App\Models\Product::orderBy('name')->get();
        $transactions = \App\Models\StockTransaction::where('type', 'Keluar')
            ->with(['product', 'user'])
            ->latest('date')
            ->latest('id')
            ->get();

        return view('roles.Admin.Transactions.outbound', [
            'title'        => 'Transaksi Barang Keluar',
            'products'     => $products,
            'transactions' => $transactions,
        ]);
    }

        public function store(Request $request) {
        $transaction = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id'=> 'required_if:type,Masuk|nullable|exists:suppliers,id',
            'type'       => 'required|in:Masuk,Keluar',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date|before_or_equal:' . date('Y-12-31'),
            'status'     => 'nullable|string',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($transaction['product_id']);
        $globalMinStock = $this->stockTransactionService->getMinimumQuantityStock();
        $minAllowedStock = $globalMinStock ?? $product->min_stock ?? 0;

        $userRole = strtolower(auth()->user()->role ?? '');
        $isAdminOrManager = in_array($userRole, ['admin', 'manajer gudang', 'manajer', 'manager']);

        // VALIDASI BACKEND: Bypass pengecekan minimum untuk Admin dan Manajer
        if (!$isAdminOrManager) {
            // Qty masuk tidak boleh kurang dari minimum Admin
            if ($transaction['type'] !== 'Keluar' && $transaction['quantity'] < $minAllowedStock) {
                return redirect()->back()->with('error', "Gagal disimpan! Qty yang dimasukkan ({$transaction['quantity']} pcs) kurang dari batas minimum Admin ({$minAllowedStock} pcs).");
            }

            // Validasi Barang Keluar (dinonaktifkan sesuai permintaan agar form tetap bisa disimpan)
            /*
            if ($transaction['type'] === 'Keluar') {
                $remainingStock = $product->stock - $transaction['quantity'];
                if ($remainingStock < $minAllowedStock) {
                    $maxAllowed = max(0, $product->stock - $minAllowedStock);
                    return redirect()->back()->with('error', "Gagal disimpan! Sisa stok tidak boleh kurang dari {$minAllowedStock} pcs. Maksimal barang keluar hanya {$maxAllowed} pcs.");
                }
            }
            */
        }

        $transaction['status'] = $transaction['status'] ?? 'Pending';
        $transaction['user_id'] = auth()->id() ?? 1;

        $this->stockTransactionService->createTransaction($transaction, $request->quantity);

        return redirect()->back()->with('success', 'Transaksi konfirmasi stok berhasil dicatat!');
    }

    public function confirmInboundView() {
        $transactions = \App\Models\StockTransaction::where('type', 'Masuk')
            ->where('status', 'Pending')
            ->with(['product', 'user'])
            ->latest('date')
            ->get();

        return view('roles.Admin.Transactions.confirm-inbound', [
            'title'        => 'Konfirmasi Penerimaan Barang',
            'transactions' => $transactions,
        ]);
    }

    public function confirmOutboundView() {
        $transactions = \App\Models\StockTransaction::where('type', 'Keluar')
            ->where('status', 'Pending')
            ->with(['product', 'user'])
            ->latest('date')
            ->get();

        return view('roles.Admin.Transactions.confirm-outbound', [
            'title'        => 'Konfirmasi Pengeluaran Barang',
            'transactions' => $transactions,
        ]);
    }

    public function updateConfirmationStatus(Request $request, $id) {
        $validated = $request->validate([
            'status' => 'required|in:Diterima,Ditolak,Dikeluarkan,Completed,Cancelled,Terima,Keluar,Tolak',
        ]);

        $stock = \App\Models\StockTransaction::findOrFail($id);

        $status = $validated['status'];
        if ($status === 'Terima' || $status === 'Diterima') {
            $stock->status = 'Diterima';
        } elseif ($status === 'Keluar' || $status === 'Dikeluarkan') {
            $stock->status = 'Dikeluarkan';
        } elseif ($status === 'Tolak' || $status === 'Ditolak') {
            $stock->status = 'Ditolak';
        } else {
            $stock->status = $status;
        }

        $stock->save();

        return redirect()->back()->with('success', 'Konfirmasi transaksi berhasil disimpan (' . $stock->status . ') dan telah tercatat di Riwayat Transaksi!');
    }
    
    public function opnameData(Request $request) {
        $stockId  = $request->input('stock_id');
        $types    = $request->input('type');
        $status   = $request->input('status');
        $quantity = $request->input('quantity');
        $notes    = $request->input('notes');

        if(empty($stockId) || !is_array($stockId)) {
            return redirect()->back()->with('error', 'Tidak ada data yang diproses.');
        }

        foreach($stockId as $id => $val) {
            $transaction = \App\Models\StockTransaction::find($id);
            $data = [];

            if ($transaction) {
                if (isset($types[$id])) {
                    $data['type'] = $types[$id];
                }

                if (isset($status[$id])) {
                    $data['status'] = $status[$id];
                }

                if (isset($notes[$id])) {
                    $data['notes'] = $notes[$id];
                }
                
                if (isset($quantity[$id])) {
                    $data['quantity'] = $quantity[$id];
                }

                if (!empty($data)) {
                    $this->stockTransactionService->updateTransaction($id, $data);
                }
            }
        }

        $redirectByAuth = auth()->user()->role ?? '';

        if($redirectByAuth === 'Admin') {
            return redirect()->route('stock.opname')->with('success', 'Data opname berhasil disimpan!');
        } elseif ($redirectByAuth === 'Manajer Gudang') {
            return redirect()->route('opname')->with('success', 'Data opname berhasil disimpan!');
        } else {
            return redirect()->route('stock.opname')->with('success', 'Data opname berhasil disimpan!');
        }
    }

    public function downloadReportByType(Request $request) {
        $type = $request->input('type');
        $transactions = $this->stockTransactionService->generatePdfByType($type);
        return view('roles.Manajer-Gudang.stock.print', compact('transactions'));
    }

    public function downloadReportByCriteria(Request $request) {
        $filters = $request->only(['periods', 'categories', 'start_date', 'end_date']);
        $transactions = $this->stockTransactionService->generatePdfByCriteria($filters);
        return view('roles.Manajer-Gudang.stock.print', compact('transactions'));
    }

    public function updateStockMinimum(Request $request) {
        $validated = $request->validate([
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $this->stockTransactionService->updateMinimumQuantityStock($validated['minimum_stock']);
        return redirect()->back()->with('success', 'Stok minimum berhasil diperbarui.');
    }

    /**
     * API endpoint: Mengembalikan nilai minimum stok terbaru (JSON).
     * Digunakan oleh halaman Staff untuk real-time sync tanpa refresh.
     */
    public function getMinimumStockApi() {
        $minimumStock = $this->stockTransactionService->getMinimumQuantityStock();

        return response()->json([
            'minimum_stock' => (int) $minimumStock,
        ]);
    }

    public function show($id) {
        $transaction = \App\Models\StockTransaction::with(['product', 'user'])->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($transaction);
        }

        return redirect()->route('transactions.index');
    }

    public function update(Request $request, $id) {
        $transactionData = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required_if:type,Masuk|nullable|exists:suppliers,id',
            'type'        => 'required|in:Masuk,Keluar',
            'quantity'    => 'required|integer|min:1',
            'date'        => 'required|date',
            'notes'       => 'nullable|string',
        ]);

        $product = Product::findOrFail($transactionData['product_id']);
        $globalMinStock = $this->stockTransactionService->getMinimumQuantityStock();
        $minAllowedStock = $globalMinStock ?? $product->min_stock ?? 0;

        $userRole = strtolower(auth()->user()->role ?? '');
        $isAdminOrManager = in_array($userRole, ['admin', 'manajer gudang', 'manajer', 'manager']);

        // VALIDASI BACKEND: Kecuali Admin/Manajer
        if (!$isAdminOrManager) {
            // Validasi Barang Masuk: Tidak boleh kurang dari minimum stok
            if ($transactionData['type'] !== 'Keluar' && $transactionData['quantity'] < $minAllowedStock) {
                return redirect()->back()->with('error', "Gagal memperbarui! Qty yang dimasukkan ({$transactionData['quantity']} pcs) kurang dari batas minimum ({$minAllowedStock} pcs).");
            }

            // Validasi Barang Keluar: Sisa stok tidak boleh kurang dari minimum
            if ($transactionData['type'] === 'Keluar') {
                $remainingStock = $product->stock - $transactionData['quantity'];
                if ($remainingStock < $minAllowedStock) {
                    $maxAllowed = max(0, $product->stock - $minAllowedStock);
                    return redirect()->back()->with('error', "Gagal memperbarui! Sisa stok tidak boleh kurang dari {$minAllowedStock} pcs. Maksimal barang keluar hanya {$maxAllowed} pcs.");
                }
            }
        }

        // Jalankan pembaruan transaksi melalui service
        $this->stockTransactionService->updateTransaction($id, $transactionData);

        return redirect()->back()->with('success', 'Data transaksi stok berhasil diperbarui!');
    }

    public function destroy($id) {
        $this->stockTransactionService->deleteTransaction($id);
        return redirect()->back()->with('success', 'Transaksi stok berhasil dihapus.');
    }
}