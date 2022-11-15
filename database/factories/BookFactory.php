<?php

namespace Database\Factories;

use App\Models\AccessLevel;
use App\Models\Book;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Status;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'prologue' => $this->faker->paragraph,
            'edition' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status_id' => Status::where('name', Status::STATUS_AVAILABLE)->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Book $book) {
            // has many authors , plans , access levels
            $authors = User::role(User::ROLE_AUTHOR)->limit(3)->get();
            $book->authors()->attach($authors);

            $plans = Plan::limit(3)->get();
            $book->plans()->attach($plans);

            $accessLevels = AccessLevel::limit(3)->get();
            $book->accessLevels()->attach($accessLevels);

            // has many tags , categories
            $tags = Tag::limit(3)->get();
            $book->tags()->attach($tags);

            $categories = Category::limit(3)->get();
            $book->categories()->attach($categories);

        });
    }

}
