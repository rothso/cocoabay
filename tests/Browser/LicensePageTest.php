<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\User;

class LicensePageTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::create([
            'uuid' => 'e5668cc3-c9bd-4b1f-9c40-5a66009aadde',
            'username' => 'testuser',
            'name' => 'name',
            'password' => bcrypt('password'),
        ]);
    }

    public function testHappyForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/dmv/license')
                ->radio('gender', 'MALE')
                ->keys('#dob', '03311990')
                ->type('height_ft', '6')
                ->type('height_in', '1')
                ->type('weight', 160)
                ->select('eye_color', '3')
                ->select('hair_color', '4')
                ->type('address', '3 Elm Street')
                ->press('Create')
                ->assertSee('Success');
        });

        $this->assertDatabaseHas('drivers_licenses', [
            'dob' => '1990-03-31',
            'gender' => 'MALE',
            'height_in' => 6 * 12 + 1,
            'weight_lb' => 160,
            'eye_color_id' => 3,
            'hair_color_id' => 4,
            'address' => '3 Elm Street'
        ]);
    }
}
