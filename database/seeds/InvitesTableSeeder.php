<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Invite;
use App\Project;

class InvitesTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();
        $projects = Project::get();

        foreach($projects as $project) {
            Invite::create([
                'email' => $faker->email(),
                'token' => $faker->swiftBicNumber,
                'project_id' => $project->id,
                'project_name' => $project->name,
            ]);
        }
    }
}

