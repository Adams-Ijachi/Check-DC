<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\Lending;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

uses()->group('admin_lending');

beforeEach(function (){
    $this->artisan('db:seed --class=TagSeeder');
    $this->artisan('db:seed --class=CategorySeeder');
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=AdminSeeder');
    $this->artisan('db:seed --class=AccessLevelSeeder');
    $this->artisan('db:seed --class=PlanSeeder');
    $this->artisan('db:seed --class=AuthorSeeder');
    $this->artisan('db:seed --class=ReaderSeeder');
    $this->artisan('db:seed --class=BookSeeder');
});

test('An Admin can get all lending', function () {

    $this->artisan('db:seed --class=LendingSeeder');

    login('',User::ROLE_ADMIN)->get('/api/v1/admin/lendings')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                    "book",
                    "user"
                ]
            ]
        ])->assertJsonCount(9, 'data');
});

test('An Admin can get a lending ', function () {

    $this->artisan('db:seed --class=LendingSeeder');

    $lending_id = Lending::first()->id;
    $lending = login('',User::ROLE_ADMIN)->get('/api/v1/admin/lendings/'.$lending_id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "book",
                "user"
            ]
        ])->json('data');

    expect($lending['id'])->toBe($lending_id);

});

test('An Admin can create a lending ', function () {

    $user_id = User::first()->id;
    $book_id = Book::first()->id;
    $status_id = Status::first()->id;
    $borrowed_at_date = now()->format('Y-m-d');
    $due_date = now()->addDays(7)->format('Y-m-d');

    $lending = [
        'book_id' => $book_id,
        'user_id' =>  $user_id,
        'status_id' => $status_id,
        'borrowed_at' => $borrowed_at_date,
        'due_at' => $due_date,
        'points' => 10,
    ];

    login('',User::ROLE_ADMIN)->post('/api/v1/admin/lendings', $lending)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                "id",
                "book",
                "user"
            ]
        ]);

    expect(Lending::where('book_id', $lending['book_id'])
        ->where('returned_at', null)->first())->not()->toBeNull();

});

test('An Admin can update a lending ', function () {

    $this->artisan('db:seed --class=LendingSeeder');

    $lending = Lending::first();
    $user_id = User::first()->id;
    $book_id = Book::first()->id;
    $status_id = Status::first()->id;
    $borrowed_at_date = now()->format('Y-m-d');
    $due_date = now()->addDays(7)->format('Y-m-d');
    $returned_at_date = now()->addDays(7)->format('Y-m-d');

    $lending_data = [
        'book_id' => $lending->book_id,
        'user_id' =>  $user_id,
        'status_id' => $status_id,
        'borrowed_at' => $borrowed_at_date,
        'due_at' => $due_date,
        'points' => 10,
        'returned_at' => $returned_at_date,
    ];


    login('',User::ROLE_ADMIN)->patch('/api/v1/admin/lendings/'.$lending->id, $lending_data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "book",
                "user"
            ]
        ]);

    expect(Lending::where('book_id', $lending->book_id)
        ->where('returned_at', null)->first())->toBeNull();

});

test('An Admin can delete a lending ', function () {

    $this->artisan('db:seed --class=LendingSeeder');

    $lending = Lending::first();

    login('',User::ROLE_ADMIN)->delete('/api/v1/admin/lendings/'.$lending->id)
        ->assertStatus(200);

    expect(Lending::find($lending->id))->toBeNull();

});

