<?php


namespace Database\Factories;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowFactory extends Factory
{
    protected $model = Follow::class;

    public function definition()
    {
        return [
            'follower_id' => User::inRandomOrder()->first()->id,  // Random follower user
            'following_id' => User::inRandomOrder()->first()->id,  // Random following user
        ];
    }
}