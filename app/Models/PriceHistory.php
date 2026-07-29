<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['product_id', 'old_price', 'new_price', 'reason'])]
class PriceHistory extends Model
{
    protected $casts = [
        'old_price' => 'float',
        'new_price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
