<?php


namespace App\Services;


use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Auth;
use Hash;

class AuthService
{



    /**
     * @throws InvalidCredentialsException
     */
    public function login(User $user, string $password): string
    {
        if (!Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException('Invalid credentials', 401);
        }

        return $user->createToken('authToken')->plainTextToken;
    }



    public function logout()
    {
        Auth::user()->tokens()->delete();
    }

    public function user()
    {
        return response()->json(auth()->user());
    }
}
