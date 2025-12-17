<?php

namespace App\Services;

use App\Models\ScheduleAssignment;
use App\Repositories\ScheduleAssignmentRepository;
use Illuminate\Database\Eloquent\Collection;

class ScheduleAssignmentService
{
    public function __construct(protected ScheduleAssignmentRepository $repository) {}

    public function assignEmployee(array $data): ScheduleAssignment
    {
        // Validasi bentrok per user per date sudah di request
        return $this->repository->create($data);
    }

    public function getAssignmentById(string $id): ?ScheduleAssignment
    {
        return $this->repository->find($id);
    }

    public function removeAssignment(ScheduleAssignment $assignment): bool
    {
        return $this->repository->delete($assignment);
    }

    public function getAssignmentsBySchedule(string $scheduleId): Collection
    {
        return $this->repository->getByScheduleId($scheduleId);
    }

    public function getAssignmentsByUser(string $userId): Collection
    {
        return $this->repository->getByUserId($userId);
    }
}
