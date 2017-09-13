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
        DB::table('users')->insert([
            'uuid' => '6553d6c6-129c-47e4-9c97-ce324dde17d6',
            'username' => 'Username',
            'name' => 'Firstname Resident',
            'password' => bcrypt('password')
        ]);
    }
}
