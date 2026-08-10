<?php

namespace App\Http\Controllers;

use App\Services\Category\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('roles.Admin.Categories.index', [
            'title'      => 'Manajemen Kategori',
            'categories' => $categories,
        ]);
    }

    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        return view('roles.Admin.Categories.edit', [
            'title'    => 'Edit Kategori',
            'category' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->categoryService->createCategory($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->categoryService->updateCategory($id, $validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}