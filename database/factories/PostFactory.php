<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),  // Random user for each post
            'description' => $this->faker->paragraph(),  // Random post description
            'image_path' => $this->faker->imageUrl(),  // Random image URL for post
        ];
    }
}
