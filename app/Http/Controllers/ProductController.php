<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\SupplierService;
use Illuminate\Http\Request;

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

        return view('pages.products.index', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|required_without:new_category|exists:categories,id',
            'new_category' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|required_without:new_supplier|exists:suppliers,id',
            'new_supplier' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0|max:999999999999',
            'selling_price' => 'required|numeric|min:0|max:999999999999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'minimum_stock' => 'required|integer|min:0|max:999999',
            'attribute_name' => 'nullable|string|max:255',
            'attribute_value' => 'nullable|string|max:255',
        ]);

        // Buat Kategori Baru secara otomatis jika diisi nama baru
        if (!empty($request->new_category)) {
            $category = \App\Models\Category::firstOrCreate(['name' => trim($request->new_category)]);
            $validated['category_id'] = $category->id;
        }

        // Buat Supplier Baru secara otomatis jika diisi nama baru
        if (!empty($request->new_supplier)) {
            $supplier = \App\Models\Supplier::firstOrCreate(['name' => trim($request->new_supplier)]);
            $validated['supplier_id'] = $supplier->id;
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Pastikan folder tujuan ada
            if (!file_exists(public_path('storage/products'))) {
                mkdir(public_path('storage/products'), 0777, true);
            }
            if (!file_exists(storage_path('app/public/products'))) {
                mkdir(storage_path('app/public/products'), 0777, true);
            }

            // Simpan ke public/storage/products agar bisa diakses langsung via web
            $file->move(public_path('storage/products'), $filename);
            
            // Backup ke storage/app/public/products
            @copy(public_path('storage/products/' . $filename), storage_path('app/public/products/' . $filename));

            $validated['image'] = 'products/' . $filename;
        }

        // Hapus input helper yang bukan kolom tabel products
        $attrName = $request->attribute_name;
        $attrVal = $request->attribute_value;
        unset($validated['new_category'], $validated['new_supplier'], $validated['attribute_name'], $validated['attribute_value']);

        $product = $this->productService->storeProduct($validated);

        // Buat atribut awal jika diisi saat tambah produk
        if (!empty($attrName) && !empty($attrVal)) {
            \App\Models\ProductAttribute::create([
                'product_id' => $product->id,
                'name' => trim($attrName),
                'value' => trim($attrVal),
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}