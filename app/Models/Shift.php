<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    //scope
    //ini untuk mengambil shift yang mulai sebelum waktu tertentu
    public function scopeStartingBefore($query, $time)
    {
        return $query->where('start_time', '<', $time);
    }

    //ini untuk mengambil shift yang berakhir setelah waktu tertentu
    public function scopeEndingAfter($query, $time)
    {
        return $query->where('end_time', '>', $time);
    }

    public function scheduleAssignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }
}
