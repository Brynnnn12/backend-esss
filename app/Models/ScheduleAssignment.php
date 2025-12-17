<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ScheduleAssignment extends Model
{
    use HasUuids;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'shift_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Scope untuk query berdasarkan schedule_id dengan eager loading
    public function scopeByScheduleId($query, $scheduleId)
    {
        return $query->where('schedule_id', $scheduleId)->with(['user', 'shift']);
    }

    // Scope untuk query berdasarkan user_id dengan eager loading
    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId)->with(['schedule.department', 'shift']);
    }
}
