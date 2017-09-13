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

    public function testHappyPost()
    {
        $response = $this->post('dmv/license', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_ft' => 6,
            'height_in' => 1,
            'weight' => 160,
            'eye_color' => 3,
            'hair_color' => 4,
            'address' => '12 Elm Street'
        ]);

        $response->assertStatus(200);
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
}
