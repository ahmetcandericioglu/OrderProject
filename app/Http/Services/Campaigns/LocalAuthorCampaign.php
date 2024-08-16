<?php

namespace App\Http\Services\Campaigns;

use App\Models\Order;
use App\Models\Campaign;

class LocalAuthorCampaign implements CampaignStrategyInterface
{
    public function calculateDiscount(Order $order, Campaign $campaign): float
    {
        $discount = 0;
        $localAuthors = ['Yaşar Kemal', 'Oğuz Atay', 'Sabahattin Ali'];

        foreach ($order->orderDetails as $orderDetail) {
            foreach ($orderDetail->products as $product) {
                if (in_array($product['author'], $localAuthors)) {
                    $discount += $product['total_price'] * 0.05;
                }
            }
        }

        return $discount;
    }
}
