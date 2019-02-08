<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviewer_ids = range(41, 50);
        foreach ($reviewer_ids as $reviewer_id) {
            DB::table('reviewers')->insert([
                'user_id' => $reviewer_id,
            ]);
        }
    }
}
