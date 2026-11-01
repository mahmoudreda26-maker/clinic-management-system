<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementService
{
  
    public function getAllUsers()
    {
        return User::where('clinic_id', auth()->user()->clinic_id)
            ->get();
    }

  
    public function getUserById(string $id)
    {
        return User::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($id);
    }


    public function createUser(array $userData)
    {
        unset($userData['clinic_id']);

        $userData['clinic_id'] = auth()->user()->clinic_id;

        $userData['password'] = Hash::make($userData['password']);

        return User::create($userData);
    }


    public function updateUser(string $userId, array $userData)
    {
        $user = User::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($userId);

        unset($userData['clinic_id']);

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        return $user;
    }


    public function deleteUser(string $userId)
    {
        $user = User::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($userId);

        return $user->delete();
    }
}
