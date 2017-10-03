<?php

namespace Tests\Feature;

use App\DriversLicense;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

//use Storage;

class LicenseTest extends TestCase
{
    use DatabaseMigrations;

    private $licenseData;
    private $databaseData;

    public function setUp()
    {
        parent::setup();

        // Fake the filesystem to avoid polluting our real storage
        Storage::fake('public');
        Storage::fake('local');

        // Must be logged in to create a license
        $fakeUser = factory(User::class)->create();
        $this->be($fakeUser);

        // Request params representation
        $this->licenseData = [
            'photo' => UploadedFile::fake()->image('test.png'),
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

        // Database representation
        $this->databaseData = [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_in' => 6 * 12 + 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '12 Elm Street',
            'sim' => 'Lost Stars',
        ];
    }

    public function testValidLicenseShouldPersist()
    {
        // Submit a new license request
        $this->post('dmv/license', $this->licenseData);

        // Record should appear in database
        $this->assertDatabaseHas('drivers_licenses', $this->databaseData);
    }

    public function testValidLicenseShouldFlashSuccess() {
        // Submit a new license request
        $response = $this->post('dmv/license', $this->licenseData);

        // User should be redirected with a success message
        $response->assertRedirect('dmv');
        $response->assertSessionHas('alert-success');
    }

    public function testValidLicenseShouldSavePhoto() {
        // Submit a new license request
        $this->post('dmv/license', $this->licenseData);

        // User photo should be saved
        $photo = DriversLicense::find(1)->first()->photo;
        Storage::disk('local')->assertExists($photo);
    }

    public function testValidLicenseShouldGenerateImage() {
        // Submit a new license request
        $this->post('dmv/license', $this->licenseData);

        // Image should be generated
        $image = DriversLicense::find(1)->first()->image;
        Storage::disk('public')->assertExists($image);
    }

    public function testShouldOnlyAllowOneLicensePerUser()
    {
        // Submit the same license request twice
        $this->post('dmv/license', $this->licenseData);
        $this->post('dmv/license', $this->licenseData);

        // Should only be one record
        $numberRecords = $this->getConnection(null)
            ->table('drivers_licenses')
            ->where($this->databaseData)
            ->count();

        $this->assertEquals(1, $numberRecords);
    }

    public function testPatchShouldUpdate()
    {
        $updatedLicenseData = array_replace($this->licenseData, ['address' => '441 Rose Street']);
        $updatedDatabaseData = array_replace($this->databaseData, ['address' => '441 Rose Street']);

        // Create a license, then update it
        $this->post('dmv/license', $this->licenseData);
        $this->patch('dmv/license', $updatedLicenseData);

        $this->assertDatabaseHas('drivers_licenses', $updatedDatabaseData);
        $this->assertDatabaseMissing('drivers_licenses', $this->databaseData);
    }

    public function testPostTwiceShouldSilentlyPatch()
    {
        $updatedLicenseData = array_replace($this->licenseData, ['address' => '441 Rose Street']);
        $updatedDatabaseData = array_replace($this->databaseData, ['address' => '441 Rose Street']);

        // Create a license, then create a different license
        $this->post('dmv/license', $this->licenseData);
        $this->post('dmv/license', $updatedLicenseData);

        $this->assertDatabaseHas('drivers_licenses', $updatedDatabaseData);
        $this->assertDatabaseMissing('drivers_licenses', $this->databaseData);
    }
}
