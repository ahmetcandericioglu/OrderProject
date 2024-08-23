<?php

namespace App\Http\Controllers;

use App\Http\IServices\IProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use InvalidArgumentException;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return response()->json($this->productService->getAllProducts());
    }

    public function show(int $id)
    {
        try {
            $product = $this->productService->getProductById($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $product = $this->productService->createProduct($request);
            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id, Request $request)
    {
        try {
            $this->productService->updateProduct($id, $request);
            return response()->json(['message' => 'Product updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function increaseStock(Request $request, $id)
    {
        try {
            $product = $this->productService->increaseStock($id, $request->input('amount'));
            return response()->json(['message' => 'Stock increased successfully', 'product' => $product], 200);
        }catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } 
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function decreaseStock(Request $request, $id)
    {
        try {
            $this->productService->decreaseStock($id, $request->input('amount'));
            return response()->json(['message' => 'Stock decreased successfully']);
        }catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }  
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}