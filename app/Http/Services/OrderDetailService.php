<?php

namespace App\Http\Services;

use App\Http\IServices\IOrderDetailService;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderDetailService implements IOrderDetailService
{
    public function getAllOrderDetails()
    {
        return OrderDetail::all();
    }

    public function getOrderDetailById(int $id): ?OrderDetail
    {
        return OrderDetail::findOrFail($id);
    }

    public function createOrderDetail(Request $request): OrderDetail
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,product_id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
            ]);

            return OrderDetail::create([
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $request->total_price,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Order Detail Creation Error: " . $e->getMessage(), 500);
        }
    }

    public function updateOrderDetail(int $id, Request $request): bool
    {
        try {
            $orderDetail = $this->getOrderDetailById($id);

            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,product_id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
            ]);

            return $orderDetail->update([
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $request->total_price,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Order Detail Update Error: " . $e->getMessage(), 500);
        }
    }

    public function deleteOrderDetail(int $id): bool
    {
        try {
            $orderDetail = $this->getOrderDetailById($id);
            return $orderDetail->delete();
        } catch (Exception $e) {
            throw new Exception("Order Detail Deletion Error: " . $e->getMessage(), 500);
        }
    }
}
