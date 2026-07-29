<?php

namespace App\Interface\Services;

interface ShiftServiceInterface
{
    public function getShiftStatus(int $userId);

    public function startShift(int $userId);

    public function endShift(int $userId);
}
