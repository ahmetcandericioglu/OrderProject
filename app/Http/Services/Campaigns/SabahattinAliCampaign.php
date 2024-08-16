<?php

namespace App\Http\Services\Campaigns;

use App\Models\Order;
use App\Models\Campaign;

class SabahattinAliCampaign implements CampaignStrategyInterface
{
    public function calculateDiscount(Order $order, Campaign $campaign): float
    {
        $discount = 0;
        $sabahattinAliBooks = $order->orderDetails->filter(function ($detail) {
            return $detail->product->author === 'Sabahattin Ali' && $detail->product->category->title === 'Roman';
        });

        $bookCount = $sabahattinAliBooks->sum('quantity');

        if ($bookCount >= 2) {
            $discount = $sabahattinAliBooks->first()->product->list_price;
        }

        return $discount;
    }
}
