<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    protected $table = 'role_type';
    protected $fillable = [
        'name',
        'description',
        'is_deleted',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
        'deleted_at',
    ];

    public function roles()
    {
        return $this->hasMany(Role::class, 'role_type_id');
    }
}
