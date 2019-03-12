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
                'first_name' => $faker->firstName(),
                'last_name' => $faker->firstName(),
                'password' => bcrypt('secret'),
                'email' => $faker->email(),
                'remember_token' => str_random(10),
            ]);
        }


    }
}
