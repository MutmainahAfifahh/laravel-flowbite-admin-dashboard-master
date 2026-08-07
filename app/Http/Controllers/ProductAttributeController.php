<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'value'      => 'required|string|max:255',
        ]);

        ProductAttribute::create($request->all());

        return back()->with('success', 'Atribut berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        $attribute->delete();

        return back()->with('success', 'Atribut berhasil dihapus!');
    }
}