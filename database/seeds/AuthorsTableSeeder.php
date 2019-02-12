<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsTableSeeder extends Seeder
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
        $author_ids = range(1, 40);
        foreach ($author_ids as $author_id) {
            $organization_type = rand(0, 1);
            if ($organization_type==0) {
                $organization_name = $faker->university;
                $organization_email = $faker->email;
            } else {
                $organization_name = $faker->company;
                $organization_email = $faker->companyEmail;
            }
            DB::table('authors')->insert([
                'user_id' => $author_id,
                'organization_name' => $organization_name,
                'organization_type' => $organization_type,
                'organization_country' => $faker->countryCode,
                'organization_address' => $faker->address,
                'organization_email' => rand(0, 1) ? $organization_email : null,
                'organization_telephone' => rand(0, 1) ? $faker->phoneNumber : null,
            ]);
        }
    }
}
