<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\ProjectUserRole;
use App\User;
use App\Role;
use App\Project;


class ProjectUserRolesTableSeeder extends Seeder
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
        $projects = Project::pluck('id')->all();


        foreach($projects as $project) {
            ProjectUserRole::create([
                'user_id' => $faker->randomElement($users),
                'role_id' => 1,
                'project_id' => $project,
            ]);
            ProjectUserRole::create([
                'user_id' => $faker->randomElement($users),
                'role_id' => 2,
                'project_id' => $project,
            ]);
            ProjectUserRole::create([
                'user_id' => $faker->randomElement($users),
                'role_id' => 3,
                'project_id' => $project,
            ]);
        }
    }
}
