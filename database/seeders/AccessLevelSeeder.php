<?php

namespace Database\Seeders;

use App\Models\AccessLevel;
use Illuminate\Database\Seeder;

class AccessLevelSeeder extends Seeder
{
    public function run()
    {
        AccessLevel::firstOrCreate([
            'name' => 'Children',
            'min_age' => 7,
            'max_age' => 15,
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Children Exclusive',
            'min_age' => 15,
            'max_age' => 24,
            'borrowing_points' => 10
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Youth',
            'min_age' => 15,
            'max_age' => 24,
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Youth Exclusive',
            'min_age' => 15,
            'max_age' => 24,
            'borrowing_points' => 15
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Adult',
            'min_age' => 25,
            'max_age' => 49,
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Adult Exclusive',
            'min_age' => 25,
            'max_age' => 49,
            'borrowing_points' => 20
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Senior',
            'min_age' => 50,
        ]);

        AccessLevel::firstOrCreate([
            'name' => 'Senior Exclusive',
            'min_age' => 50,
            'borrowing_points' => 10
        ]);
    }
}
