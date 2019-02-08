<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 5; $i++) {
            DB::table('topics')->insert([ //,
                'name' => $faker->words(3, true),
                'parent_topic_id' => null,
            ]);
        }
        $parent_topic_ids = $faker->randomElements(range(1, 5), 10, true);
        foreach ($parent_topic_ids as $parent_topic_id) {
            DB::table('topics')->insert([ //,
                'name' => $faker->words(3, true),
                'parent_topic_id' => $parent_topic_id,
            ]);
        }
        $parent_topic_ids_2 = $faker->randomElements(range(6, 15), 10, true);
        foreach ($parent_topic_ids_2 as $parent_topic_id) {
            DB::table('topics')->insert([ //,
                'name' => $faker->words(3, true),
                'parent_topic_id' => $parent_topic_id,
            ]);
        }
        $parent_topic_ids_3 = $faker->randomElements(range(16, 25), 10, true);
        foreach ($parent_topic_ids_3 as $parent_topic_id) {
            DB::table('topics')->insert([ //,
                'name' => $faker->words(3, true),
                'parent_topic_id' => $parent_topic_id,
            ]);
        }
    }
}
