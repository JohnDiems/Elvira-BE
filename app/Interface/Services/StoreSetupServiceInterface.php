<?php

namespace App\Interface\Services;

interface StoreSetupServiceInterface
{
    public function saveStoreConfig(object $payload, int $userId);
}
