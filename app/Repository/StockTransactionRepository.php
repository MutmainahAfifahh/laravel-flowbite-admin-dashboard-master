<?php

namespace App\Repository;

use App\Models\StockTransaction;

class StockTransactionRepository
{
    public function getAll()
    {
        return StockTransaction::with(['product', 'user'])->latest()->get();
    }

    public function findById($id)
    {
        return StockTransaction::with(['product', 'user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return StockTransaction::create($data);
    }

    public function delete($id)
    {
        $transaction = $this->findById($id);
        return $transaction->delete();
    }
}
