<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <--- Tambah ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids; // <--- Pakai Trait ini

    // HAPUS: public $incrementing = false; (Sudah diurus HasUuids)
    // HAPUS: protected $keyType = 'string'; (Sudah diurus HasUuids)

    // HAPUS: protected static function booted()... (Sudah diurus HasUuids)

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'role',
    ];

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getRoleNames()->first(),
        );
    }
}
