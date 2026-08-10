<?php

namespace App\Services\ProductAttribute;

use LaravelEasyRepository\Service;
use App\Repositories\ProductAttribute\ProductAttributeRepository;
use App\Models\Product;

class ProductAttributeServiceImplement extends Service implements ProductAttributeService
{
    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(ProductAttributeRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getAllAttributeProducts()
    {
        return $this->mainRepository->all();
    }

    public function getAttributeProduct($id)
    {
        return $this->mainRepository->find($id);
    }

    public function createAttributeProduct($data)
    {
        return $this->mainRepository->create($data);
    }

    public function updateAttributeProduct($id, $data)
    {
        return $this->mainRepository->update($id, $data);
    }

    public function update($id, $data)
    {
        return $this->mainRepository->update($id, $data);
    }

    public function deleteAttributeProduct($id)
    {
        return $this->mainRepository->delete($id);
    }

    public function getAllProducts()
    {
        return Product::all();
    }
}
