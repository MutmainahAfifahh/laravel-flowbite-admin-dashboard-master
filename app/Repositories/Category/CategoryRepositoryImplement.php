<?php

namespace App\Repositories\Category;

use App\Events\ModelActivity;
use App\Models\Category;
use LaravelEasyRepository\Implementations\Eloquent;

class CategoryRepositoryImplement extends Eloquent implements CategoryRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    // Mengambil semua data kategori dari yang paling baru
    public function all()
    {
        return $this->model->latest()->get();
    }

    public function pagination()
    {
        return $this->model->latest()->simplePaginate(10);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create($data)
    {
        // Simpan data kategori lebih dulu
        $category = $this->model->create($data);

        // Amankan event log dengan try-catch
        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'create',
                    'Categories',
                    $category->name,
                    'Categories has been created successfuly',
                    $category->created_at,
                ));
            }
        } catch (\Exception $e) {
            // Jika log error, tetap izinkan kategori tersimpan
        }

        return $category;
    }

    public function update($id, array $data)
    {
        $category = $this->find($id);
        $category->update($data);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'update',
                    'Categories',
                    $category->name,
                    'Categories has been updated successfuly',
                    $category->updated_at,
                ));
            }
        } catch (\Exception $e) {
            // Abaikan error event log
        }

        return $category;
    }

    public function delete($id)
    {
        $category = $this->find($id);

        try {
            if (auth()->check()) {
                event(new ModelActivity(
                    auth()->user(),
                    'delete',
                    'Categories',
                    $category->name,
                    'Categories has been deleted successfuly',
                    $category->deleted_at ?? now(),
                ));
            }
        } catch (\Exception $e) {
            // Abaikan error event log
        }

        return $category->delete();
    }
}