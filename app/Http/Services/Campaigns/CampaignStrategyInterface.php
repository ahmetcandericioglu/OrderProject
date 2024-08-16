<?php

namespace App\Http\Services\Campaigns;

use App\Models\Order;
use App\Models\Campaign;


interface CampaignStrategyInterface
{
    public function calculateDiscount(Order $order, Campaign $campaign): float;
}