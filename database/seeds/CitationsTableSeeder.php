<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 300; $i++) {
            for ($j = 1; $j <= 300; $j++) {
                if ($i != $j and rand(1, 10) == 10) {
                    DB::table('citations')->insert([
                        'citing_paper_id' => $i,
                        'cited_paper_id' => $j,
                        'part' => rand(0, 1) ? 'pages ' . rand(1, 5) . ' to ' . rand(6, 10) : null,
                    ]);
                }
            }
        }
    }
}
