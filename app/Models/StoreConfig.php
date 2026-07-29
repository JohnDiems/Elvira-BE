<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Traits\UsesUuid;

#[Fillable(['uuid', 'user_id', 'business_type', 'locations_count', 'is_migrating'])]
class StoreConfig extends Model
{
    use UsesUuid;

    protected $casts = [
        'is_migrating' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
