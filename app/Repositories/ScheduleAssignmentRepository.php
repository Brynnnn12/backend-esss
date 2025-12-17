<?php

namespace App\Repositories;

use App\Models\ScheduleAssignment;
use Illuminate\Database\Eloquent\Collection;

class ScheduleAssignmentRepository
{
    public function create(array $data): ScheduleAssignment
    {
        return ScheduleAssignment::create($data);
    }

    public function find(string $id): ?ScheduleAssignment
    {
        return ScheduleAssignment::find($id);
    }

    public function delete(ScheduleAssignment $assignment): bool
    {
        return $assignment->delete();
    }

    public function getByScheduleId(string $scheduleId): Collection
    {
        return ScheduleAssignment::byScheduleId($scheduleId)->get();
    }

    public function getByUserId(string $userId): Collection
    {
        return ScheduleAssignment::byUserId($userId)->get();
    }
}
