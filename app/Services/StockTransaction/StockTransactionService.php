<?php

namespace App\Services\StockTransaction;

use LaravelEasyRepository\BaseService;

interface StockTransactionService extends BaseService{

    public function getAllStockTransaction();
    public function getAllStockWithoutPageRestrict();
    public function getTransactionByProduct($id);
    public function createTransaction($data);
    public function updateTransaction($id, $data);
    public function deleteTransaction($id);
    public function getAllCategoryByStock();
    public function getAllSuppliersByStock();
    public function getAllProductByStock();
    public function getTransactionByType($type);
    public function getTransactionByCriteria($criteria);
    public function generatePdfByType($type);
    public function generatePdfByCriteria($criteria);
    public function getMinimumQuantityStock();
    public function updateMinimumQuantityStock($minQuantity);
    public function getTransactionByTypeAndPeriod($type, $days);
    public function getTransactionByMonthAndYear();
    public function countLowStock($minQuantity);
}

