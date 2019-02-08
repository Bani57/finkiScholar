<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PapersTableSeeder extends Seeder
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
        for ($i = 0; $i < 300; $i++) {
            $title = $faker->sentence(10);
            DB::table('papers')->insert([ //,
                'title' => $title,
                'file' => './papers/' . $title . '.pdf',
                'abstract' => $faker->text(150),
                'status' => 0,
                'date_submitted' => null,
                'topic_id' => rand(1, 35),
            ]);
        }
    }
}
