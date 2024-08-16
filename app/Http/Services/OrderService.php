<?php

namespace App\Http\Services;

use App\Http\IServices\IOrderService;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderService implements IOrderService
{
    protected $productService;
    protected $campaignService;

    public function __construct(ProductService $productService, CampaignService $campaignService)
    {
        $this->productService = $productService;
        $this->campaignService = $campaignService;
    }
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


    public function processOrderCreation(Request $request): Order
    {
        $request->validate([
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|exists:products,product_id',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $orderDetailsData = $request->input('order_details');
            $totalAmount = 0;
            $discountAmount = 0;
            $shippingFee = 0;
            $appliedCampaign = null;

            $order = Order::create([
                'total_amount' => 0, 
                'discount_amount' => 0, 
                'shipping_fee' => 0, 
                'final_amount' => 0 
            ]);

            foreach ($orderDetailsData as $detail) {
                $product = Product::findOrFail($detail['product_id']);

                if ($product->stock_quantity < $detail['quantity']) {
                    throw new Exception("Insufficient stock for product ID: {$product->product_id}");
                }

                $originalPrice = $product->list_price * $detail['quantity'];
                $totalAmount += $originalPrice;
                
                // Ürünün stok miktarını azalt
                $this->productService->decreaseStock($product->product_id, $detail['quantity']);

                $newOrderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->product_id,
                    'quantity' => $detail['quantity'],
                    'unit_price' => $product->list_price,
                    'total_price' => $originalPrice,
                    'original_price' => $originalPrice,
                ]);

                list($appliedCampaign, $discountAmount) = $this->campaignService->applyBestCampaign($order);

                $newOrderDetail->campaign_id = $appliedCampaign->id;
                $newOrderDetail->discount_amount = $discountAmount;
                $newOrderDetail->total_price = $totalAmount-$discountAmount;
                $newOrderDetail->save();
            }

            $order->total_amount = $totalAmount;

            // Kampanya Uygulaması
            list($appliedCampaign, $discountAmount) = $this->campaignService->applyBestCampaign($order);
            $order->discount_amount = $discountAmount;

            // Kargo ücreti hesaplama
            $shippingFee = $totalAmount > 50 ? 0 : 10;
            $order->shipping_fee = $shippingFee;

            $order->final_amount = $totalAmount - $discountAmount + $shippingFee;

            // Güncellenmiş Order'ı Kaydetme
            $order->save();

            DB::commit();

            return $order->load('orderDetails');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e; 
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Order creation failed: " . $e->getMessage());
        }
    }
}
