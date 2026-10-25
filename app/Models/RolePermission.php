<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permission';
    protected $fillable = [
        'role_id',
        'permission_id',
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
}
