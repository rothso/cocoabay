<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LicenseTest extends TestCase
{
    use DatabaseMigrations;

    /*
     * List of tests
     * - Should not create more than one license
     * - Duplicate submission for user should update
     * - Something about the Eloquent relationships
     */

    public function setUp()
    {
        parent::setup();

        // Making a license requires the user to be logged in
        $fakeUser = User::create([
            'uuid' => 'e5668cc3-c9bd-4b1f-9c40-5a66009aadde',
            'username' => 'testuser',
            'name' => 'name',
            'password' => bcrypt('password'),
        ]);

        $this->be($fakeUser);
    }

    public function testValidLicenseShouldSave()
    {
        $response = $this->post('dmv/license', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street'
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
            'address' => '12 Elm Street'
        ]);
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
            'address' => '12 Elm Street'
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
                'address' => '12 Elm Street'
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
            'address' => '12 Elm Street'
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
            'address' => '12 Elm Street'
        ];

        $this->post('dmv/license', $licenseData);
        $response = $this->post('dmv/license', array_replace($licenseData, ['address' => '441 Rose Street']));

        $response->assertRedirect('dmv');
        $response->assertSessionHas('alert-success');
        $this->assertDatabaseHas('drivers_licenses', ['address' => '441 Rose Street']);
        $this->assertDatabaseMissing('drivers_licenses', ['address' => '12 Elm Street']);
    }
}
