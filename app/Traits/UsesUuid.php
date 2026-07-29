<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    /**
     * The "booting" method of the model.
     * Generates a UUID for new models automatically.
     */
    public static function bootUsesUuid(): void
    {
        self::creating(function ($model): void {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
