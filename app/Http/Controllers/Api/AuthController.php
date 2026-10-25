<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Permission;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password),
            'role_id' => 'request->role_id', // Assign the user role
        ]);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'message' => 'User Registered Successfully',
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid login email or password'
            ], 401);
        }

        $user = User::with('role.permission')->where('email',$request->email)->first();
        $permissions = $user->role
                        ->flatMap(fn($role) => $role->permission)
                        ->pluck('code')
                        ->map(fn($code) => strtoupper($code))
                        ->unique()
                        ->values()
                        ->toArray();

        $token = $user->createToken(('auth-token'), $permissions)->plainTextToken;

        return response()->json([
            'user' => $user->only('id', 'name', 'email'),
            'permissions' => $permissions,
            'role' => $user->role->pluck('name'),
            'token' => $token,
            'message' => 'User Logged In Successfully'
        ]);

    }

    public function logout(Request $request){
        request()->user()->tokens()->delete();

        return response()->json([
            'message' => 'User Logged Out Successfully'
        ]);
    }

}
