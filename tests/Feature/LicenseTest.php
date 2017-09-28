<?php

namespace Tests\Feature;

use App\DriversLicense;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Storage;
use Tests\TestCase;

class LicenseTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setup();

        // Fake the default filesystem to avoid file name conflicts
        Storage::fake('public');

        // Making a license requires the user to be logged in
        $fakeUser = User::create([
            'uuid' => 'e5668cc3-c9bd-4b1f-9c40-5a66009aadde',
            'username' => 'testuser',
            'name' => 'name',
            'password' => bcrypt('password'),
        ]);

        $this->be($fakeUser);
    }

    public function testValidLicenseShouldPersist()
    {
        $response = $this->post('dmv/license', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ]);

        $response->assertRedirect('dmv');
        $response->assertSessionHas('alert-success');
        $this->assertDatabaseHas('drivers_licenses', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_in' => 6 * 12 + 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ]);
    }

    public function testValidLicenseShouldCreateImage() {
        $this->post('dmv/license', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ]);

        $image = DriversLicense::find(1)->first()->image;
        Storage::disk('public')->assertExists($image);

        // TODO: test update should generate a new image and delete the old one
    }

    public function testShouldOnlyAllowOneLicensePerUser()
    {
        $licenseData = [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ];

        // Submit twice
        $this->post('dmv/license', $licenseData);
        $this->post('dmv/license', $licenseData);

        $numberRecords = $this->getConnection(null)
            ->table('drivers_licenses')
            ->where([
                'dob' => '1990-03-31',
                'gender' => 'MALE',
                'height_in' => 6 * 12 + 1,
                'weight_lb' => 160,
                'eye_color_id' => 3,
                'hair_color_id' => 4,
                'address' => '12 Elm Street',
                'sim' => 'Lost Stars',
            ])->count();

        $this->assertEquals(1, $numberRecords);
    }

    public function testPatchShouldUpdate()
    {
        $licenseData = [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ];

        $this->post('dmv/license', $licenseData);
        $response = $this->patch('dmv/license', array_replace($licenseData, ['address' => '441 Rose Street']));

        $response->assertRedirect('dmv');
        $response->assertSessionHas('alert-success');
        $this->assertDatabaseHas('drivers_licenses', ['address' => '441 Rose Street']);
        $this->assertDatabaseMissing('drivers_licenses', ['address' => '12 Elm Street']);
    }

    public function testPostTwiceShouldSilentlyPatch()
    {
        $licenseData = [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ];

        $this->post('dmv/license', $licenseData);
        $response = $this->post('dmv/license', array_replace($licenseData, ['address' => '441 Rose Street']));

        $response->assertRedirect('dmv');
        $response->assertSessionHas('alert-success');
        $this->assertDatabaseHas('drivers_licenses', ['address' => '441 Rose Street']);
        $this->assertDatabaseMissing('drivers_licenses', ['address' => '12 Elm Street']);
    }
}
