<?php

namespace App\Repositories\Product;

use App\Events\ModelActivity;
use App\Models\Product;
use BladeUIKit\Components\DateTime\Carbon;
use LaravelEasyRepository\Implementations\Eloquent;


class ProductRepositoryImplement extends Eloquent implements ProductRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all() {
        return $this->model->all();
    }

    public function withRelation() {
        return $this->model->with(['category', 'supplier', 'attributes'])->latest()->get();
    }

    public function find($id) {
        return $this->model->findOrFail($id);
    }

    public function create($data) {
        if (empty($data['sku'])) {
            $data['sku'] = 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8));
        }

        $product = $this->model->create($data);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(), 
                    'create', 
                    'Product', 
                    $product->name, 
                    'Product has been created successfully',
                    $product->created_at,
                ));
            }
        } catch (\Throwable $e) {}

        return $product;
    }

    public function update($id, $data) {
        $product = $this->find($id);
        $product->update($data);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(), 
                    'update', 
                    'Product', 
                    $product->name, 
                    'Product has been updated successfully',
                    $product->updated_at,
                ));
            }
        } catch (\Throwable $e) {}

        return $product;
    }

    public function delete($id) {
        $product = $this->find($id);
        
        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(), 
                    'delete', 
                    'Product', 
                    $product->name, 
                    'Product has been deleted successfully',
                    now(),
                ));
            }
        } catch (\Throwable $e) {}

        return $product->delete();
    }
}
