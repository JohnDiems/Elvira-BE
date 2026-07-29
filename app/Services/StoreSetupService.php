<?php

namespace App\Services;

use App\Interface\Repositories\StoreConfigRepositoryInterface;
use App\Interface\Services\StoreSetupServiceInterface;

class StoreSetupService implements StoreSetupServiceInterface
{
    private $storeConfigRepository;

    public function __construct(StoreConfigRepositoryInterface $storeConfigRepository)
    {
        $this->storeConfigRepository = $storeConfigRepository;
    }

    public function saveStoreConfig(object $payload, int $userId)
    {
        $config = $this->storeConfigRepository->updateOrCreateForUser($userId, $payload);

        return response()->json([
            'message' => 'Store configured successfully',
            'config' => $config
        ]);
    }
}
