<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExternalCitationsTableSeeder extends Seeder
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
        for ($i = 0; $i < 500; $i++) {
            $num_authors = rand(1, 5);
            $authors = "";
            for ($j = 0; $j < $num_authors - 1; $j++)
                $authors = $authors . $faker->name . ', ';
            $authors = $authors . $faker->name;
            DB::table('external_citations')->insert([//,
                'title' => $faker->sentence(10),
                'authors' => $authors,
                'link' => $faker->url,
                'date_published' => $faker->date(),
                'type' => $faker->randomElement(array('paper', 'book', 'website')),
                'part' => rand(0, 1) ? 'pages ' . rand(1, 5) . ' to ' . rand(6, 10) : null,
                'paper_id' => rand(1, 300),
            ]);
        }
    }
}
