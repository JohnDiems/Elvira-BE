<?php

namespace App\Interface\Repositories;

interface UserRepositoryInterface
{
    public function findOneByEmail(string $email);

    public function findById(int $id);

    public function findByUuid(string $uuid);

    public function create(object $payload);

    public function count();
}
