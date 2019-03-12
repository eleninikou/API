<?php

use Illuminate\Database\Seeder;
use App\TicketType;

class TicketTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketType::create([
            'type' => 'Bug',
        ]);
        TicketType::create([
            'type' => 'Future request',
        ]);
        TicketType::create([
            'type' => 'Idea',
        ]);
        TicketType::create([
            'type' => 'Research',
        ]);
    }
}
