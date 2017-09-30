<?php

use Illuminate\Database\Seeder;

class DriversLicensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drivers_licenses')->delete();
        DB::table('drivers_licenses')->insert([
            'user_id' => 1,
            'number' => '179193072',
            'dob' => '1994-01-17',
            'gender' => 'MALE',
            'height_in' => 5 * 12 + 8,
            'weight_lb' => 108,
            'eye_color_id' => 1,
            'hair_color_id' => 1,
            'address' => '104 Rocky Rd',
            'sim' => 'Lost Stars',
            'photo' => '',
            'image' => '',
            'expires_at' => '2020-09-17',
        ]);
    }
}
