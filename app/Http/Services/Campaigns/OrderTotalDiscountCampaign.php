<?php

namespace App\Http\Services\Campaigns;

use App\Models\Order;
use App\Models\Campaign;

class OrderTotalDiscountCampaign implements CampaignStrategyInterface
{
    public function calculateDiscount(Order $order, Campaign $campaign): float
    {
        $totalAmount = $order->total_amount;
        if ($totalAmount >= 200) {
            return $totalAmount * 0.05;
        }
        return 0;
    }
}
