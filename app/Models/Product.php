<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Traits\UsesUuid;

#[Fillable(['uuid', 'category_id', 'name', 'selling_price', 'cost_price', 'image_path', 'track_inventory', 'current_stock', 'low_stock_threshold'])]
class Product extends Model
{
    use UsesUuid;

    protected $casts = [
        'track_inventory' => 'boolean',
        'selling_price' => 'float',
        'cost_price' => 'float',
        'current_stock' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class)->orderBy('created_at', 'desc');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
