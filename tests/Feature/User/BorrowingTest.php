<?php

use App\Models\Book;
use App\Models\Status;
use App\Models\User;
use App\Models\Lending;

uses()->group('user_borrowing');

beforeEach(function (){
    $this->artisan('db:seed --class=TagSeeder');
    $this->artisan('db:seed --class=CategorySeeder');
    $this->artisan('db:seed --class=RoleSeeder');
    $this->artisan('db:seed --class=StatusSeeder');
    $this->artisan('db:seed --class=AdminSeeder');
    $this->artisan('db:seed --class=AccessLevelSeeder');
    $this->artisan('db:seed --class=PlanSeeder');
    $this->artisan('db:seed --class=AuthorSeeder');
    $this->artisan('db:seed --class=BookSeeder');
});

test('A user can borrow a book', function () {

    $user = User::factory()->state([
        'age' => 20,
    ])->create();

    $status_id = Status::where('name',Status::STATUS_AVAILABLE)->first()->id;
    $book = Book::where('status_id',$status_id)->first();

    \App\Models\Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $book->plans->first()->id,
    ]);

    login($user)->post('/api/v1/user/books/'.$book->id.'/borrow')
        ->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);

    $lending = Lending::query()
                ->where('book_id',$book->id)
                ->where('user_id',$user->id)->first();

    expect($book->refresh()->status->name)->toBe(Status::STATUS_BORROWED);
    expect($lending)
        ->toBeInstanceOf(Lending::class)
        ->and($lending->user_id)->toBe($user->id)
        ->and($lending->book_id)->toBe($book->id)
        ->and($lending->borrowed_at->format('Y-m-d H:i:s'))
            ->toBe(now()->format('Y-m-d H:i:s'))
        ->and($lending->due_at->format('Y-m-d H:i:s'))
            ->toBe(now()->addDays(config('app.borrow_days'))
                    ->format('Y-m-d H:i:s'))
        ->and($lending->returned_at)->toBeNull();
});

test('A user can return a book before before due date time ', function () {

    $user = User::factory()->state([
        'age' => 20,
    ])->create();


    $book = Book::first();
    $book->update([
        'status_id' => Status::where('name',Status::STATUS_BORROWED)->first()->id
    ]);


    $lending = Lending::create([
        'book_id' => $book->id,
        'user_id' => $user->id,
        'borrowed_at' => now(),
        'due_at' => now()->addDays(config('app.borrow_days')),
    ]);

    login($user)->post('/api/v1/user/lending/'.$lending->id.'/return')
        ->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);

    expect($book->refresh()->status->name)->toBe(Status::STATUS_AVAILABLE);
    expect($lending->refresh()->returned_at->format('Y-m-d H:i:s'))
        ->toBe(now()->format('Y-m-d H:i:s'));
    expect($user->lending_points())->toBe(2);

});


test('A user can return a book before after due date time ', function () {

    $user = User::factory()->state([
        'age' => 20,
    ])->create();


    $book = Book::first();
    $book->update([
        'status_id' => Status::where('name',Status::STATUS_BORROWED)->first()->id
    ]);

    Lending::factory(1)->state([
        'book_id' => Book::where('id','!=',$book->id)->first()->id,
        'user_id' => $user->id,
        'borrowed_at' => now()->subDays(config('app.borrow_days')),
        'due_at' => now()->subDays(config('app.borrow_days')-1),
        'returned_at' => now()->subDays(config('app.borrow_days')-1),
        'points' => 2,
    ])->create();


    $lending = Lending::create([
        'book_id' => $book->id,
        'user_id' => $user->id,
        'borrowed_at' => now()->subDays(config('app.borrow_days')),
        'due_at' => now()->subDays(config('app.borrow_days')),
    ]);

    login($user)->post('/api/v1/user/lending/'.$lending->id.'/return')
        ->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);

    expect($book->refresh()->status->name)->toBe(Status::STATUS_AVAILABLE);
    expect($lending->refresh()->returned_at->format('Y-m-d H:i:s'))
        ->toBe(now()->format('Y-m-d H:i:s'));
    expect($user->lending_points())->toBe(1);

});
