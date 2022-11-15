<?php

namespace Tests\Feature\Admin;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

uses()->group('admin_users');

beforeEach(function (){

    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=UserSeeder');

});


test("A Admin can get all users", function () {

    login('',User::ROLE_ADMIN)->get('/api/v1/admin/users')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                ]
            ]
        ])->assertJsonCount(15, 'data');
});

test("A Admin can get a user ", function () {
    $user_id = User::first()->id;
    $user = login('',User::ROLE_ADMIN)->get('/api/v1/admin/users/'.$user_id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
            ]
        ])->json('data');

    expect($user['id'])->toBe($user_id);

});

test("A Admin can create a user ", function () {

    $user_data = [
        "first_name" => fake()->firstName,
        "last_name" => fake()->lastName,
        "age" => 10,
        "address" => fake()->address,
        "username" => fake()->userName,
        "email" => fake()->email,
        "status_id" => Status::first()->id,
        "password" => fake()->password,
    ];

    $user = login('', User::ROLE_ADMIN)->post('/api/v1/admin/users', $user_data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                "id",
            ]
        ])->json('data');

    expect(User::find($user['id']))
        ->id->toBe($user['id'])
        ->roles->first()->name->toBe(User::ROLE_READER);

});

test("A Admin can update a user ", function () {

    $currentUser = User::first();
    $user_data = [
        "first_name" => "test",
        "last_name" => fake()->lastName,
        "age" => 10,
        "address" => fake()->address,
        "username" => fake()->userName,
        "email" => fake()->email,
        "status_id" => Status::first()->id,
    ];

    $user = login('', User::ROLE_ADMIN)->put('/api/v1/admin/users/'.$currentUser->id, $user_data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
            ]
        ])->json('data');

    expect(User::find($user['id']))
        ->first_name->toBe($user_data['first_name'])
        ->roles->first()->name->toBe(User::ROLE_READER);

});

test("A Admin can delete a user ", function () {

    $user = User::first();

    login('', User::ROLE_ADMIN)->delete('/api/v1/admin/users/'.$user->id)
        ->assertStatus(200);

    expect(User::find($user->id))->toBeNull();

});

test("A user with role reader can't get all users", function () {

    login('',User::ROLE_READER)->get('/api/v1/admin/users')
        ->assertStatus(403);

});

test("A user with role reader can't get a user ", function () {
    $user_id = User::first()->id;
    login('',User::ROLE_READER)->get('/api/v1/admin/users/'.$user_id)
        ->assertStatus(403);

});

test("A user with role reader can't create a user ", function () {

    $user_data = [
        "first_name" => fake()->firstName,
        "last_name" => fake()->lastName,
        "age" => 10,
        "address" => fake()->address,
        "username" => fake()->userName,
        "email" => fake()->email,
        "status_id" => Status::first()->id,
        "password" => fake()->password,
    ];

    login('', User::ROLE_READER)->post('/api/v1/admin/users', $user_data)
        ->assertStatus(403);

});

test("A user with role reader can't update a user ", function () {

    $currentUser = User::first();
    $user_data = [
        "first_name" => "test",
        "last_name" => fake()->lastName,
        "age" => 10,
        "address" => fake()->address,
        "username" => fake()->userName,
        "email" => fake()->email,
        "status_id" => Status::first()->id,
    ];

    login('', User::ROLE_READER)->patch('/api/v1/admin/users/'.$currentUser->id, $user_data)
        ->assertStatus(403);

});

test("A user with role reader can't delete a user ", function () {

    $user = User::first();

    login('', User::ROLE_READER)->delete('/api/v1/admin/users/'.$user->id)
        ->assertStatus(403);

});

