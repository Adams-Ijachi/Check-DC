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

//    public function register($request)
//    {
//        $request->validate([
//            'name' => 'required',
//            'email' => 'required|email',
//            'password' => 'required',
//            'c_password' => 'required|same:password',
//        ]);
//
//        $input = $request->all();
//        $input['password'] = bcrypt($input['password']);
//        $user = User::create($input);
//        $token = $user->createToken('authToken')->accessToken;
//        return response()->json(['token' => $token], 200);
//    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
    }

    public function user()
    {
        return response()->json(auth()->user());
    }
}
