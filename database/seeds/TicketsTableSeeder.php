<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Project;
use App\Milestone;
use App\User;
use App\Ticket;
use App\TicketType;
use App\TicketStatus;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $projects = Project::with('milestones')->get();
        $users = User::pluck('id')->all();
        $ticket_type = TicketType::pluck('id')->all();
        $ticket_status = TicketStatus::pluck('id')->all();


        foreach($projects as $project) {
            foreach($project->milestones as $milestone) {
                foreach(range(1, 3) as $index) {
                        Ticket::create([
                            'title' => $faker->catchPhrase(),
                            'description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                            'type_id' => $faker->randomElement($ticket_type),
                            'status_id' =>  $faker->randomElement($ticket_status),
                            'project_id' => $project->id,
                            'due_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                            'creator_id' => $faker->randomElement($users),
                            'assigned_user_id' => $faker->randomElement($users),
                            'milestone_id' => $milestone->id
                        ]);
                        Ticket::create([
                            'title' => $faker->catchPhrase(),
                            'description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                            'type_id' => $faker->randomElement($ticket_type),
                            'status_id' =>  $faker->randomElement($ticket_status),
                            'project_id' => $project->id,
                            'due_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                            'creator_id' => $faker->randomElement($users),
                            'assigned_user_id' => $faker->randomElement($users),
                            'milestone_id' => $milestone->id
                        ]);
                        Ticket::create([
                            'title' => $faker->catchPhrase(),
                            'description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                            'type_id' => $faker->randomElement($ticket_type),
                            'status_id' =>  $faker->randomElement($ticket_status),
                            'project_id' => $project->id,
                            'due_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                            'creator_id' => $faker->randomElement($users),
                            'assigned_user_id' => $faker->randomElement($users),
                            'milestone_id' => $milestone->id
                        ]);
                    }
                }
        }
    }
}
