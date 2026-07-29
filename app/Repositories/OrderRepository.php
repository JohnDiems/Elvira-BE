<?php

namespace App\Repositories;

use App\Interface\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAllForCashier(int $cashierId)
    {
        return Order::with(['cashier', 'items.product'])
            ->where('cashier_id', $cashierId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAll()
    {
        return Order::with(['cashier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(int $id)
    {
        return Order::with(['cashier', 'items.product.category'])->findOrFail($id);
    }

    public function createOrder(
        int $cashierId,
        object $payload,
        float $subtotal,
        float $taxAmount,
        float $totalAmount,
        float $changeAmount
    ) {
        return Order::create([
            'cashier_id' => $cashierId,
            'customer_name' => $payload->customer_name,
            'table_number' => $payload->table_number ?? null,
            'subtotal' => $subtotal,
            'tax_rate' => 0.11,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $payload->payment_method,
            'payment_provider' => $payload->payment_provider ?? null,
            'amount_received' => $payload->amount_received,
            'change_amount' => $changeAmount,
            'status' => 'completed',
        ]);
    }

    public function createOrderItem(
        int $orderId,
        int $productId,
        int $quantity,
        float $unitPrice,
        float $subtotal
    ) {
        return OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
        ]);
    }

    public function totalRevenue()
    {
        return Order::where('status', 'completed')->sum('total_amount');
    }

    public function totalOrdersCount()
    {
        return Order::where('status', 'completed')->count();
    }

    public function salesForDate(string $date)
    {
        return Order::where('status', 'completed')
            ->whereDate('created_at', $date)
            ->sum('total_amount');
    }

    public function getTopSellingProducts(int $limit)
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->with('product.category')
            ->get()
            ->map(function ($item) {
                return [
                    'product' => $item->product,
                    'total_sold' => intval($item->total_sold)
                ];
            });
    }
}
