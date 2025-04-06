<?php

namespace App\Interfaces\Services;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(string $email, string $password): ?string;
}
