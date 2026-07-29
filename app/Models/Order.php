<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Traits\UsesUuid;

#[Fillable([
    'uuid', 'cashier_id', 'customer_name', 'table_number', 'subtotal', 'tax_rate', 'tax_amount',
    'total_amount', 'payment_method', 'payment_provider', 'amount_received', 'change_amount', 'status'
])]
class Order extends Model
{
    use UsesUuid;

    protected $casts = [
        'subtotal' => 'float',
        'tax_rate' => 'float',
        'tax_amount' => 'float',
        'total_amount' => 'float',
        'amount_received' => 'float',
        'change_amount' => 'float',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
