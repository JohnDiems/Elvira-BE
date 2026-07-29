<?php

namespace App\Http\Controllers;

use App\Interface\Services\OrderServiceInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $this->orderService->listOrders($request, $user->id, $user->role);
        return response()->json($orders);
    }

    public function show($id)
    {
        $order = $this->orderService->getOrderDetails(intval($id));
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number' => 'nullable|string|max:50',
            'payment_method' => 'required|string|in:cash,credit_card,e_wallet',
            'payment_provider' => 'nullable|string|max:50',
            'amount_received' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $cashier = $request->user();

        try {
            $order = $this->orderService->processCheckout($request, $cashier->id);
            return response()->json([
                'message' => 'Order processed successfully',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
