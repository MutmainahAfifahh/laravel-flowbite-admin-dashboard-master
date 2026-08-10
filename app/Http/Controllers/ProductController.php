<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductService;
use App\Services\Category\CategoryService;
use App\Services\Supplier\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $supplierService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        SupplierService $supplierService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();

        // image_url otomatis diikutsertakan oleh Model Product ($appends)
        return view('roles.Admin.Products.index', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'new_supplier' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0|max:999999999999',
            'selling_price' => 'required|numeric|min:0|max:999999999999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'minimum_stock' => 'nullable|integer|min:0|max:999999',
        ]);

        // Pastikan salah satu (kategori lama atau baru) terisi
        if (empty($validated['category_id']) && empty($validated['new_category'])) {
            return redirect()->back()->withErrors(['category_id' => 'Kategori wajib dipilih atau buat baru.'])->withInput();
        }

        // Pastikan salah satu (supplier lama atau baru) terisi
        if (empty($validated['supplier_id']) && empty($validated['new_supplier'])) {
            return redirect()->back()->withErrors(['supplier_id' => 'Supplier wajib dipilih atau buat baru.'])->withInput();
        }

        return DB::transaction(function () use ($request, $validated) {
            if ($request->filled('new_category')) {
                $category = \App\Models\Category::firstOrCreate(['name' => trim($request->new_category)]);
                $validated['category_id'] = $category->id;
            }

            if ($request->filled('new_supplier')) {
                $supplier = \App\Models\Supplier::firstOrCreate(['name' => trim($request->new_supplier)]);
                $validated['supplier_id'] = $supplier->id;
            }

            if (empty($validated['sku'])) {
                $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
            }

            if (!isset($validated['minimum_stock'])) {
                $validated['minimum_stock'] = 0;
            }

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            unset($validated['new_category'], $validated['new_supplier']);

            $this->productService->storeProduct($validated);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        });
    }

    public function edit(string $id)
    {
        $product = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('roles.Admin.Products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'new_supplier' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($id)],
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0|max:999999999999',
            'selling_price' => 'required|numeric|min:0|max:999999999999',
            'minimum_stock' => 'nullable|integer|min:0|max:999999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        return DB::transaction(function () use ($request, $validated, $id) {
            $product = $this->productService->getProductById($id);

            if ($request->filled('new_category')) {
                $category = \App\Models\Category::firstOrCreate(['name' => trim($request->new_category)]);
                $validated['category_id'] = $category->id;
            }

            if ($request->filled('new_supplier')) {
                $supplier = \App\Models\Supplier::firstOrCreate(['name' => trim($request->new_supplier)]);
                $validated['supplier_id'] = $supplier->id;
            }

            if (empty($validated['sku'])) {
                $validated['sku'] = $product->sku ?? ('PRD-' . strtoupper(Str::random(8)));
            }

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada di storage
                $this->deleteOldImage($product->image);
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            unset($validated['new_category'], $validated['new_supplier']);

            $this->productService->updateProduct($id, $validated);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
        });
    }

    public function destroy(string $id)
    {
        $product = $this->productService->getProductById($id);

        if ($product && $product->image) {
            $this->deleteOldImage($product->image);
        }

        $this->productService->deleteProduct($id);
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function export()
    {
        $products = $this->productService->getAllProducts();
        $filename = "export_products_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['SKU', 'Nama Produk', 'Kategori', 'Supplier', 'Harga Beli', 'Harga Jual', 'Deskripsi'], ';');

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->sku ?? '-',
                    $p->name,
                    $p->category->name ?? '-',
                    $p->supplier->name ?? '-',
                    $p->purchase_price ?? 0,
                    $p->selling_price ?? 0,
                    $p->description ?? '',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        $firstLine = fgets($handle);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
        rewind($handle);

        fgetcsv($handle, 0, $delimiter);

        $importedCount = 0;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (empty($row) || empty($row[1])) continue;

            $sku = !empty($row[0]) && $row[0] !== '-' ? trim($row[0]) : 'PRD-' . strtoupper(Str::random(6));
            $name = trim($row[1]);
            $categoryName = isset($row[2]) ? trim($row[2]) : null;
            $supplierName = isset($row[3]) ? trim($row[3]) : null;
            $purchasePrice = isset($row[4]) ? (float)$row[4] : 0;
            $sellingPrice = isset($row[5]) ? (float)$row[5] : 0;
            $description = isset($row[6]) ? trim($row[6]) : null;

            $categoryId = null;
            if (!empty($categoryName) && $categoryName !== '-') {
                $category = \App\Models\Category::firstOrCreate(['name' => $categoryName]);
                $categoryId = $category->id;
            }

            $supplierId = null;
            if (!empty($supplierName) && $supplierName !== '-') {
                $supplier = \App\Models\Supplier::firstOrCreate(['name' => $supplierName]);
                $supplierId = $supplier->id;
            }

            \App\Models\Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $name,
                    'category_id' => $categoryId,
                    'supplier_id' => $supplierId,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'description' => $description,
                ]
            );
            $importedCount++;
        }
        fclose($handle);

        return redirect()->back()->with('success', "Berhasil mengimpor {$importedCount} data produk!");
    }

    /**
     * Helper privat untuk menghapus gambar dari disk public
     */
    private function deleteOldImage(?string $imagePath): void
    {
        if (empty($imagePath) || Str::startsWith($imagePath, ['http://', 'https://'])) {
            return;
        }

        $cleanPath = ltrim(str_replace('storage/', '', $imagePath), '/');

        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }
}