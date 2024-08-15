<?php
 
namespace App\Http\IServices;

use App\Models\Category;
use Illuminate\Http\Request;

interface ICategoryService
{
    public function getAllCategories();

    public function getCategoryById(int $id): ?Category;

    public function createCategory(Request $request): Category;

    public function updateCategory(int $id, Request $request): bool;

    public function deleteCategory(int $id): bool;
}