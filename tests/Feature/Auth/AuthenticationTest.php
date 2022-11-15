<?php

namespace Tests\Feature\Auth;

use App\Models\User;

uses()->group('auth');

beforeEach(function (){

    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=RoleSeeder');

});


test('A user can register successfully', function () {

    $response = $this->post('/api/v1/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 10,
            'address' => '6268 Purdy Forge Suite 350\nQuitzonhaven, CT 58590-5695',
            'username' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'password'],
        [
            'Accept' => 'application/json',
    ]);
     $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
            ],
            'token'
     ]);

     $user = User::where('email', 'test@gmail.com')->first();

     expect($user)->not()->toBeNull()
         ->and($user->roles()->first()->name)->toBe(User::ROLE_READER);

});

test("A user can login successfully", function () {

    $user = User::factory()->create();

    $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'],
        [
            'Accept' => 'application/json',
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'data' => [
            'id',
        ],
        'token'
    ]);

});

test("A user can logout successfully", function () {

    $user = User::factory()->create();

    login($user)->post('/api/v1/logout', [], [
        'Accept' => 'application/json',
    ])->assertStatus(200);

});
