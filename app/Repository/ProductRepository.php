<?php

namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
    public function getAll()
    {
        return Product::with(['category', 'supplier', 'attributes'])->latest()->get();
    }

    public function findById($id)
    {
        return Product::with(['category', 'supplier', 'attributes'])->findOrFail($id);
    }

    public function create($data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }
}