<?php

namespace App\Http\Controllers;

use App\Http\IServices\ICampaignService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(ICampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index()
    {
        return response()->json($this->campaignService->getAllCampaigns());
    }

    public function show(int $id)
    {
        try {
            $campaign = $this->campaignService->getCampaignById($id);
            return response()->json($campaign);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $campaign = $this->campaignService->createCampaign($request);
            return response()->json($campaign, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id, Request $request)
    {
        try {
            $this->campaignService->updateCampaign($id, $request);
            return response()->json(['message' => 'Campaign updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->campaignService->deleteCampaign($id);
            return response()->json(['message' => 'Campaign deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
