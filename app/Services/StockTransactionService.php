<?php

namespace App\Services;

use App\Repository\StockTransactionRepository;

class StockTransactionService
{
    protected $stockTransactionRepo;

    public function __construct(StockTransactionRepository $stockTransactionRepo)
    {
        $this->stockTransactionRepo = $stockTransactionRepo;
    }

    public function getAllTransactions()
    {
        return $this->stockTransactionRepo->getAll();
    }

    public function storeTransaction(array $data)
    {
        return $this->stockTransactionRepo->create($data);
    }

    public function deleteTransaction($id)
    {
        return $this->stockTransactionRepo->delete($id);
    }
}
