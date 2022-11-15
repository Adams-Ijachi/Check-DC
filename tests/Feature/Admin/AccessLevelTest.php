<?php

namespace Tests\Feature\Admin;

use App\Models\AccessLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
uses()->group('admin_access_level');

beforeEach(function (){

    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=AccessLevelSeeder');

});

test('An Admin can get all access levels', function () {

    login('',User::ROLE_ADMIN)->get('/api/v1/admin/access-levels')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                    "name",
                    "min_age",
                    "max_age",
                    "borrowing_points",
                ]
            ]
        ])->assertJsonCount(8, 'data');
});

test('An Admin can get an access_level ', function () {
    $access_level_id = AccessLevel::first()->id;
    $access_level = login('',User::ROLE_ADMIN)->get('/api/v1/admin/access-levels/'.$access_level_id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "min_age",
                "max_age",
                "borrowing_points",
            ]
        ])->json('data');

    expect($access_level['id'])->toBe($access_level_id);

});

test('An Admin can create an access_level ', function () {

    $access_level = [
        'name' => 'test',
        'min_age' => 10,
        'max_age' => 20,
        'borrowing_points' => 10,
    ];

    $response = login('',User::ROLE_ADMIN)->post('/api/v1/admin/access-levels', $access_level)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "min_age",
                "max_age",
                "borrowing_points",
            ]
        ]);

    $access_level = $response->json('data');

    expect(AccessLevel::find($access_level['id']))->not()->toBeNull();

});

test('An Admin can update an access_level ', function () {

    $access_level = AccessLevel::first();

    $access_level->name = 'test';

    $response = login('',User::ROLE_ADMIN)->patch('/api/v1/admin/access-levels/'.$access_level->id, $access_level->toArray())
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "min_age",
                "max_age",
                "borrowing_points",
            ]
        ]);

    $access_level = $response->json('data');

    expect(AccessLevel::find($access_level['id'])->name)->toBe('test');

});

test('An Admin can delete an access_level ', function () {

    $access_level = AccessLevel::first();

    login('',User::ROLE_ADMIN)->delete('/api/v1/admin/access-levels/'.$access_level->id)
        ->assertStatus(204);

    expect(AccessLevel::find($access_level->id))->toBeNull();

});
