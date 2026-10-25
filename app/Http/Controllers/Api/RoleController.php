<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = \App\Models\Role::where('is_deleted', false)->get();
        return response()->json($roles);
    }

    public function show($id)
    {
        $role = \App\Models\Role::where('is_deleted', false)->find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array|exists:permissions,id',
        ]);

        $role = \App\Models\Role::create($request->all());
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->input('permissions'));
        }

        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $role = \App\Models\Role::where('is_deleted', false)->find($id);
        
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role->update($request->all());
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }

        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = \App\Models\Role::where('is_deleted', false)->find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->update(['is_deleted' => true]);
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
