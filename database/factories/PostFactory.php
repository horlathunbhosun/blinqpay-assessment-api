<?php

namespace Database\Factories;

use App\Http\Enums\GenericStatusEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'title' => $this->faker->sentence,
            'excerpt' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'slug' => $this->faker->slug,
            'status' => GenericStatusEnum::ACTIVE,
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
        ];
    }
}
