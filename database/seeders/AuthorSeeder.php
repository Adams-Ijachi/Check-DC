<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = User::factory(10)->create();
        // give admin role to all users
        foreach ($admins as $admin) {
            $admin->assignRole(User::ROLE_AUTHOR);
        }




    }
}
