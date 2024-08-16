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

        $localAuthorBooks = $order->orderDetails->filter(function ($detail) use ($localAuthors) {
            return in_array($detail->product->author, $localAuthors);
        });

        foreach ($localAuthorBooks as $detail) {
            $discount += $detail->total_price * 0.05;
        }

        return $discount;
    }
}
