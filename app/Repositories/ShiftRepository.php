<?php

namespace App\Repositories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShiftRepository
{
    public function __construct(protected Shift $model) {}

    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    // Gunakan string|int untuk fleksibilitas ID
    public function find(string|int $id): ?Shift
    {
        return $this->model->find($id);
    }

    public function create(array $data): Shift
    {
        return $this->model->create($data);
    }

    public function update(Shift $shift, array $data): Shift
    {
        // Menggunakan tap() agar code lebih one-liner dan clean (optional style)
        return tap($shift)->update($data);
    }

    public function delete(Shift $shift): bool
    {
        // Mengembalikan bool (true jika berhasil, null/false jika gagal)
        return (bool) $shift->delete();
    }

    public function searchByName(string $name): Collection
    {
        return $this->model->where('name', 'LIKE', "%{$name}%")->get();
    }
}
