<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
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
        for ($i = 1; $i <= 300; $i++) {
            $reviewer_ids = $faker->randomElements(range(41, 50), 3, false);
            foreach ($reviewer_ids as $reviewer_id) {
                $score = rand(1, 5);
                DB::table('reviews')->insert([
                    'reviewer_id' => $reviewer_id,
                    'paper_id' => $i,
                    'score' => $score,
                    'comment' => rand(0, 1) ? $faker->text(50) : null,
                    'passed' => $score < 3 ? false : rand(0, 1),
                ]);
            }
        }
    }
}
