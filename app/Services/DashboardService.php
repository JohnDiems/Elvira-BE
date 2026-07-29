<?php

namespace App\Services;

use App\Interface\Repositories\OrderRepositoryInterface;
use App\Interface\Repositories\ProductRepositoryInterface;
use App\Interface\Services\DashboardServiceInterface;
use Carbon\Carbon;

class DashboardService implements DashboardServiceInterface
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

    public function getSummary(int $userId)
    {
        $totalRevenue = $this->orderRepository->totalRevenue();
        $totalOrders = $this->orderRepository->totalOrdersCount();

        // 2. Weekly Sales (Last 7 Days, grouped by day)
        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayName = $date->shortEnglishDayOfWeek; // e.g. "Mon"
            
            $salesAmount = $this->orderRepository->salesForDate($date->toDateString());

            // Format day label for UI: M, T, W, T, F, S, S
            $shortLabel = substr($dayName, 0, 1);
            if ($dayName === 'Thursday') $shortLabel = 'T';
            if ($dayName === 'Sunday') $shortLabel = 'S';
            
            $weeklySales[] = [
                'day' => $dayName,
                'label' => $shortLabel,
                'amount' => floatval($salesAmount)
            ];
        }

        // 3. Low Stock Items (current_stock <= low_stock_threshold)
        $lowStockItems = $this->productRepository->lowStockList();

        // 4. Top Selling Products
        $topSelling = $this->orderRepository->getTopSellingProducts(5);

        return response()->json([
            'total_revenue' => floatval($totalRevenue),
            'total_orders' => $totalOrders,
            'weekly_sales' => $weeklySales,
            'low_stock' => $lowStockItems,
            'top_selling' => $topSelling
        ]);
    }
}
