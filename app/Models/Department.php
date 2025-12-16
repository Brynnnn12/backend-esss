<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];


    //scope untuk mencari department berdasarkan nama
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
}
