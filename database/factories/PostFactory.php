<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word,
            'content' => fake()->sentence,
            'image' => 'photo1.png',
            'date' => '08/09/17',
            'views' => fake()->numberBetween(0, 9999),
            'category_id' => 1,
            'user_id' => 1,
            'status' => 1,
            'is_featured' => 0,
        ];
    }
}
