<?php

namespace App\Http\Controllers;

use App\Interface\Services\DashboardServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function summary(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->dashboardService->getSummary($user->id);
    }
}
