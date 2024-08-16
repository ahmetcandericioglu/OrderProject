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

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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

            foreach ($orderDetailsData as $detail) {
                $product = Product::findOrFail($detail['product_id']);

                if ($product->stock_quantity < $detail['quantity']) {
                    throw new Exception("Insufficient stock for product ID: {$product->product_id}");
                }

                list($appliedCampaign, $discount) = $this->applyBestCampaign($product, $detail['quantity']);
                $discountAmount += $discount;

                $originalPrice = $product->list_price * $detail['quantity'];
                $totalAmount += $originalPrice - $discount;

                $this->productService->decreaseStock($product->product_id, $detail['quantity']);
            }

            if ($totalAmount <= 50) {
                $shippingFee = 10;
            }

            $finalAmount = $totalAmount + $shippingFee - $discountAmount;

            $orderRequest = new Request([
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'final_amount' => $finalAmount,
            ]);

            $order = $this->createOrder($orderRequest);

            foreach ($orderDetailsData as $detail) {
                $product = Product::findOrFail($detail['product_id']);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->product_id,
                    'quantity' => $detail['quantity'],
                    'unit_price' => $product->list_price,
                    'total_price' => $product->list_price * $detail['quantity'],
                    'original_price' => $product->list_price * $detail['quantity'],
                    'discount_amount' => $discountAmount,
                    'campaign_id' => $appliedCampaign ? $appliedCampaign->id : null,
                ]);
            }

            DB::commit();

            return $order;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e; 
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Order creation failed: " . $e->getMessage());
        }
    }


    private function applyBestCampaign(Product $product, int $quantity): array
        {
            $campaigns = Campaign::all();
            $bestCampaign = null;
            $maxDiscount = 0;

            foreach ($campaigns as $campaign) {
                $discount = 0;
                if ($campaign->type === 'Sabahattin Ali 2 ürün 1 bedava' && $quantity >= 2) {
                    $discount = $product->list_price; 
                } elseif ($campaign->type === 'Yerli Yazar %5 indirim' && $product->author_is_local) {
                    $discount = ($product->list_price * $quantity) * 0.05;
                } elseif ($campaign->type === '200 TL üzeri %5 indirim' && $product->list_price * $quantity > 200) {
                    $discount = ($product->list_price * $quantity) * 0.05; 
                }

                if ($discount > $maxDiscount) {
                    $maxDiscount = $discount;
                    $bestCampaign = $campaign;
                }
            }

            return [$bestCampaign, $maxDiscount];
        }
}
