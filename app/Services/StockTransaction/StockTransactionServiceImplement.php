<?php

namespace App\Services\StockTransaction;

use LaravelEasyRepository\Service;
use App\Repositories\StockTransaction\StockTransactionRepository;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

class StockTransactionServiceImplement extends Service implements StockTransactionService
{
    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(StockTransactionRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getAllStockTransaction()
    {
        return $this->mainRepository->all();
    }

    public function getAllStockWithoutPageRestrict()
    {
        return $this->mainRepository->allNoPaginate();
    }

    public function getTransactionByProduct($id)
    {
        return $this->mainRepository->find($id);
    }

    public function createTransaction($data, $quantity = null)
    {
        return $this->mainRepository->create($data);
    }

    public function updateTransaction($id, $data)
    {
        return $this->mainRepository->update($id, $data);
    }

    public function deleteTransaction($id)
    {
        return $this->mainRepository->delete($id);
    }

    public function getAllCategoryByStock()
    {
        return Category::all();
    }

    public function getAllSuppliersByStock()
    {
        return Supplier::all();
    }

    public function getAllProductByStock()
    {
        return Product::all();
    }

    public function getTransactionByType($type)
    {
        return $this->mainRepository->filterByType($type);
    }

    public function getTransactionByCriteria($criteria)
    {
        return $this->mainRepository->filterByCriteria($criteria);
    }

    public function generatePdfByType($type)
    {
        return true;
    }

    public function generatePdfByCriteria($criteria, $all = null)
    {
        return true;
    }

    public function getMinimumQuantityStock()
    {
        return $this->mainRepository->getMinimumStock();
    }

    public function updateMinimumQuantityStock($minQuantity)
    {
        return $this->mainRepository->updateMinimumStock($minQuantity);
    }

    public function getTransactionByTypeAndPeriod($type, $days = 30)
    {
        return $this->mainRepository->countTransactionByTypeAndPeriod($type, $days);
    }

    public function getTransactionByMonthAndYear($type = 'Masuk')
    {
        return $this->mainRepository->transactionByMonthAndYear($type);
    }

    public function countLowStock($minQuantity)
    {
        return $this->mainRepository->countLowStock($minQuantity);
    }
}
