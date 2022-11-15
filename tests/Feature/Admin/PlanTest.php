<?php

namespace Tests\Feature\Admin;

use App\Models\Plan;
use App\Models\Status;
use App\Models\User;


uses()->group('admin_plans');

beforeEach(function (){

    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=PlanSeeder');

});

test('An Admin can get all plans', function () {

    login('',User::ROLE_ADMIN)->get('/api/v1/admin/plans')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    "id"
                ]
            ]
        ])->assertJsonCount(4, 'data');
});

test('An Admin can get a plan ', function () {
    $plan_id = Plan::first()->id;
    $plan = login('',User::ROLE_ADMIN)->get('/api/v1/admin/plans/'.$plan_id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
            ]
        ])->json('data');

    expect($plan['id'])->toBe($plan_id);

});

test('An Admin can create a plan ', function () {

    $plan_array = [
        "name" => "test",
        "duration" => 10,
        "status_id" => Status::first()->id,
    ];

    $response = login('',User::ROLE_ADMIN)->post('/api/v1/admin/plans', $plan_array);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                "id",
            ]
        ]);

    $plan_id = $response->json('data.id');

    $plan = Plan::find($plan_id);

    expect($plan)
        ->name->toBe($plan_array['name'])
        ->duration->toBe($plan_array['duration'])
        ->status_id->toBe($plan_array['status_id']);

});

test('An Admin can update a plan ', function () {

    $plan = Plan::first();

    $plan_array = [
        "name" => "test",
        "duration" => 10,
        "status_id" => Status::where('id', '!=', $plan->status_id)->first()->id,
    ];

    $response = login('',User::ROLE_ADMIN)->patch('/api/v1/admin/plans/'.$plan->id, $plan_array);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "duration",
                "status",
            ]
        ]);

    $plan = Plan::find($plan->id);

    expect($plan)
        ->name->toBe($plan_array['name'])
        ->duration->toBe($plan_array['duration'])
        ->status_id->toBe($plan_array['status_id']);

});

test('An Admin can delete a plan ', function () {

    $plan = Plan::first();

    $response = login('',User::ROLE_ADMIN)->delete('/api/v1/admin/plans/'.$plan->id);

    $response->assertStatus(200);

    $plan = Plan::find($plan->id);

    expect($plan)->toBeNull();

});
