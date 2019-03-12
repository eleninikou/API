<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(ProjectUserRolesTableSeeder::class);
        $this->call(MilestonesTableSeeder::class);
        $this->call(TicketTypesTableSeeder::class);
        $this->call(TicketStatusTableSeeder::class);
        $this->call(TicketsTableSeeder::class);
        $this->call(TicketCommentsTableSeeder::class);

    }
}
