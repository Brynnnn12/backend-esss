<?php

namespace App\Services;

use App\Models\Department;
use App\Repositories\DepartmentRepository; // Langsung use Class-nya
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DepartmentService
{
    public function __construct(protected DepartmentRepository $departmentRepository) {}

    public function getAllDepartments(int $perPage = 10): LengthAwarePaginator
    {
        return $this->departmentRepository->getAllPaginated($perPage);
    }

    public function getDepartmentById(string $id): ?Department
    {
        return $this->departmentRepository->find($id);
    }

    public function createDepartment(array $data): Department
    {
        return $this->departmentRepository->create($data);
    }

    public function updateDepartment(Department $department, array $data): Department
    {
        return $this->departmentRepository->update($department, $data);
    }

    public function deleteDepartment(Department $department): bool
    {
        return $this->departmentRepository->delete($department);
    }

    public function searchDepartmentsByName(string $name): Collection
    {
        return $this->departmentRepository->searchByName($name);
    }
}
