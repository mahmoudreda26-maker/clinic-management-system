<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->validated()
        );

        if (!$result) {
            return $this->error(
                'بيانات الدخول غير صحيحة أو الحساب غير متاح.',
                401
            );
        }

        return $this->success([
            'user' => (new UserResource($result['user']))
                ->resolve($request),
            'token' => $result['token'],
            'token_type' => 'Bearer',
        ], 'تم تسجيل الدخول بنجاح.');
    }

    public function me(Request $request)
    {
        $user = $this->authService->me($request->user());

        return $this->success(
            (new UserResource($user))->resolve($request),
            'تم جلب بيانات المستخدم بنجاح.'
        );
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success(
            null,
            'تم تسجيل الخروج بنجاح.'
        );
    }
}
