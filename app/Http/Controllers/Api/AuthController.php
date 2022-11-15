<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginFormRequest;
use App\Http\Requests\Api\RegisterUserFormRequest;
use App\Http\Resources\UserResource;
use App\Models\Status;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Auth;
use Hash;


class AuthController extends Controller
{
    public function register(RegisterUserFormRequest $request)
    {
        $user = app(UserService::class)->createUser($request->validated());

        $token = $user->createToken('authToken')->plainTextToken;

        return UserResource::make($user->load('roles'))->additional([
            'token' => $token,
            'message' => 'User registered successfully'
        ]);
    }
    public function login(LoginFormRequest $request)
    {
        try {

            $user = User::where('email', $request->safe()->email)->first();
            $token = app(AuthService::class)->login($user, $request->safe()->password);
            return UserResource::make($user->load('roles'))->additional([
                'token' => $token,
                'message' => 'User logged in successfully'
            ]);

        }catch (InvalidCredentialsException $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 401);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }
}
