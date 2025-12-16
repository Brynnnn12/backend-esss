<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Method ini berjalan SEBELUM method lainnya.
     * Jika return true, maka user diizinkan melakukan apa saja di policy ini.
     * Jika return null, maka akan lanjut mengecek method view/create/update di bawah.
     */
    public function before(User $user, $ability)
    {
        // Pastikan nama role sesuai dengan yang di database ('hr' atau 'Hr')
        if ($user->hasRole('Hr')) {
            return true;
        }

        // Jika bukan HR, biarkan lanjut ke pengecekan method di bawah
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // HR sudah lolos di "before", jadi method ini hanya untuk user NON-HR
        // Misalnya: return true; (kalau user biasa boleh lihat list department)
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Department $department): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Department $department): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Department $department): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Department $department): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return false;
    }
}
