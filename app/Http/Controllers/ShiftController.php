<?php

namespace App\Http\Controllers;

use App\Interface\Services\ShiftServiceInterface;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    private $shiftService;

    public function __construct(ShiftServiceInterface $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function status(Request $request)
    {
        $user = $request->user();
        return $this->shiftService->getShiftStatus($user->id);
    }

    public function start(Request $request)
    {
        $user = $request->user();
        return $this->shiftService->startShift($user->id);
    }

    public function end(Request $request)
    {
        $user = $request->user();
        return $this->shiftService->endShift($user->id);
    }
}
