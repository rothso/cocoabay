<?php

use App\DriversLicense;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

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
        Storage::disk('public')->deleteDirectory('license/photos');
        Storage::disk('public')->deleteDirectory('license/drivers');

        $fakeFile1 = UploadedFile::fake()->image('test.png', 20, 20);
        $fakeFile2 = UploadedFile::fake()->image('test.png', 30, 30);

        $fakePhoto1 = Storage::disk('public')->putFile('license/photos', $fakeFile1);
        $fakePhoto2 = Storage::disk('public')->putFile('license/photos', $fakeFile2);

        DriversLicense::create([
            'user_id' => 1,
            'dob' => '1994-01-17',
            'gender' => 'MALE',
            'height_in' => 68,
            'weight_lb' => 108,
            'eye_color_id' => 1,
            'hair_color_id' => 1,
            'address' => '104 Rocky Rd',
            'sim' => 'Lost Stars',
            'photo' => $fakePhoto1,
        ]);

        DriversLicense::create([
            'user_id' => 3,
            'dob' => '1994-01-17',
            'gender' => 'MALE',
            'height_in' => 66,
            'weight_lb' => 108,
            'eye_color_id' => 2,
            'hair_color_id' => 2,
            'address' => '24 Test Street',
            'sim' => 'Bratsville',
            'photo' => $fakePhoto2,
        ]);
    }
}
