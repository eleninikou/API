<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Invite;
use App\Project;
use App\User;
use App\role;

class InvitesTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();
        $projects = Project::get();
        $users = User::pluck('email');
        $roles = Role::pluck('id');

        foreach($projects as $project) {
            Invite::create([
                'email' => $faker->randomElement($users),
                'token' => $faker->swiftBicNumber,
                'project_id' => $project->id,
                'project_name' => $project->name,
                'project_role' => $faker->randomElement($roles),
            ]);
        }
    }
}

