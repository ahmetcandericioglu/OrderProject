<?php

namespace App\Http\Services;

use App\Http\IServices\IProductService;
use App\Models\Product;
use App\Models\Category;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService implements IProductService
{
    public function getAllProducts()
    {
        $cacheKey = 'all_products';
        $products = Cache::remember($cacheKey, 3600, function () {
            return Product::all();
        });
        return $products;
    }

    public function getProductById(int $id): ?Product
    {
        $cacheKey = 'product_' . $id;
        $product = Cache::remember($cacheKey, 3600, function () use ($id) {
            return Product::findOrFail($id);
        });

        return $product;
    }

    public function createProduct(Request $request): Product
    {
        try {
            Cache::forget('all_products');
            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'author' => 'required|string|max:255',
                'list_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
            ]);

            $category = Category::find($request->category_id);
            if (!$category) {
                throw new NotFoundHttpException("Category not found.");
            }

            $product = Product::create([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'category_title' => $category->title,
                'author' => $request->author,
                'list_price' => $request->list_price,
                'stock_quantity' => $request->stock_quantity,
            ]);

            $cacheKey = 'product_' . $product->id;
            Cache::put($cacheKey, $product, 3600);

            return $product;
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Product Creation Error: " . $e->getMessage(), 500);
        }
    }

    public function updateProduct(int $id, Request $request): bool
    {
        try {
            Cache::forget('all_products');
            $product = $this->getProductById($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'author' => 'required|string|max:255',
                'list_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
            ]);

            $category = Category::find($request->category_id);
            
            if (!$category) {
                throw new NotFoundHttpException("Category not found.");
            }

            $updated = $product->update([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'category_title' => $category->title,
                'author' => $request->author,
                'list_price' => $request->list_price,
                'stock_quantity' => $request->stock_quantity,
            ]);

            if ($updated) {
                $cacheKey = 'product_' . $id;
                Cache::put($cacheKey, $product, 3600); 
            }

            return $updated;

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Product Update Error: " . $e->getMessage(), 500);
        }
    }

    public function deleteProduct(int $id): bool
    {
        try {
            Cache::forget('all_products');
            $product = $this->getProductById($id);
            $cacheKey = 'product_' . $product->id;
            Cache::forget($cacheKey);
            return $product->delete();
        } catch (Exception $e) {
            throw new Exception("Product Deletion Error: " . $e->getMessage(), 500);
        }
    }

    public function increaseStock(int $productId, int $amount) : Product
    {
        try {
            Cache::forget('all_products');
            $product = Product::findOrFail($productId);
            if ($amount <= 0) {
                throw new InvalidArgumentException("The amount to increase must be greater than zero.");
            }
            
            $product->increment('stock_quantity', $amount);
            return $product;    

        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Product Stock Error: '. $e->getMessage(), 400);
        }
        catch (Exception $e) {
            throw new Exception('Product Stock Error: '. $e->getMessage(), 500);
        }
    }

    public function decreaseStock(int $productId, int $amount): Product
    {
        try {
            Cache::forget('all_products');
            $product = Product::findOrFail($productId);
            if ($amount <= 0) {
                throw new InvalidArgumentException("The amount to decrease must be greater than zero.");
            }
            
            $product->decrement('stock_quantity', $amount);
            return $product;    

        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Product Stock Error: '. $e->getMessage(), 400);
        }
        catch (Exception $e) {
            throw new Exception('Product Stock Error: '. $e->getMessage(), 500);
        }
    }
}