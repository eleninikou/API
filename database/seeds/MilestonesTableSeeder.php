<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Milestone;
use App\Project;

class MilestonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $projects = Project::pluck('id')->all();

        foreach($projects as $project) {
            Milestone::create([
                'title' => $faker->word(),
                'focus' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'project_id' => $project,
                'due_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
            ]);
        }
    }
}
