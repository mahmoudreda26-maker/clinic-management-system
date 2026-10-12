<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\Repositories\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }
}