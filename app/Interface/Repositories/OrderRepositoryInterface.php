<?php

namespace App\Interface\Repositories;

interface OrderRepositoryInterface
{
    public function getAllForCashier(int $cashierId);

    public function getAll();

    public function findById(int $id);

    public function createOrder(int $cashierId, object $payload, float $subtotal, float $taxAmount, float $totalAmount, float $changeAmount);

    public function createOrderItem(int $orderId, int $productId, int $quantity, float $unitPrice, float $subtotal);

    public function totalRevenue();

    public function totalOrdersCount();

    public function salesForDate(string $date);

    public function getTopSellingProducts(int $limit);
}
