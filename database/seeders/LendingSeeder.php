<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Lending;
use App\Models\User;
use Illuminate\Database\Seeder;

class LendingSeeder extends Seeder
{
    public function run()
    {

        $users = User::limit(3)->get();


        $users->each(function ($user)  {
            $user->lendings()->saveMany(Lending::factory()->count(3)->make());
        });


    }


}
