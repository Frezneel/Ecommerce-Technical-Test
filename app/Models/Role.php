<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $fillable = [
        'name',
        'description',
        'type_role_id',
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

    public function roleType()
    {
        return $this->belongsTo(RoleType::class, 'type_role_id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }

    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

}
