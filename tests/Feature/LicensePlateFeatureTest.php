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

    // TODO: show all licenses on GET index (if authenticated)
    // TODO: show edit form on GET {plate} (if owner)

    public function testUserCanSeeCreateForm()
    {
        $user = factory(User::class)->create();

        // View the page as a user
        $this->actingAs($user)
            ->get('dmv/plate/create')
            ->assertStatus(200)
            ->assertSeeText('Register Vehicle');
    }

    public function testGuestCannotSeeCreateForm()
    {
        // View the page while unauthenticated
        $this->get('dmv/plate/create')
            ->assertRedirect('login');
    }

    public function testUserCanAddLicensePlate()
    {
        $user = factory(User::class)->create();
        $validData = $this->newValidData();

        // Create a license plate
        $this->actingAs($user)
            ->post('dmv/plate', $validData)
            ->assertRedirect('dmv/plate/1')
            ->assertSessionHas('success');

        // Record should appear in the database
        array_merge($validData, ['user_id' => $user->id]);
        $this->assertDatabaseHas('license_plates', $validData);
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
            'year' => '2100', // too new
        ];

        // Attempt to create a license plate as that user
        $this->actingAs($user)
            ->post('dmv/plate', $invalidData)
            ->assertRedirect()
            ->assertSessionHasErrors(array_keys($invalidData));

        // Record should not appear in database
        $this->assertDatabaseMissing('drivers_licenses', $invalidData);
    }

    public function testGuestCannotAddLicensePlate()
    {
        $validData = $this->newValidData();

        // Attempt to create license plate while unauthenticated
        $this->post('dmv/plate', $validData)
            ->assertRedirect('login');

        // Record should not appear in database
        $this->assertDatabaseMissing('drivers_licenses', $validData);
    }

    public function testOwnerCanViewAllPlates()
    {
        $user = factory(User::class)->create();
        $plates = factory(LicensePlate::class, 3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('dmv/plate')
            ->assertStatus(200);

        foreach ($plates as $plate) {
            $response->assertSeeText($plate->tag)
                ->assertSeeText($plate->make)
                ->assertSeeText($plate->model);
        }
    }

    public function testOwnerCanViewExistingPlate()
    {
        $user = factory(User::class)->create();
        $plate = factory(LicensePlate::class)->create(['user_id' => $user->id]);

        // View the new plate
        $this->actingAs($user)
            ->get('dmv/plate/1')
            ->assertStatus(200)
            ->assertSeeText($plate->tag)
            ->assertSeeText($plate->make)
            ->assertSeeText($plate->model);
    }

    public function testStrangerCannotViewUnownedPlate()
    {
        $user = factory(User::class)->create();
        $stranger = factory(User::class)->create();
        $plate = factory(LicensePlate::class)->create(['user_id' => $user->id]);

        // Attempt to view the new plate as another user
        $this->actingAs($stranger)
            ->get("dmv/plate/{$plate->id}")
            ->assertStatus(403);
    }

    public function testOwnerCanUpdateExistingPlate()
    {
        $user = factory(User::class)->create();

        $validData = $this->newValidData();
        $patchData = $this->newValidData();

        // Create a license plate
        $this->actingAs($user)->post('dmv/plate', $validData);

        // Update the license plate
        $this->patch('dmv/plate/1', $patchData)
            ->assertStatus(200)
            ->assertSeeText('Updated');

        // Only the newest record should appear in the database
        $this->assertDatabaseMissing('license_plates', $validData);
        $this->assertDatabaseHas('license_plates', $patchData);
    }

    public function testStrangerCannotUpdateUnownedPlate()
    {
        $user = factory(User::class)->create();
        $stranger = factory(User::class)->create();

        $validData = $this->newValidData();
        $patchData = $this->newValidData();

        // Create a license plate
        $this->actingAs($user)->post('dmv/plate', $validData);

        // Try to update it as another user
        $this->actingAs($stranger)
            ->patch('dmv/plate/1', $patchData)
            ->assertStatus(403)
            ->assertDontSeeText('Updated');

        // The record should remain unchanged
        $this->assertDatabaseHas('license_plates', $validData);
        $this->assertDatabaseMissing('license_plates', $patchData);
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
