<?php

namespace App\Http\Controllers;

use App\Services\Supplier\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();

        $userRole = strtolower(trim(auth()->user()->role ?? ''));

        // Manajer Gudang mendapat view khusus
        if (in_array($userRole, ['manajer gudang', 'manajer_gudang', 'manajer'])) {
            return view('roles.Manajer-Gudang.supplier.index', compact('suppliers'))
                ->with('title', 'Daftar Supplier');
        }

        return view('roles.Admin.Suppliers.index', compact('suppliers'));
    }

    public function show($id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        return view('roles.Admin.Suppliers.detail', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        return view('roles.Admin.Suppliers.edit', compact('supplier'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $this->supplierService->createSupplier($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $this->supplierService->updateSupplier($id, $validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->supplierService->deleteSupplier($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }
}