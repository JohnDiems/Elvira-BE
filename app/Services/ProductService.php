<?php

namespace App\Services;

use App\Interface\Repositories\ProductRepositoryInterface;
use App\Interface\Repositories\CategoryRepositoryInterface;
use App\Interface\Services\ProductServiceInterface;
use App\Models\PriceHistory;

class ProductService implements ProductServiceInterface
{
    private $productRepository;
    private $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function listProducts(object $payload)
    {
        return $this->productRepository->findFiltered($payload);
    }

    public function listCategories()
    {
        return $this->categoryRepository->all();
    }

    public function createProduct(object $payload)
    {
        $product = $this->productRepository->create($payload);

        // Record initial price history
        PriceHistory::create([
            'product_id' => $product->id,
            'old_price' => $product->selling_price,
            'new_price' => $product->selling_price,
            'reason' => 'Initial Setup',
        ]);

        return $product;
    }

    public function updateProduct(int $id, object $payload)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new \Exception("Product not found");
        }

        $oldPrice = $product->selling_price;
        $newPrice = isset($payload->selling_price) ? floatval($payload->selling_price) : $oldPrice;

        $updatedProduct = $this->productRepository->update($id, $payload);

        // Record price history if price changed
        if ($oldPrice != $newPrice) {
            PriceHistory::create([
                'product_id' => $updatedProduct->id,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'reason' => $payload->price_change_reason ?? 'Price Adjustment',
            ]);
        }

        return $updatedProduct;
    }

    public function deleteProduct(int $id)
    {
        return $this->productRepository->delete($id);
    }

    public function priceHistory(int $productId)
    {
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new \Exception("Product not found");
        }
        return PriceHistory::where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
