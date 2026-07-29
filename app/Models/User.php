<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\UsesUuid;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['uuid', 'name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UsesUuid, HasRoles;

    protected $appends = ['role'];

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first() ?? 'cashier';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function storeConfig()
    {
        return $this->hasOne(StoreConfig::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'cashier_id');
    }

    public function shiftLogs()
    {
        return $this->hasMany(ShiftLog::class);
    }
}
