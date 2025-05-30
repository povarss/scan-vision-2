<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create user with expiration date
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'expire_at' => $request->expire_at,
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    // Login a user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        $isArchived = false;
        if (!$user) {
            $isArchived = $user->hasRole(User::ROLE_PATIENT) && $user->patient->is_archived;
        }
        // $user->password =
        // Check if the user exists, if password is correct, and if access has expired
        if (!$user || !Hash::check($request->password, $user->password) || $isArchived) {
            throw ValidationException::withMessages(['email' => __('messages.InvalidCredentials')]);
            // return response()->json(['errors' => 'Invalid credentials'], 401);
        }

        // if ($this->checkIsExpired($user)) {
        //     throw ValidationException::withMessages(['email' => __('messages.UserAccessHasExpired')]);
        //     // return response()->json(['error' => 'User access has expired'], 403);
        // }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'userData' => [
                'id' => $user->id,
                'fullName' => $user->full_name,
                'role' => $user->roles->first()->name
            ],
            'userAbilityRules' => [
                [
                    'action' => 'manage',
                    'subject' => 'all',
                ]
            ]
        ], 200);
    }

    public function checkIsExpired($user)
    {
        return !empty($user->expire_at) && $user->expire_at < now();
    }
    // Logout a user
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
