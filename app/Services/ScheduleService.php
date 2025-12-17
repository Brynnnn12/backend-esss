<?php

namespace App\Services;

use App\Models\Schedule;;

use App\Repositories\ScheduleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ScheduleService
{
    public function __construct(protected ScheduleRepository $scheduleRepository) {}

    public function getAllSchedules(int $perPage = 10): LengthAwarePaginator
    {
        return $this->scheduleRepository->getAllPaginated($perPage);
    }

    public function getScheduleById(string $id): ?Schedule
    {
        return $this->scheduleRepository->find($id);
    }

    public function createSchedule(array $data): Schedule
    {
        return $this->scheduleRepository->create($data);
    }

    public function updateSchedule(Schedule $schedule, array $data): Schedule
    {
        return $this->scheduleRepository->update($schedule, $data);
    }

    public function deleteSchedule(Schedule $schedule): bool
    {
        return $this->scheduleRepository->delete($schedule);
    }

    public function searchSchedulesByDepartmentId(string $departmentId): Collection
    {
        return $this->scheduleRepository->searchByDepartmentId($departmentId);
    }
}
