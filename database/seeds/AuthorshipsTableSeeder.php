<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorshipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($j = 1; $j <= 300; $j++) {
            $lead_author = true;
            for ($i = 1; $i <= 40; $i++) {
                if (rand(1, 10) == 10) {
                    DB::table('authorships')->insert([
                        'author_id' => $i,
                        'paper_id' => $j,
                        'is_lead_author' => $lead_author,
                        'part' => rand(0, 1) ? 'pages ' . rand(1, 5) . ' to ' . rand(6, 10) : null,
                    ]);
                    $lead_author = false;
                }
            }
            if ($lead_author) {
                DB::table('authorships')->insert([
                    'author_id' => rand(1, 40),
                    'paper_id' => $j,
                    'is_lead_author' => true,
                    'part' => rand(0, 1) ? 'pages ' . rand(1, 5) . ' to ' . rand(6, 10) : null,
                ]);
            }
        }
    }
}
