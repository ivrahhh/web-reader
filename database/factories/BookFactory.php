<?php

namespace Database\Factories;

use App\Enums\Book\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'book_title' => fake()->unique()->sentence(),
            'synopsis' => fake()->paragraphs(5, true),
            'status' => Status::random(),
            'user_id' => User::factory(),
        ];
    }
}
