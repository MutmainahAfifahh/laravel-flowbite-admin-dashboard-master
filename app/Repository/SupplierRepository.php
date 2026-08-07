<?php

namespace App\Repository;

use App\Models\Supplier;

class SupplierRepository
{
    public function getAll()
    {
        return Supplier::latest()->get();
    }

    public function findById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function create($data)
    {
        return Supplier::create($data);
    }

    public function update($id, array $data)
    {
        $supplier = $this->findById($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete($id)
    {
        $supplier = $this->findById($id);
        return $supplier->delete();
    }
}