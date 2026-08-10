<?php

namespace App\Services\Category;

use LaravelEasyRepository\Service;
use App\Repositories\Category\CategoryRepository;

class CategoryServiceImplement extends Service implements CategoryService {

    protected $mainRepository;

    public function __construct(CategoryRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getAllCategories()
    {
        // UBAH DARI pagination() MENJADI all() AGAR SEMUA MUNCUL
        return $this->mainRepository->all();
    }

    public function getCategoryById($id)
    {
        return $this->mainRepository->find($id);
    }

    public function getCategory($id)
    {
        return $this->getCategoryById($id);
    }

    public function createCategory($data)
    {
        return $this->mainRepository->create($data);
    }

    public function updateCategory($id, $data)
    {
        return $this->mainRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->mainRepository->delete($id);
    }
}