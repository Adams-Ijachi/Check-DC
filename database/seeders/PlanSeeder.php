<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $status = Status::where('name', Status::STATUS_ACTIVE)->first()->id;
        \App\Models\Plan::create([
            'name' => 'Free',
            'duration' => 0,
            'status_id' => $status
        ]);
        \App\Models\Plan::create([
            'name' => 'Silver',
            'duration' => 30,
            'status_id' => $status
        ]);
        \App\Models\Plan::create([
            'name' => 'Bronze',
            'duration' => 30,
            'status_id' => $status
        ]);
        \App\Models\Plan::create([
            'name' => 'Gold',
            'duration' => 30,
            'status_id' => $status
        ]);
    }
}
