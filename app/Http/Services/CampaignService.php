<?php

namespace App\Http\Services;

use App\Http\IServices\ICampaignService;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class CampaignService implements ICampaignService
{
    public function getAllCampaigns()
    {
        return Campaign::all();
    }

    public function getCampaignById(int $id): ?Campaign
    {
        return Campaign::findOrFail($id);
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

            return Campaign::create([
                'name' => $request->name,
                'type' => $request->type,
                'conditions' => $request->conditions,
                'discount_rate' => $request->discount_rate,
            ]);
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

            return $campaign->update([
                'name' => $request->name,
                'type' => $request->type,
                'conditions' => $request->conditions,
                'discount_rate' => $request->discount_rate,
            ]);
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

            return $campaign->delete();
        } catch (Exception $e) {
            throw new Exception("Campaign Deletion Error: " . $e->getMessage(), 500);
        }
    }
}
