<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comments;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->all();
        $postIds = Post::pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            Comments::create([
                'user_id' => $faker->randomElement($userIds),
                'post_id' => $faker->randomElement($postIds),
                'message' => $faker->text
            ]);
        }
    }
}
