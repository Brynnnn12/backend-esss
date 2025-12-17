<?php

namespace App\Policies;

use App\Models\ScheduleAssignment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScheduleAssignmentPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Hr')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ScheduleAssignment $scheduleAssignment): bool
    {
        return $scheduleAssignment->user_id === $user->id; // Employee bisa lihat assignment sendiri
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Handle di before
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ScheduleAssignment $scheduleAssignment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ScheduleAssignment $scheduleAssignment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ScheduleAssignment $scheduleAssignment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ScheduleAssignment $scheduleAssignment): bool
    {
        return false;
    }
}
