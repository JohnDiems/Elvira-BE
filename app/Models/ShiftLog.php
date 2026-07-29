<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Traits\UsesUuid;

#[Fillable(['uuid', 'user_id', 'started_at', 'ended_at', 'status'])]
class ShiftLog extends Model
{
    use UsesUuid;

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
