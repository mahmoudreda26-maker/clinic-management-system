<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserManagementRequest;
use App\Http\Requests\User\UpdateUserManagementRequest;
use App\Http\Resources\UserManagmentResource;
use App\Http\Traits\ApiResponse;
use App\Services\UserManagementService;

class UserManagementController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly UserManagementService $userManagementService
    ) {}

    public function getAllUsers()
    {
        $users = $this->userManagementService->getAllUsers();

        return $this->paginated(
            $users,
            UserManagmentResource::class,
            'Users retrieved successfully'
        );
    }

    public function getUserById(string $userId)
    {
        $user = $this->userManagementService->getUserById($userId);

        return $this->success(
            new UserManagmentResource($user),
            'User retrieved successfully'
        );
    }

    public function createUser(StoreUserManagementRequest $request)
    {
        $user = $this->userManagementService->createUser(
            $request->validated()
        );

        return $this->success(
            new UserManagmentResource($user),
            'User created successfully',
            201
        );
    }

    public function updateUser(UpdateUserManagementRequest $request, string $userId)
    {
        $user = $this->userManagementService->updateUser(
            $userId,
            $request->validated()
        );

        return $this->success(
            new UserManagmentResource($user),
            'User updated successfully'
        );
    }

    public function deleteUser(string $userId)
    {
        $this->userManagementService->deleteUser($userId);

        return $this->success(
            null,
            'User deleted successfully'
        );
    }
}
