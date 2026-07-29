<?php

namespace App\Repositories;

use App\Interface\Repositories\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function findFiltered(object $payload)
    {
        $query = Product::with('category');

        if (isset($payload->category_id) && $payload->category_id !== null) {
            $query->where('category_id', $payload->category_id);
        }

        if (isset($payload->search) && $payload->search !== null) {
            $query->where('name', 'like', "%{$payload->search}%");
        }

        return $query->get();
    }

    public function findById(int $id)
    {
        return Product::find($id);
    }

    public function create(object $payload)
    {
        return Product::create([
            'category_id' => $payload->category_id,
            'name' => $payload->name,
            'selling_price' => $payload->selling_price,
            'cost_price' => $payload->cost_price,
            'image_path' => $payload->image_path ?? null,
            'track_inventory' => $payload->track_inventory,
            'current_stock' => $payload->track_inventory ? ($payload->current_stock ?? 0) : 0,
            'low_stock_threshold' => $payload->track_inventory ? ($payload->low_stock_threshold ?? 10) : 0,
        ]);
    }

    public function update(int $id, object $payload)
    {
        $product = Product::findOrFail($id);
        
        $updateData = [];
        if (property_exists($payload, 'category_id') || isset($payload->category_id)) $updateData['category_id'] = $payload->category_id;
        if (property_exists($payload, 'name') || isset($payload->name)) $updateData['name'] = $payload->name;
        if (property_exists($payload, 'selling_price') || isset($payload->selling_price)) $updateData['selling_price'] = $payload->selling_price;
        if (property_exists($payload, 'cost_price') || isset($payload->cost_price)) $updateData['cost_price'] = $payload->cost_price;
        if (property_exists($payload, 'image_path') || isset($payload->image_path)) $updateData['image_path'] = $payload->image_path;
        if (property_exists($payload, 'track_inventory') || isset($payload->track_inventory)) $updateData['track_inventory'] = $payload->track_inventory;
        if (property_exists($payload, 'current_stock') || isset($payload->current_stock)) $updateData['current_stock'] = $payload->current_stock;
        if (property_exists($payload, 'low_stock_threshold') || isset($payload->low_stock_threshold)) $updateData['low_stock_threshold'] = $payload->low_stock_threshold;
        
        $product->update($updateData);
        return $product;
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return true;
    }

    public function lowStockCount()
    {
        return Product::where('track_inventory', true)
            ->whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->count();
    }

    public function lowStockList()
    {
        return Product::with('category')
            ->where('track_inventory', true)
            ->whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->get();
    }
}
