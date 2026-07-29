<?php

namespace App\Services;

use App\Interface\Repositories\OrderRepositoryInterface;
use App\Interface\Repositories\ProductRepositoryInterface;
use App\Interface\Services\OrderServiceInterface;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;
    private $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function listOrders(object $payload, int $userId, string $role)
    {
        if ($role === 'cashier') {
            return $this->orderRepository->getAllForCashier($userId);
        }
        return $this->orderRepository->getAll();
    }

    public function getOrderDetails(int $id)
    {
        return $this->orderRepository->findById($id);
    }

    public function processCheckout(object $payload, int $cashierId)
    {
        return DB::transaction(function () use ($payload, $cashierId) {
            $subtotal = 0;
            $itemsToCreate = [];

            // Calculate subtotal and verify stock levels
            foreach ($payload->items as $itemData) {
                // Convert array to object if necessary
                $itemObj = (object) $itemData;
                $product = $this->productRepository->findById($itemObj->product_id);
                
                if (!$product) {
                    throw new \Exception("Product not found");
                }

                $quantity = $itemObj->quantity;
                $itemSubtotal = $product->selling_price * $quantity;
                $subtotal += $itemSubtotal;

                // Inventory validation and stock deduction
                if ($product->track_inventory) {
                    if ($product->current_stock < $quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Remaining: {$product->current_stock}.");
                    }
                    
                    // Deduct stock using repository update method
                    $newStock = $product->current_stock - $quantity;
                    $this->productRepository->update($product->id, (object)['current_stock' => $newStock]);
                }

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->selling_price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Compute tax and totals
            $taxRate = 0.11; // 11% standard PPN
            $taxAmount = $subtotal * $taxRate;
            $totalAmount = $subtotal + $taxAmount;
            
            $changeAmount = $payload->amount_received - $totalAmount;
            if ($changeAmount < 0 && $payload->payment_method === 'cash') {
                throw new \Exception("Amount received is less than total order amount.");
            }

            // Create main order record
            $order = $this->orderRepository->createOrder(
                $cashierId,
                $payload,
                $subtotal,
                $taxAmount,
                $totalAmount,
                max(0, $changeAmount)
            );

            // Create associated order item records
            foreach ($itemsToCreate as $item) {
                $this->orderRepository->createOrderItem(
                    $order->id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['subtotal']
                );
            }

            return $order->load(['cashier', 'items.product']);
        });
    }
}
