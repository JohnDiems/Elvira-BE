<?php

namespace App\Http\Controllers;

use App\Interface\Services\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->listProducts($request);
        return response()->json($products);
    }

    public function categories()
    {
        $categories = $this->productService->listCategories();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'image_path' => 'nullable|string',
            'track_inventory' => 'required|boolean',
            'current_stock' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $product = $this->productService->createProduct($request);
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'selling_price' => 'sometimes|required|numeric|min:0',
            'cost_price' => 'sometimes|required|numeric|min:0',
            'image_path' => 'nullable|string',
            'track_inventory' => 'sometimes|required|boolean',
            'current_stock' => 'sometimes|required|integer|min:0',
            'low_stock_threshold' => 'sometimes|required|integer|min:0',
            'price_change_reason' => 'nullable|string',
        ]);

        $product = $this->productService->updateProduct(intval($id), $request);
        return response()->json($product);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->productService->deleteProduct(intval($id));
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function priceHistory($id)
    {
        $history = $this->productService->priceHistory(intval($id));
        return response()->json($history);
    }
}
