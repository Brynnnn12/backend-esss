<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        // Assign default role
        $user->assignRole('user');

        return $user;
    }

    /**
     * Get user profile.
     */
    public function getProfile(string $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(string $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }
}
