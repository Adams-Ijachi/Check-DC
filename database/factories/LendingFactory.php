<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lending>
 */
class LendingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $books_ids = \App\Models\Book::limit(10)->get()->pluck('id')->toArray();
        return [
            'book_id' => $this->faker->unique()->randomElement($books_ids),
            "borrowed_at" => $this->faker->dateTimeBetween('-1 year', 'now'),
            "due_at" => $this->faker->dateTimeBetween('now', '+1 year'),
            "returned_at" => $this->faker->dateTimeBetween('-1 year', 'now'),
            "points" => $this->faker->numberBetween(0, 100)
        ];
    }
}
