<?php

namespace App\Interface\Services;

interface ProductServiceInterface
{
    public function listProducts(object $payload);

    public function listCategories();

    public function createProduct(object $payload);

    public function updateProduct(int $id, object $payload);

    public function deleteProduct(int $id);

    public function priceHistory(int $productId);
}
