<?php

namespace App\Repositories;

use App\Interface\Repositories\ShiftLogRepositoryInterface;
use App\Models\ShiftLog;
use App\Models\Order;
use Carbon\Carbon;

class ShiftLogRepository implements ShiftLogRepositoryInterface
{
    public function findActiveForUser(int $userId)
    {
        return ShiftLog::where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

    public function ordersProcessedCountToday(int $userId)
    {
        return Order::where('cashier_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function startShift(int $userId)
    {
        return ShiftLog::create([
            'user_id' => $userId,
            'started_at' => Carbon::now(),
            'status' => 'active',
        ]);
    }

    public function endShift(int $shiftId)
    {
        $shift = ShiftLog::findOrFail($shiftId);
        $shift->ended_at = Carbon::now();
        $shift->status = 'ended';
        $shift->save();
        return $shift;
    }
}
