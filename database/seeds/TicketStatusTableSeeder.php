<?php

use Illuminate\Database\Seeder;
use App\TicketStatus;

class TicketStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketStatus::create([
            'status' => 'To do',
        ]);
        TicketStatus::create([
            'status' => 'In progress',
        ]);
        TicketStatus::create([
            'status' => 'Review',
        ]);
        TicketStatus::create([
            'status' => 'Completed',
        ]);
        TicketStatus::create([
            'status' => 'On hold',
        ]);
        TicketStatus::create([
            'status' => 'To be discussed',
        ]);
        TicketStatus::create([
            'status' => 'Archived',
        ]);
    }
}
