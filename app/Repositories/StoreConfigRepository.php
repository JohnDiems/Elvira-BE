<?php

namespace App\Repositories;

use App\Interface\Repositories\StoreConfigRepositoryInterface;
use App\Models\StoreConfig;

class StoreConfigRepository implements StoreConfigRepositoryInterface
{
    public function updateOrCreateForUser(int $userId, object $payload)
    {
        return StoreConfig::updateOrCreate(
            ['user_id' => $userId],
            [
                'business_type' => $payload->business_type,
                'locations_count' => $payload->locations_count,
                'is_migrating' => $payload->is_migrating,
            ]
        );
    }
}
