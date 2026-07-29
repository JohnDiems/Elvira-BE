<?php

namespace App\Interface\Repositories;

interface ShiftLogRepositoryInterface
{
    public function findActiveForUser(int $userId);

    public function ordersProcessedCountToday(int $userId);

    public function startShift(int $userId);

    public function endShift(int $shiftId);
}
