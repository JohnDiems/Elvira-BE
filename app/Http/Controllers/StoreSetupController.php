<?php

namespace App\Http\Controllers;

use App\Interface\Services\StoreSetupServiceInterface;
use Illuminate\Http\Request;

class StoreSetupController extends Controller
{
    private $storeSetupService;

    public function __construct(StoreSetupServiceInterface $storeSetupService)
    {
        $this->storeSetupService = $storeSetupService;
    }

    public function save(Request $request)
    {
        $request->validate([
            'business_type' => 'required|string|in:cafe,retail,restaurant',
            'locations_count' => 'required|string|in:1,2-5,6-10,10+',
            'is_migrating' => 'required|boolean',
        ]);

        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Only admins can configure store settings.'
            ], 403);
        }

        return $this->storeSetupService->saveStoreConfig($request, $user->id);
    }
}
