<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        // Development user
        User::insert([
            'id' => 1,
            'uuid' => '6553d6c6-129c-47e4-9c97-ce324dde17d6',
            'username' => 'Username',
            'name' => 'Firstname Resident',
            'password' => bcrypt('password'),
        ]);

        // Live demo user
        User::insert([
            'id' => '2',
            'uuid' => '1153d689-429c-47e4-2c33-de324dff17d8',
            'username' => 'user.name',
            'name' => 'Devin Sandman',
            'password' => bcrypt('password'),
        ]);

        // Real SL user
        User::insert([
            'id' => '3',
            'uuid' => '2e045f99-4807-4672-966f-b5af446f27be',
            'username' => 'devindevin1111 Resident',
            'name' => 'Dҽʋιɳ Kɳιɠԋƚ Dɾαƙσɳ',
            'password' => '$2y$10$hlDWty41umpA5TJ0CMc2uObW/9cXUsuTPlnoxiZPw9oLpZ5ABRQGe',
        ]);
    }
}
