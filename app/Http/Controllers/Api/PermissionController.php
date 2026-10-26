<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('is_deleted', false)->get();
        return response()->json($permissions);
    }

    public function show($id)
    {
        $permission = Permission::where('is_deleted', false)->find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        return response()->json($permission);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create($request->all());
        return response()->json($permission, 201);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::where('is_deleted', false)->find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'nullable|string|max:255',
        ]);

        $permission->update($request->all());
        return response()->json($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::where('is_deleted', false)->find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $permission->update(['is_deleted' => true]);
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
