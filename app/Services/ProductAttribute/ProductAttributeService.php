<?php

namespace App\Services\ProductAttribute;

use LaravelEasyRepository\BaseService;

interface ProductAttributeService extends BaseService{

    public function getAllAttributeProducts();

    public function getAttributeProduct($id);

    public function createAttributeProduct($data);

    public function updateAttributeProduct($id, $data);

    public function deleteAttributeProduct($id);

    public function getAllProducts();
}
