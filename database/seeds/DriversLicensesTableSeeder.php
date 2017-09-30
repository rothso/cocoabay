<?php

use App\DriversLicense;
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

        DriversLicense::create([
            'user_id' => 1,
            'number' => '179193072',
            'dob' => '1994-01-17',
            'gender' => 'MALE',
            'height_in' => 68,
            'weight_lb' => 108,
            'eye_color_id' => 1,
            'hair_color_id' => 1,
            'address' => '104 Rocky Rd',
            'sim' => 'Lost Stars',
            'expires_at' => '2020-09-17',
        ]);

        DriversLicense::create([
            'user_id' => 3,
            'number' => '478398416',
            'dob' => '1994-01-17',
            'gender' => 'MALE',
            'height_in' => 66,
            'weight_lb' => 108,
            'eye_color_id' => 2,
            'hair_color_id' => 2,
            'address' => '24 Test Street',
            'sim' => 'Bratsville',
            'expires_at' => '2017-12-29',
        ]);
    }
}
