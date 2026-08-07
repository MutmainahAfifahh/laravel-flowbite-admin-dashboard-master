<?php

namespace App\Repository;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::latest()->get();
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function create($data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->findById($id);
        return $category->delete();
    }
}