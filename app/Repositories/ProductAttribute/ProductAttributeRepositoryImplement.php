<?php

namespace App\Repositories\ProductAttribute;

use App\Events\ModelActivity;
use App\Models\ProductAttribute;
use LaravelEasyRepository\Implementations\Eloquent;

class ProductAttributeRepositoryImplement extends Eloquent implements ProductAttributeRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(ProductAttribute $model)
    {
        $this->model = $model;
    }

    public function all() {
        return $this->model->with('product')->latest()->get();
    }

    public function find($id) {
        return $this->model->findOrFail($id);
    }

    public function create($data) {
        $attribute = $this->model->create($data);
        
        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'create',
                    'Product Attribute',
                    $attribute->name,
                    'Product Attribute has been created successfully',
                    $attribute->created_at,
                ));
            }
        } catch (\Throwable $e) {}

        return $attribute;
    }

    public function update($id, $data) {
        $attributeProduct = $this->model->find($id);
        $updated = $attributeProduct->update($data);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'update',
                    'Product Attribute',
                    $attributeProduct->name,
                    'Product Attribute has been updated successfully',
                    $attributeProduct->updated_at,
                ));
            }
        } catch (\Throwable $e) {}

        return $attributeProduct;
    }

    public function delete($id) {
        $attributeProduct = $this->model->find($id);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'delete',
                    'Product Attribute',
                    $attributeProduct->name,
                    'Product Attribute has been deleted successfully',
                    now(),
                ));
            }
        } catch (\Throwable $e) {}

        return $attributeProduct->delete();
    }
}
