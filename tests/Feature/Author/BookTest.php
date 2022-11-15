<?php

namespace Tests\Feature\Author;

use App\Models\AccessLevel;
use App\Models\Book;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Status;
use App\Models\Tag;
use App\Models\User;


uses()->group('author');

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


test("An author can only get own books", function () {


    $author = User::role(User::ROLE_AUTHOR)->first();


    login($author,User::ROLE_AUTHOR)->get('/api/v1/author/books')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                ]
            ]
        ])->assertJsonCount($author->books->count(), 'data');
});

test("An author can only get own book", function () {

    $author = User::role(User::ROLE_AUTHOR)->first();
    $book_id = $author->books->first()->id;
    login($author,User::ROLE_AUTHOR)->get('/api/v1/author/books/'.$book_id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
            ]
        ]);
});

test('An Author can create a book', function () {
    $status_id = Status::where('name',Status::STATUS_AVAILABLE)->first()->id;
    $access_level_ids = AccessLevel::all()->pluck('id')->toArray();
    $plan_ids = Plan::all()->pluck('id')->toArray();
    $category_ids = Category::all()->pluck('id')->toArray();
    $tag_ids = Tag::all()->pluck('id')->toArray();


    $book = [
        "title" => "Title",
        "edition" => fake()->sentence(3),
        "description" => fake()->paragraph(3),
        "prologue" => fake()->paragraph(3),
        'status_id' => $status_id,
        'access_level_ids' => array_slice($access_level_ids,0,3),
        'plan_ids' => array_slice($plan_ids,0,2),
        'category_ids' => array_slice($category_ids,0,2),
        'tag_ids' => array_slice($tag_ids,0,2),
    ];

    login('',User::ROLE_AUTHOR)->post('/api/v1/author/books', $book)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                "id",
                "status",
                "plans",
                "access_levels",
                "tags",
                "categories"
            ]
        ]);

    expect(Book::where('title',$book['title'])->first())->not()->toBeNull();

});

test('An Author can update a book', function () {

    $author = User::role(User::ROLE_AUTHOR)->first();
    $book_id = $author->books->first()->id;
    $status_id = Status::where('name',Status::STATUS_AVAILABLE)->first()->id;
    $access_level_ids = AccessLevel::all()->pluck('id')->toArray();
    $plan_ids = Plan::all()->pluck('id')->toArray();
    $category_ids = Category::all()->pluck('id')->toArray();
    $tag_ids = Tag::all()->pluck('id')->toArray();


    $book = [
        "title" => "Title",
        "edition" => fake()->sentence(3),
        "description" => fake()->paragraph(3),
        "prologue" => fake()->paragraph(3),
        'status_id' => $status_id,
        'access_level_ids' => array_slice($access_level_ids,0,3),
        'plan_ids' => array_slice($plan_ids,0,2),
        'category_ids' => array_slice($category_ids,0,2),
        'tag_ids' => array_slice($tag_ids,0,2),

    ];

    login($author,User::ROLE_AUTHOR)->put('/api/v1/author/books/'.$book_id, $book)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "status",
                "plans",
                "access_levels",
                "tags",
                "categories"
            ]
        ]);

    expect(Book::find($book_id)->title)->toBe($book['title']);

});

test('An Author can delete a book', function () {

    $author = User::role(User::ROLE_AUTHOR)->first();
    $book_id = $author->books->first()->id;

    login($author,User::ROLE_AUTHOR)->delete('/api/v1/author/books/'.$book_id)
        ->assertStatus(200);

    expect(Book::find($book_id))->toBeNull();

});



