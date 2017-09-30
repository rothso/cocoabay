<?php

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
        DB::table('users')->insert([
            'id' => 1,
            'uuid' => '6553d6c6-129c-47e4-9c97-ce324dde17d6',
            'username' => 'Username',
            'name' => 'Firstname Resident',
            'password' => bcrypt('password')
        ]);
        DB::table('users')->insert([
            'id' => '2',
            'uuid' => '1153d689-429c-47e4-2c33-de324dff17d8',
            'username' => 'user.name',
            'name' => 'Devin Sandman',
            'password' => bcrypt('password')
        ]);
    }
}
