<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Traits\UsesUuid;

#[Fillable(['uuid', 'name'])]
class Category extends Model
{
    use UsesUuid;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
