<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'schedule' => [
                'date' => $this->schedule->date,
                'department' => $this->schedule->department->name ?? null,
            ],
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'shift' => [
                'name' => $this->shift->name,
                'start_time' => $this->shift->start_time,
                'end_time' => $this->shift->end_time,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
