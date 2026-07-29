<?php

namespace App\Interface\Services;

interface OrderServiceInterface
{
    public function listOrders(object $payload, int $userId, string $role);

    public function getOrderDetails(int $id);

    public function processCheckout(object $payload, int $cashierId);
}
