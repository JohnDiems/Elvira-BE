<?php

namespace App\Interface\Repositories;

interface ProductRepositoryInterface
{
    public function findFiltered(object $payload);

    public function findById(int $id);

    public function create(object $payload);

    public function update(int $id, object $payload);

    public function delete(int $id);

    public function lowStockCount();

    public function lowStockList();
}
