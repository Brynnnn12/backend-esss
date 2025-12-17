<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleRepository
{
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Schedule::withDepartment()->paginate($perPage);
    }

    public function find(string $id): ?Schedule
    {
        return Schedule::withDepartment()->find($id);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);
        return $schedule;
    }

    public function delete(Schedule $schedule): bool
    {
        return $schedule->delete();
    }

    public function searchByDepartmentId(string $departmentId): Collection
    {
        return Schedule::schedulesByDepartment($departmentId)->get();
    }
}
