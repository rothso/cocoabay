<?php

namespace Tests\Browser;

use App\LicensePlateStyle;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LicensePlateBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanCreatePlate()
    {
        $user = factory(User::class)->create();
        $style = factory(LicensePlateStyle::class)->create();

        // TODO implement form
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
                ->assertPathIs('/dmv/plate/1') // TODO we want /dmv/plate/{tag}
                ->assertSeeIn('.alert-success', 'Vehicle registered successfully!');
        });

        $this->assertDatabaseHas('license_plates', [
            'id' => $style->id,
            'user_id' => $user->id,
            'style_id' => 1,
            'make' => 'Toyota',
            'model' => 'Camry',
            'class' => 'Sedan',
            'year' => 2018
        ]);
    }
}
