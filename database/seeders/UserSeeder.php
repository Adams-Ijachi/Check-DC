<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = User::factory(20)->create();

        foreach ($users as $user) {
            $user->assignRole(User::ROLE_READER);
        }


    }
}
