<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Services\ProductAttribute\ProductAttributeService;

class ProductAttributeController extends Controller
{
    protected $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService) {
        $this->productAttributeService = $productAttributeService;
    }

    public function validationData() {
        return [
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'value'      => 'required|string|max:255',
        ];
    }

    public function index() {
        $attributes = $this->productAttributeService->getAllAttributeProducts();
        $products = $this->productAttributeService->getAllProducts();

        return view('roles.Admin.Products.attributes.index', [
            'title' => 'Product Attributes',
            'attributes' => $attributes,
            'products' => $products,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate($this->validationData());

        $this->productAttributeService->createAttributeProduct($data);
        return redirect()->route('attributes.index')->with('success', 'Attribute Product created successfully.');
    }

    public function show($id) {
        $attribute = $this->productAttributeService->getAttributeProduct($id);

        return view('roles.Admin.Products.attributes.edit', [
            'title' => 'Product Attribute Detail',
            'attribute' => $attribute,
        ]);
    }

    public function edit($id) {
        $attribute = $this->productAttributeService->getAttributeProduct($id);
        return view('roles.Admin.Products.attributes.edit', [
            'title' => 'Edit Attribute Product',
            'attribute' => $attribute,
        ]);
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);
        
        $this->productAttributeService->update($id, $data);

        return redirect()->route('attributes.index')->with('success', 'Attribute Product updated successfully.');
    }

    public function destroy($id) {
        $this->productAttributeService->deleteAttributeProduct($id);

        return redirect()->route('attributes.index')->with('success', 'Attribute Product deleted successfully.');
    }
}