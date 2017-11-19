<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Note: This test MUST be run from `php artisan dusk` to work properly. Running from the IDE will
 * cause Dusk to use the wrong database, because PHPStorm doesn't pick up the .env.dusk file.
 */
class LicensePageTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp()
    {
        parent::setUp();

        // Avoid polluting the storage with test drivers licenses
        Storage::fake('public');

        $this->user = User::create([
            'uuid' => 'e5668cc3-c9bd-4b1f-9c40-5a66009aadde',
            'username' => 'testuser',
            'name' => 'name',
            'password' => bcrypt('password'),
        ]);
    }

    public function testSuccessfulCreation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/dmv/license')
                ->attach('photo', resource_path('imgproc/devin.png'))
                ->radio('gender', 'MALE')
                ->keys('#dob', '03311990')
                ->type('height_ft', '6')
                ->type('height_in', '1')
                ->type('weight_lb', 160)
                ->select('eye_color_id', '3')
                ->select('hair_color_id', '4')
                ->type('address', '3 Elm Street')
                ->type('sim', 'Lost Stars')
                ->press('Create')
                ->assertSeeIn('.alert-success', 'License successfully created!')
                ->assertPathIs('/dmv');
        });

        $this->assertDatabaseHas('drivers_licenses', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_in' => 6 * 12 + 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '3 Elm Street',
            'sim' => 'Lost Stars',
        ]);
    }

    // TODO testPrefillExistingLicense()
    // TODO assert right messages are displayed, e.g. "Edit License" when editing
}
