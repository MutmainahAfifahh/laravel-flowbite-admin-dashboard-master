<?php

namespace App\Services\Product;

use App\Repositories\Category\CategoryRepository;
use App\Repositories\Supplier\SupplierRepository;
use App\Repositories\Product\ProductRepository;
use LaravelEasyRepository\Service;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductServiceImplement extends Service implements ProductService
{
    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;
    protected $categoryRepository;
    protected $supplierRepository;

    public function __construct(
      ProductRepository $mainRepository, 
      CategoryRepository $categoryRepository, 
      SupplierRepository $supplierRepository)
    {
        $this->mainRepository = $mainRepository;
        $this->categoryRepository = $categoryRepository;
        $this->supplierRepository = $supplierRepository;
    }

    public function getAllProducts()
    {
        return $this->mainRepository->withRelation();
    }

    public function getProduct($id)
    {
        return $this->mainRepository->find($id);
    }

    public function getProductById($id)
    {
        return $this->getProduct($id);
    }

    public function createProduct($data)
    {
        return $this->mainRepository->create($data);
    }

    public function storeProduct($data)
    {
        return $this->createProduct($data);
    }

    public function updateProduct($id, $data)
    {
        return $this->mainRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->mainRepository->delete($id);
    }

    public function getAllCategory()
    {
        return Category::all();
    }

    public function getAllSupplier()
    {
        return Supplier::all();
    }

    public function importFromExcel($file)
    {
        (new FastExcel)->import($file, function($line){
          $this->mainRepository->create([
          'category_id' => $line['category_id'],
          'supplier_id' => $line['supplier_id'],
          'name' => $line['name'],
          'sku' => $line['sku'],
          'description' => $line['description'],
          'purchase_price' => $line['purchase_price'],
          'selling_price' => $line['selling_price'],
          'image' => $line['image'],
          'minimum_stock' => $line['minimum_stock'],
          ]);
        });
        return true;
    }

    public function exportToExcel()
    {
        $model = $this->mainRepository->all();

        $data = $model->map(function($item){
          return [
            'category_id' => $item->category_id,
            'supplier_id' => $item->supplier_id,
            'name' => $item->name,
            'sku' => $item->sku,
            'description' => $item->description,
            'purchase_price' => $item->purchase_price,
            'selling_price' => $item->selling_price,
            'image' => $item->image,
            'minimum_stock' => $item->minimum_stock,
          ];
        });

        return (new FastExcel($data))->download('product-list.xlsx');
    }
}
