<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->text(10),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make($faker->password),
                'bio' => $faker->text(50),
                'image_perfil' => $faker->text(10)
            ]);
        }
    }
}

