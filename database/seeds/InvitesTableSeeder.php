<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Invite;
use App\Project;
use App\User;

class InvitesTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();
        $projects = Project::get();
        $users = User::pluck('email');

        foreach($projects as $project) {
            Invite::create([
                'email' => $faker->randomElement($users),
                'token' => $faker->swiftBicNumber,
                'project_id' => $project->id,
                'project_name' => $project->name,
            ]);
        }
    }
}

