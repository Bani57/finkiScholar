<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);
        for ($i = 0; $i < 50; $i++) {
            $username = $faker->unique()->userName;
            DB::table('users')->insert([ //,
                'username' => $username,
                'email' => $faker->unique()->email,
                'password' => bcrypt(strtolower($username)),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'description' => rand(0, 1) ? $faker->sentence : null,
                'dob' => $faker->date(),
                'picture' => null,
            ]);
        }
    }
}
