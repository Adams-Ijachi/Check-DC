<?php


namespace App\Services;


use App\Models\User;

class UserService
{

    public function createUser(array $data): User
    {
        $user = User::create($data);
        $user->assignRole('reader');

        return $user;
    }

    public function updateUser(array $data, User $user): User
    {
        $user->update($data);
        return $user;
    }


}
