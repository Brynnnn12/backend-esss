<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DepartmentRepository
{
    public function __construct(protected Department $model) {}
    /**
     * Get all departments with pagination.
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    /**
     * Find department by ID.
     */
    public function find(string $id): ?Department
    {
        return $this->model->find($id);
    }

    /**
     * Create a new department.
     */
    public function create(array $data): Department
    {
        return $this->model->create($data);
    }

    /**
     * Update department.
     */
    public function update(Department $department, array $data): Department
    {
        $department->update($data);
        return $department;
    }

    /**
     * Delete department.
     */
    public function delete(Department $department): bool
    {
        return $department->delete();
    }

    /**
     * Search departments by name.
     */
    public function searchByName(string $name): Collection
    {
        // Perbaikan: Menggunakan $this->model (bukan undefined property)
        return $this->model->where('name', 'LIKE', "%{$name}%")->get();
    }
}
