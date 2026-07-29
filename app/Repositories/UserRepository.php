<?php

namespace App\Repositories;

use App\Interface\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function findOneByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id)
    {
        return User::find($id);
    }

    public function findByUuid(string $uuid)
    {
        return User::where('uuid', $uuid)->first();
    }

    public function create(object $payload)
    {
        return User::create([
            'name' => $payload->name,
            'email' => $payload->email,
            'password' => Hash::make($payload->password),
        ]);
    }

    public function count()
    {
        return User::count();
    }
}
