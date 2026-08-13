<?php

namespace App\Repositories\Supplier;

use App\Events\ModelActivity;
use App\Models\Supplier;
use LaravelEasyRepository\Implementations\Eloquent;

class SupplierRepositoryImplement extends Eloquent implements SupplierRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->latest()->get();
    }

    public function pagination()
    {
        return $this->model->simplePaginate(5);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create($data)
    {
        $supplier = $this->model->create($data);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'create',
                    'Supplier',
                    $supplier->name,
                    'Supplier created successfully',
                    $supplier->created_at,
                ));
            }
        } catch (\Throwable $e) {}

        return $supplier;
    }

    public function update($id, $data)
    {
        $supplier = $this->find($id);
        $supplier->update($data);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'update',
                    'Supplier',
                    $supplier->name,
                    'Supplier has been updated successfully',
                    $supplier->updated_at,
                ));
            }
        } catch (\Throwable $e) {}

        return $supplier;
    }

    public function delete($id)
    {
        $supplier = $this->find($id);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'delete',
                    'Supplier',
                    $supplier->name,
                    'Supplier has been deleted successfully',
                    now(),
                ));
            }
        } catch (\Throwable $e) {}

        return $supplier->delete();
    }
}
