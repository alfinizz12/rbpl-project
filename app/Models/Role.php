<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $primaryKey = 'roleId';

    protected $fillable = ['role_type'];

    public function users()
    {
        return $this->hasMany(User::class, 'roleId');
    }
}
