<?php

namespace App\Http\Services;

use App\Http\IServices\ICategoryService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class CategoryService implements ICategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById(int $id): ?Category
    {
        return Category::findOrFail($id);
    }

    public function createCategory(Request $request): Category
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            return Category::create([
                'title' => $request->title,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Category Creation Error: " . $e->getMessage(), 500);
        }
    }

    public function updateCategory(int $id, Request $request): bool
    {
        try {
            $category = $this->getCategoryById($id);

            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            return $category->update([
                'title' => $request->title,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Category Update Error: " . $e->getMessage(), 500);
        }
    }

    public function deleteCategory(int $id): bool
    {
        try {
            $category = $this->getCategoryById($id);

            return $category->delete();
        } catch (Exception $e) {
            throw new Exception("Category Deletion Error: " . $e->getMessage(), 500);
        }
    }
}