<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('is_deleted', false)->get();
        return response()->json($roles);
    }

    public function show($id)
    {
        $role = Role::where('is_deleted', false)
                ->where('id', $id)
                ->first();
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->load('roleType');
        $role->load('permission');
        return response()->json($role);
    }

    public function store(StoreRoleRequest $request)
    {
        $request->validated();

        $role = Role::create($request->all());
        if ($request->has('permission')) {
            $role->permission()->attach($request->input('permission'));
        }
        $role->load('roleType');
        $role->load('permission');

        return response()->json($role, 201);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $request->validated();
        $role = Role::where('is_deleted', false)->find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->update($request->all());
        if ($request->has('permission')) {
            $role->permission()->sync($request->input('permission'));
        }

        $role->load('roleType');
        $role->load('permission');

        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = Role::where('is_deleted', false)->find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->update(['is_deleted' => true]);
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
