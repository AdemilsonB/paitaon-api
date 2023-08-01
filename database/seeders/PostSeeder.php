<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->all(); // Obtenha todos os IDs de usuário existentes

        for ($i = 0; $i < 10; $i++) {
            Post::create([
                'user_id' => $faker->randomElement($userIds), // Obtenha um ID de usuário aleatório
                'title' => $faker->text(10),
                'body' => $faker->text(15),
            ]);
        }
    }
}
