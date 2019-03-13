<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 5) as $index) {
            User::create([
                'name' => $faker->firstName(),
                'password' => bcrypt('secret'),
                'email' => $faker->email(),
                'google_id' => '',
                'remember_token' => str_random(10),
            ]);
        }


    }
}
