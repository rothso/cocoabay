<?php

namespace Tests\Feature;

use App\LicensePlate;
use App\User;
use Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LicensePlateFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        // Avoid polluting the public directory with fake license plate images
        Storage::fake('public');
    }

    public function _testUserCanSeeForm()
    {
        //
    }

    public function _testGuestCannotSeeForm()
    {
        //
    }

    public function testUserCanAddLicensePlate()
    {
        $user = factory(User::class)->create();
        $validData = $this->newValidData();

        // Create a license plate
        $response = $this->actingAs($user)
            ->post('dmv/plate', $validData);

        // Record should appear in the database
        array_merge($validData, ['user_id' => $user->id]);
        $this->assertDatabaseHas('license_plates', $validData);

        // User should see a success message
        $response->assertStatus(200);
        $response->assertSeeText('Success');
    }

    public function testUserCanAddMultiplePlates()
    {
        $user = factory(User::class)->create();
        $validData = $this->newValidData();

        // Create multiple license plates under the same user
        $this->be($user);
        $this->post('dmv/plate', $validData);
        $this->post('dmv/plate', $validData);
        $this->post('dmv/plate', $validData);

        // Should be three records
        $numberRecords = $this->getConnection()
            ->table('license_plates')
            ->where(['user_id' => $user->id])
            ->count();

        $this->assertEquals(3, $numberRecords);

    }

    public function testUserCannotAddInvalidLicensePlate()
    {
        $user = factory(User::class)->create();

        // Each of these fields will fail validation
        $invalidData = [
            'style_id' => 1000, // out of bounds
            'make' => '', // missing
            'model' => '', // missing
            'class' => '', // missing
            'color' => '', // missing
            'year' => 'asdf', // bad format
        ];

        // Attempt to create a license plate as that user
        $response = $this->actingAs($user)
            ->post('dmv/plate', $invalidData);

        // Record should not appear in database
        $this->assertDatabaseMissing('drivers_licenses', $invalidData);

        // Validator should catch the errors
        $response->assertRedirect();
        $response->assertSessionHasErrors(array_keys($invalidData));
    }

    public function testGuestCannotAddLicensePlate()
    {
        $validData = $this->newValidData();

        // Attempt to create license plate while unauthenticated
        $response = $this->post('dmv/plate', $validData);

        // Record should not appear in database
        $this->assertDatabaseMissing('drivers_licenses', $validData);

        // Should redirect to login page
        $response->assertRedirect('login');
    }

    public function _testOwnerCanViewExistingPlate()
    {
        //
    }

    public function testOwnerCanUpdateExistingPlate()
    {
        // Create a new user
        $user = factory(User::class)->create();

        $validData = $this->newValidData();
        $patchData = array_replace($validData, ['color' => 'some color']);

        // Create a license plate and update it
        $this->actingAs($user)->post('dmv/plate', $validData);
        $response = $this->patch('dmv/plate/1', $patchData);

        // Only the newest record should appear in the database
        $this->assertDatabaseMissing('license_plates', $validData);
        $this->assertDatabaseHas('license_plates', $patchData);

        // User should see a success message
        $response->assertStatus(200);
        $response->assertSeeText('Updated');
    }

    public function _testStrangerCannotViewUnownedPlate()
    {
        //
    }

    public function _testStrangerCannotUpdateUnownedPlate()
    {
        //
    }

    /**
     * Generates a possible form submission.
     *
     * @return array
     */
    private function newValidData()
    {
        /** @var \App\LicensePlate $fakePlate */
        $fakePlate = factory(LicensePlate::class)->make();

        return [
            'style_id' => $fakePlate->style_id,
            'make' => $fakePlate->make,
            'model' => $fakePlate->model,
            'class' => $fakePlate->class,
            'color' => $fakePlate->color,
            'year' => $fakePlate->year,
        ];
    }
}
