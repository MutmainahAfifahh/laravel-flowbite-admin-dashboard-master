<?php

namespace App\Services;

use App\Repository\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAll();
    }

    public function storeProduct(array $data)
    {
        if (empty($data['sku'])) {
            $data['sku'] = 'PRD-' . strtoupper(substr(md5(time()), 0, 6));
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }
}
