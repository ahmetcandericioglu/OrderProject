<?php

namespace App\Http\Services\Campaigns;

use App\Models\Order;
use App\Models\Campaign;

class SabahattinAliCampaign implements CampaignStrategyInterface
{
    public function calculateDiscount(Order $order, Campaign $campaign): float
    {
        $discount = 0;

        foreach ($order->orderDetails as $orderDetail) {
            foreach ($orderDetail->products as $product) {
                if ($product['author'] === 'Sabahattin Ali' && $product['category_title'] === 'Roman') {
                    // Örneğin: 2 üründen 1 tanesi bedava
                    $quantity = $product['quantity'];
                    if ($quantity >= 2) {
                        $discount += $product['unit_price']; // En ucuz ürünü ücretsiz yapıyoruz
                    }
                }
            }
        }

        return $discount;
    }
}
