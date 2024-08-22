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
                    $quantity = $product['quantity'];
                    if ($quantity >= 2) {
                        $discount += $product['unit_price'];
                    }
                }
            }
        }

        return $discount;
    }
}
