<?php

namespace Tests\Browser;

use App\LicensePlate;
use App\LicensePlateStyle;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
                ->visit(route('plate.create'));

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
                ->visit(route('plate.create'))
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
                ->visit(route('plate.create'))
                ->radio('style_id', $style->id)
                ->type('make', 'Toyota')
                ->type('model', 'Camry')
                ->type('class', 'Sedan')
                ->type('color', 'Silver')
                ->type('year', '2100')
                ->press('Register Vehicle')
                ->assertRouteIs('plate.create')
                ->assertSee('The year must be a date before next year.');
        });

        $this->assertDatabaseMissing('license_plates', [
            'user_id' => $user->id
        ]);
    }

    public function testOwnerCanSeeListingOfAllPlates()
    {
        $user = factory(User::class)->create();
        $plates = factory(LicensePlate::class, 3)->create(['user_id' => $user->id]);

        $this->browse(function (Browser $browser) use ($user, $plates) {
            $browser->loginAs($user)
                ->visit('/dmv/plate');

            foreach ($plates as $plate) {
                $browser->assertSee($plate->tag)
                    ->assertSee($plate->make)
                    ->assertSee($plate->model);
            }
        });
    }

    public function testOwnerCanSeeSpecificPlateDetails()
    {
        $user = factory(User::class)->create();
        $plate = factory(LicensePlate::class)->create(['user_id' => $user->id]);

        $this->browse(function (Browser $browser) use ($user, $plate) {
            $browser->loginAs($user)
                ->visit('/dmv/plate/1')
                ->assertSee($plate->tag)
                ->assertSee($plate->make)
                ->assertSee($plate->model);
        });
    }

    public function testOwnerCanSeeEditForm()
    {
        $user = factory(User::class)->create();
        $plate = factory(LicensePlate::class)->create(['user_id' => $user->id]);

        $this->browse(function (Browser $browser) use ($user, $plate) {
            $browser->loginAs($user)
                ->visit('dmv/plate/' . $plate->id . '/edit')
                ->assertInputValue('style_id', $plate->style->id)
                ->assertInputValue('make', $plate->make)
                ->assertInputValue('model', $plate->model)
                ->assertInputValue('class', $plate->class)
                ->assertInputValue('color', $plate->color)
                ->assertInputValue('year', $plate->year);
        });
    }

    public function testStrangerCannotSeeUnownedEditForm()
    {
        $stranger = factory(User::class)->create();
        $plate = factory(LicensePlate::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->browse(function (Browser $browser) use ($stranger, $plate) {
            $browser->loginAs($stranger)
                ->visit('dmv/plate/' . $plate->id . '/edit')
                ->assertMissing('form')
                ->assertTitleContains('Page Not Found');
        });
    }
}
