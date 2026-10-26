<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_deleted', false)->get();
        $output = UserResource::collection($users);
        return response()->json($output);
    }

    public function show($id)
    {

        $user = User::where('is_deleted', false)->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $request->validated();
        $user = User::where('is_deleted', false)->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([

        ]);

        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::where('is_deleted', false)->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update(['is_deleted' => true]);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
