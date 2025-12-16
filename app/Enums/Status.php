<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }
}
