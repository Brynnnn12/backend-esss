<?php

namespace App\Services;

use App\Models\Shift;
use App\Repositories\ShiftRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShiftService
{
    public function __construct(protected ShiftRepository $shiftRepository) {}

    public function getAllShifts(int $perPage = 10): LengthAwarePaginator
    {
        return $this->shiftRepository->getAllPaginated($perPage);
    }

    public function getShiftById(string $id): ?Shift
    {
        return $this->shiftRepository->find($id);
    }

    public function createShift(array $data): Shift
    {
        return $this->shiftRepository->create($data);
    }

    public function updateShift(Shift $shift, array $data): Shift
    {
        return $this->shiftRepository->update($shift, $data);
    }

    public function deleteShift(Shift $shift): bool
    {
        return $this->shiftRepository->delete($shift);
    }

    public function searchShiftsByName(string $name): Collection
    {
        return $this->shiftRepository->searchByName($name);
    }
}
