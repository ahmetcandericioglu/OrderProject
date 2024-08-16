<?php

namespace App\Http\Controllers;

use App\Http\IServices\IOrderDetailService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderDetailController extends Controller
{
    protected $orderDetailService;

    public function __construct(IOrderDetailService $orderDetailService)
    {
        $this->orderDetailService = $orderDetailService;
    }

    public function index()
    {
        return response()->json($this->orderDetailService->getAllOrderDetails());
    }

    public function show(int $id)
    {
        try {
            $orderDetail = $this->orderDetailService->getOrderDetailById($id);
            return response()->json($orderDetail);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $orderDetail = $this->orderDetailService->createOrderDetail($request);
            return response()->json($orderDetail, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id, Request $request)
    {
        try {
            $this->orderDetailService->updateOrderDetail($id, $request);
            return response()->json(['message' => 'Order detail updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->orderDetailService->deleteOrderDetail($id);
            return response()->json(['message' => 'Order detail deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

