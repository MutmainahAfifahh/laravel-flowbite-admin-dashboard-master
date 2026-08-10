<?php

namespace App\Services\Supplier;

use LaravelEasyRepository\BaseService;

interface SupplierService extends BaseService{

    public function getAllSuppliers();

    public function getSupplier($id);

    public function createSupplier($data);

    public function updateSupplier($id, $data);

    public function deleteSupplier($id);
}
