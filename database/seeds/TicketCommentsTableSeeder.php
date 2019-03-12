<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\User;
use App\Ticket;
use App\TicketComment;

class TicketCommentsTableSeeder extends Seeder
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
        $tickets = Ticket::pluck('id')->all();

        foreach($tickets as $ticket) {
            TicketComment::create([
                'ticket_id' => $ticket,
                'user_id' => $faker->randomElement($users),
                'comment' => $faker->text,
            ]);
        }
    }
}
