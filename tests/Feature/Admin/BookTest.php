<?php

namespace Tests\Feature\Admin;

use App\Models\AccessLevel;
use App\Models\Book;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Status;
use App\Models\Tag;
use App\Models\User;

use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

uses()->group('admin_book');

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

test('An Admin can get all books', function () {

    login('',User::ROLE_ADMIN)->get('/api/v1/admin/books')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",

                ]
            ]
        ])->assertJsonCount(Book::count(), 'data');
});

test('An Admin can get a book ', function () {
    $book_id = Book::first()->id;
    $book = login('',User::ROLE_ADMIN)->get('/api/v1/admin/books/'.$book_id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                "id",
                "status",
                "plans",
                "access_levels",
                "authors",
                "tags",
                "categories"
            ]
        ])->json('data');

    expect($book['id'])->toBe($book_id);

});

test('An Admin can create a book', function () {
    $status_id = Status::where('name',Status::STATUS_AVAILABLE)->first()->id;
    $access_level_ids = AccessLevel::all()->pluck('id')->toArray();
    $plan_ids = Plan::all()->pluck('id')->toArray();
    $category_ids = Category::all()->pluck('id')->toArray();
    $tag_ids = Tag::all()->pluck('id')->toArray();
    $author_ids = User::role(User::ROLE_AUTHOR)->get()->pluck('id')->toArray();

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
        'author_ids' => array_slice($author_ids,0,2),
    ];

    login('',User::ROLE_ADMIN)->post('/api/v1/admin/books', $book)
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

test('An Admin can update a book', function () {
    $book_id = Book::first()->id;
    $status_id = Status::where('name',Status::STATUS_AVAILABLE)->first()->id;
    $access_level_ids = AccessLevel::all()->pluck('id')->toArray();
    $plan_ids = Plan::all()->pluck('id')->toArray();
    $category_ids = Category::all()->pluck('id')->toArray();
    $tag_ids = Tag::all()->pluck('id')->toArray();
    $author_ids = User::role(User::ROLE_AUTHOR)->get()->pluck('id')->toArray();

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
        'author_ids' => array_slice($author_ids,0,2),
    ];

    login('',User::ROLE_ADMIN)->put('/api/v1/admin/books/'.$book_id, $book)
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

test('An Admin can delete a book', function () {
    $book_id = Book::first()->id;

    login('',User::ROLE_ADMIN)->delete('/api/v1/admin/books/'.$book_id)
        ->assertStatus(200);

    expect(Book::find($book_id))->toBeNull();

});

