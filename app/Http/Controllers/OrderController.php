<?php

namespace App\Http\Controllers;

use App\Http\IServices\IOrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(IOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return response()->json($this->orderService->getAllOrders());
    }

    public function show(int $id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $order = $this->orderService->createOrder($request);
            return response()->json($order, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id, Request $request)
    {
        try {
            $this->orderService->updateOrder($id, $request);
            return response()->json(['message' => 'Order updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->orderService->deleteOrder($id);
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

