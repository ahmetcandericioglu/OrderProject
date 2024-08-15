<?php

namespace App\Http\IServices;

use App\Models\Product;
use Illuminate\Http\Request;

interface IProductService
{
    public function getAllProducts();

    public function getProductById(int $id): ?Product;

    public function createProduct(Request $request): Product;

    public function updateProduct(int $id, Request $request): bool;

    public function deleteProduct(int $id): bool;

    public function increaseStock(int $id, int $amount): Product; 
    
    public function decreaseStock(int $id, int $amount): Product; 
}