<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::firstOrCreate([
           'name' => Status::STATUS_ACTIVE,
            'description' => 'Active Entity (User, Plan, etc.)',
        ]);

        Status::firstOrCreate([
           'name' => Status::STATUS_INACTIVE,
            'description' => 'Inactive Entity',
        ]);

        Status::firstOrCreate([
            'name' => Status::STATUS_BORROWED,
            'description' => 'Borrowed Entity (Book, etc.)',
        ]);

        Status::firstOrCreate([
           'name' => Status::STATUS_AVAILABLE,
            'description' => 'Available Entity (Book, etc.)',
        ]);
    }
}
