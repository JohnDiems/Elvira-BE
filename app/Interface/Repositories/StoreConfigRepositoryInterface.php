<?php

namespace App\Interface\Repositories;

interface StoreConfigRepositoryInterface
{
    public function updateOrCreateForUser(int $userId, object $payload);
}
