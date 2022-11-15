<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TagSeeder::class,
            CategorySeeder::class,
            RoleSeeder::class,
            StatusSeeder::class,
            AdminSeeder::class,
            AccessLevelSeeder::class,
            PlanSeeder::class,
            AuthorSeeder::class,
            ReaderSeeder::class,
            BookSeeder::class,
            LendingSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
