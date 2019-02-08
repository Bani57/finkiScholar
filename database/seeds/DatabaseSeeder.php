<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AuthorsTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(PapersTableSeeder::class);
        $this->call(AuthorshipsTableSeeder::class);
        $this->call(CitationsTableSeeder::class);
        $this->call(ExternalCitationsTableSeeder::class);
        $this->call(ReviewersTableSeeder::class);
        $this->call(ReviewsTableSeeder::class);
    }
}
