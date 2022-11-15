<?php


use App\Models\Plan;
use App\Models\User;

uses()->group('user_subscription');

beforeEach(function (){

    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');
    $this->artisan('db:seed --class=PlanSeeder');

});

test('A user can subscribe', function () {

    $user = User::first();
    $plan = Plan::first();

    $response = login($user,User::ROLE_READER)->post('/api/v1/user/plans/'.$plan->id.'/subscribe');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);


    expect($user->subscriptions()->first()->id)->toBe($plan->id);
});

test('A user can get his subscriptions', function () {

    $user = User::first();
    $plan = Plan::first();


    \App\Models\Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
    ]);


    $response = login($user,User::ROLE_READER)->get('/api/v1/user/subscriptions');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'plan',
                    'status'
                ]
            ]
        ]);


});
