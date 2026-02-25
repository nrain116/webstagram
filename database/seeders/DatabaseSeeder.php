<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use App\Models\Like;
use App\Events\UserFollowed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        // admin id 1
        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('root'),
            'remember_token' => Str::random(10),
        ]);
        // Create 10 users
        $users = User::factory(10)->create();

        // Create 50 posts, each post belongs to a random user
        $posts = Post::factory(10)->create();

        // Create 20 follow relationships (random user following other users)
        Follow::factory(10)->create()->each(function ($follow) {
            // Get the follower and following users
            $follower = $follow->follower;  // The user who follows
            $following = $follow->following;  // The user being followed

            // Dispatch the event
            event(new UserFollowed($follower, $following));
        });

        // Create 100 likes for random posts from random users
        Like::factory(10)->create();
    }
}