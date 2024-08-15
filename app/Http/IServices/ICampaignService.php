<?php

namespace App\Http\IServices;

use App\Models\Campaign;
use Illuminate\Http\Request;

interface ICampaignService
{
    public function getAllCampaigns();

    public function getCampaignById(int $id): ?Campaign;

    public function createCampaign(Request $request): Campaign;

    public function updateCampaign(int $id, Request $request): bool;

    public function deleteCampaign(int $id): bool;
}