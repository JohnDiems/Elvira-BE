<?php

namespace App\Interface\Services;

interface AuthServiceInterface
{
    public function registerUser(object $payload);

    public function loginUser(object $payload);

    public function verifyOtp(object $payload);
}
