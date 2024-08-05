<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), 
        [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roleUser = Role::where('name', 'user')->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleUser->id,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            "message" => "Registration Success",
            "user" => $user,
            "token" => $token,
        ], 201);
    }

    public function getUser() {
        $currentUser = auth()->user();

        $user = User::with(['role', 'historyBorrows'])->where('id', $currentUser->id)->first();

        return response()->json([
            "message" => "Get user was successful",
            "user" => $user,
        ]);
    }

    public function login(Request $request) {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                "message" => "User Invalid"
            ], 401);
        }

        $user = User::with('Role')->where('email', $request['email'])->first();
        $token = JWTAuth::fromUser($user);
        return response()->json([
            "user" => $user,
            "token" => $token,
        ]);
    }

    public function logout() {
        auth()->logout();

        return response()->json([
            "message" => "Logout Success"
        ]);
    }
}
