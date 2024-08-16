<?php

namespace App\Http\Services;

use App\Http\IServices\IOrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderService implements IOrderService
{
    public function getAllOrders()
    {
        return Order::all();
    }

    public function getOrderById(int $id): ?Order
    {
        return Order::findOrFail($id);
    }

    public function createOrder(Request $request): Order
    {
        try {
            $request->validate([
                'total_amount' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'shipping_fee' => 'nullable|numeric|min:0',
                'final_amount' => 'required|numeric|min:0',
            ]);

            return Order::create([
                'total_amount' => $request->total_amount,
                'discount_amount' => $request->discount_amount,
                'shipping_fee' => $request->shipping_fee,
                'final_amount' => $request->final_amount,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Order Creation Error: " . $e->getMessage(), 500);
        }
    }

    public function updateOrder(int $id, Request $request): bool
    {
        try {
            $order = $this->getOrderById($id);

            $request->validate([
                'total_amount' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'shipping_fee' => 'nullable|numeric|min:0',
                'final_amount' => 'required|numeric|min:0',
            ]);

            return $order->update([
                'total_amount' => $request->total_amount,
                'discount_amount' => $request->discount_amount,
                'shipping_fee' => $request->shipping_fee,
                'final_amount' => $request->final_amount,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Order Update Error: " . $e->getMessage(), 500);
        }
    }

    public function deleteOrder(int $id): bool
    {
        try {
            $order = $this->getOrderById($id);
            return $order->delete();
        } catch (Exception $e) {
            throw new Exception("Order Deletion Error: " . $e->getMessage(), 500);
        }
    }
}
