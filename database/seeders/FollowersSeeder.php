<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Followers;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class FollowersSeeder extends Seeder
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
        $followerIds = 13; // User::pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            Followers::create([
                'user_id' => $faker->randomElement($userIds),
                'follower_id' => $followerIds // $faker->randomElement($followerIds),
            ]);
        }
    }
}
