<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::factory()->state([
            'email' => 'admin@gmail.com',
        ])->create();

        $admin->assignRole(User::ROLE_ADMIN);
    }
}
