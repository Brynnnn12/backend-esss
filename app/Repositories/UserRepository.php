<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Get all users.
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find user by ID.
     */
    public function find(string $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update user.
     */
    public function update(string $id, array $data): bool
    {
        $user = $this->find($id);
        return $user ? $user->update($data) : false;
    }

    /**
     * Delete user.
     */
    public function delete(string $id): bool
    {
        $user = $this->find($id);
        return $user ? $user->delete() : false;
    }
}
