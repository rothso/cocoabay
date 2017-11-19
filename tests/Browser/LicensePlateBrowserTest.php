<?php

namespace Tests\Browser;

use App\LicensePlateStyle;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LicensePlateBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        // Avoid polluting the storage with test license plates and plate styles
        Storage::fake('public');
    }

    public function testFormListsAllPlateStyles()
    {
        $user = factory(User::class)->create();
        $styles = factory(LicensePlateStyle::class, 5)->create();

        $this->browse(function (Browser $browser) use ($user, $styles) {
            $browser->loginAs($user)
                ->visit('/dmv/plate/create');

            foreach ($styles as $style) {
                $browser->assertSee($style->name)
                    ->assertVisible("input[type='radio'][value='$style->id']")
                    ->assertSourceHas('img src="' . asset($style->image) . '"');
            }
        });
    }

    public function testUserCanCreatePlate()
    {
        $user = factory(User::class)->create();
        $style = factory(LicensePlateStyle::class)->create();
        
        $this->browse(function (Browser $browser) use ($user, $style) {
            $browser->loginAs($user)
                ->visit('/dmv/plate/create')
                ->radio('style_id', $style->id)
                ->type('make', 'Toyota')
                ->type('model', 'Camry')
                ->type('class', 'Sedan')
                ->type('color', 'Silver')
                ->type('year', '2018')
                ->press('Register Vehicle')
                ->assertPathIs('/dmv/plate/1')// TODO we want /dmv/plate/{tag}
                ->assertSeeIn('.alert-success', 'Vehicle registered successfully!');
        });

        $this->assertDatabaseHas('license_plates', [
            'id' => $style->id,
            'user_id' => $user->id,
            'style_id' => 1,
            'make' => 'Toyota',
            'model' => 'Camry',
            'class' => 'Sedan',
            'color' => 'Silver',
            'year' => 2018
        ]);
    }

    public function testUserCanSeeValidationErrors()
    {
        $user = factory(User::class)->create();
        $style = factory(LicensePlateStyle::class)->create();

        $this->browse(function (Browser $browser) use ($user, $style) {
            $browser->loginAs($user)
                ->visit('/dmv/plate/create')
                ->radio('style_id', $style->id)
                ->type('make', 'Toyota')
                ->type('model', 'Camry')
                ->type('class', 'Sedan')
                ->type('color', 'Silver')
                ->type('year', '2100')
                ->press('Register Vehicle')
                ->assertPathIs('/dmv/plate/create')
                ->assertSee('The year must be a date before next year.');
        });

        $this->assertDatabaseMissing('license_plates', [
            'user_id' => $user->id
        ]);
    }
}
