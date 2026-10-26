<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:role,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password),
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

        //EAGER = Memakan Ram karena perlu memuat semua data relasi,
        //LAZY = Hanya memuat data relasi ketika dibutuhkan
        // $user = User::with('role.permission')->where('email',$request->email)->first();
        // $permissions = $user->role
        //                 ->flatMap(fn($role) => $role->permission)
        //                 ->pluck('code')
        //                 ->map(fn($code) => strtoupper($code))
        //                 ->unique()
        //                 ->values()
        //                 ->toArray();
        $user = User::where('email',$request->email)->first();
        $permissions = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('role_permission', 'user_role.role_id', '=', 'role_permission.role_id')
            ->join('permission', 'role_permission.permission_id', '=', 'permission.id')
            ->where('users.email', $request->email)
            ->where('user_role.is_active', true)
            ->where('user_role.start_date', '<=', now())
            ->where('user_role.end_date', '>=', now())
            ->select('permission.code')
            ->distinct()
            ->pluck('code')
            ->map(fn($code) => strtoupper($code))
            ->toArray();

        $token = $user->createToken(('auth-token'), $permissions)->plainTextToken;
        $data = UserResource::make($user);

        return response()->json([
            'user' => $data,
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
