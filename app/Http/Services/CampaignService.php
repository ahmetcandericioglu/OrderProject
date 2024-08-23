<?php

namespace App\Http\Services;

use App\Http\IServices\ICampaignService;
use App\Models\Campaign;
use App\Models\Order;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Services\Campaigns\SabahattinAliCampaign;
use App\Http\Services\Campaigns\LocalAuthorCampaign;
use App\Http\Services\Campaigns\OrderTotalDiscountCampaign;

use Exception;

class CampaignService implements ICampaignService
{

    protected $strategies = [
        'btgo' => SabahattinAliCampaign::class,
        'local_discount' => LocalAuthorCampaign::class,
        'total_discount' => OrderTotalDiscountCampaign::class,
    ];
    public function getAllCampaigns()
    {

        $cacheKey = 'all_campaigns';
        
        $campaigns = Cache::remember($cacheKey, 3600, function () {
            return Campaign::all();
        });

        return $campaigns;

    }

    public function getCampaignById(int $id): ?Campaign
    {
        $cacheKey = 'campaign_' . $id;
        
        $campaign = Cache::remember($cacheKey, 3600, function () use ($id) {
            return Campaign::findOrFail($id);
        });
        return $campaign;
    }

    public function createCampaign(Request $request): Campaign
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'conditions' => 'required|array',
                'discount_rate' => 'required|numeric|min:0|max:100',
            ]);

            $campaign = Campaign::create([
                'name' => $request->name,
                'type' => $request->type,
                'conditions' => $request->conditions,
                'discount_rate' => $request->discount_rate,
            ]);

            $cacheKey = 'campaign_' . $campaign->id;
            Cache::put($cacheKey, $campaign, 3600);

            return $campaign;
        
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Campaign Creation Error: " . $e->getMessage(), 500);
        }
    }

    public function updateCampaign(int $id, Request $request): bool
    {
        try {
            $campaign = $this->getCampaignById($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'conditions' => 'required|array',
                'discount_rate' => 'required|numeric|min:0|max:100',
            ]);

            $updated = $campaign->update([
                'name' => $request->name,
                'type' => $request->type,
                'conditions' => $request->conditions,
                'discount_rate' => $request->discount_rate,
            ]);

            if ($updated) {
                $cacheKey = 'campaign_' . $id;
                Cache::put($cacheKey, $campaign, 3600); 
            }

            return $updated;
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Campaign Update Error: " . $e->getMessage(), 500);
        }
    }

    public function deleteCampaign(int $id): bool
    {
        try {
            $campaign = $this->getCampaignById($id);
            $cacheKey = 'campaign_' . $campaign->id;
            Cache::forget($cacheKey);
            return $campaign->delete();
        } catch (Exception $e) {
            throw new Exception("Campaign Deletion Error: " . $e->getMessage(), 500);
        }
    }

    public function applyBestCampaign(Order $order): array
    {
        $cacheKey = 'all_campaigns';
        
        $campaigns = Cache::remember($cacheKey, 3600, function () {
            return Campaign::all();
        });
        $bestCampaign = null;
        $maxDiscount = 0;

        foreach ($campaigns as $campaign) {
            $campaignClass = $this->strategies[$campaign->type];
            $campaignInstance = new $campaignClass();
            $discount = $campaignInstance->calculateDiscount($order, $campaign);

            if ($discount > $maxDiscount) {
                $maxDiscount = $discount;
                $bestCampaign = $campaign;
            }
        }

        return [$bestCampaign, $maxDiscount];
    }
}
