<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'department_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }

    // Scope untuk memuat relasi department
    public function scopeWithDepartment($query)
    {
        return $query->with('department');
    }

    //ini untuk mengambil semua schedule dengan relasi department danpaki 10 data
    public function scopeAllSchedules($query)
    {
        return $query->withDepartment()->take(10);
    }
    //ini untuk mengambil schedule berdasarkan department id dengan relasi department
    public function scopeSchedulesByDepartment($query, $departmentId)
    {
        return $query->withDepartment()->where('department_id', $departmentId);
    }

    //cari schedule berdasarkan tanggal
    public function scopeSchedulesByDate($query, $date)
    {
        return $query->withDepartment()->where('date', $date);
    }

    //ini untuk mengambil schedule berdasarkan id dengan relasi department
    public function scopeScheduleById($query, $id)
    {
        return $query->withDepartment()->where('id', $id)->first();
    }
}
