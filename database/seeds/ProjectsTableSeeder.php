<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Project;
use App\User;


class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = User::pluck('id')->all();

        foreach(range(1, 5) as $index) {
            Project::create([
                'name' => $faker->company(),
                'description' => $faker->bs(),
                'creator_id' => $faker->randomElement($users),
                'client_id' =>$faker->randomElement($users)
            ]);
        }
    }
}
