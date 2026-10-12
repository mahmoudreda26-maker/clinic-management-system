<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Repositories\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function login(array $credentials): ?array
    {
        $user = $this->userRepository->findByEmail(
            $credentials['email']
        );

        if (
            !$user ||
            !Hash::check($credentials['password'], $user->password) ||
            !$user->is_active ||
            !$user->clinic_id
        ) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function me(User $user): User
    {
        return $user;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
