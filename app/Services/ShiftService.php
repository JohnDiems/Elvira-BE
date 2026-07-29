<?php

namespace App\Services;

use App\Interface\Repositories\ShiftLogRepositoryInterface;
use App\Interface\Services\ShiftServiceInterface;
use Carbon\Carbon;

class ShiftService implements ShiftServiceInterface
{
    private $shiftLogRepository;

    public function __construct(ShiftLogRepositoryInterface $shiftLogRepository)
    {
        $this->shiftLogRepository = $shiftLogRepository;
    }

    public function getShiftStatus(int $userId)
    {
        $activeShift = $this->shiftLogRepository->findActiveForUser($userId);
        $ordersTodayCount = $this->shiftLogRepository->ordersProcessedCountToday($userId);

        return response()->json([
            'active_shift' => $activeShift,
            'orders_processed_today' => $ordersTodayCount,
            'shift_started_at' => $activeShift ? $activeShift->started_at->toIso8601String() : null,
            'server_time' => Carbon::now()->toIso8601String(),
        ]);
    }

    public function startShift(int $userId)
    {
        $activeShift = $this->shiftLogRepository->findActiveForUser($userId);

        if ($activeShift) {
            return response()->json([
                'message' => 'Shift is already active.',
                'shift' => $activeShift
            ]);
        }

        $shift = $this->shiftLogRepository->startShift($userId);

        return response()->json([
            'message' => 'Shift started successfully',
            'shift' => $shift
        ], 201);
    }

    public function endShift(int $userId)
    {
        $activeShift = $this->shiftLogRepository->findActiveForUser($userId);

        if (!$activeShift) {
            return response()->json([
                'message' => 'No active shift found to end.'
            ], 422);
        }

        $shift = $this->shiftLogRepository->endShift($activeShift->id);

        return response()->json([
            'message' => 'Shift ended successfully',
            'shift' => $shift
        ]);
    }
}
