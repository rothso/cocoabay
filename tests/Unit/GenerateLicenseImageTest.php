<?php

namespace Tests\Unit;

use App\DriversLicense;
use App\Events\DriversLicenseSaving;
use App\Listeners\GenerateLicenseImage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateLicenseImageTest extends TestCase
{
    use DatabaseMigrations;

    public function setup()
    {
        parent::setUp();

        // We want to manually save licenses without triggering duplicate unwanted events
        $this->withoutEvents();

        // Images are generated into the public disk
        Storage::fake('public');
    }

    public function testShouldGenerateImage()
    {
        // Make license
        $license = factory(DriversLicense::class)->make();

        // Handle event
        $listener = new GenerateLicenseImage();
        $listener->handle(new DriversLicenseSaving($license));

        // Should upload image and store path
        $this->assertNotNull($license->image);
        Storage::disk('public')->assertExists($license->image);
    }

    public function testNoChangesShouldNotGenerateImage()
    {
        // Make the license
        $license = factory(DriversLicense::class)->make();

        // Handle event
        $listener = new GenerateLicenseImage();
        $listener->handle(new DriversLicenseSaving($license));

        // Save the license, so now no attributes are "dirty"
        $license->save();

        // Delete artifacts and fire the event again with the same license
        Storage::disk('public')->delete($license->image);
        $listener->handle(new DriversLicenseSaving($license));

        // A new image should not have been generated, since the model was unchanged
        Storage::disk('public')->assertMissing($license->image);
    }

    public function testShouldDeleteOrphanedImageOnUpdate()
    {
        // Make the license
        $license = factory(DriversLicense::class)->make();

        // Handle event
        $listener = new GenerateLicenseImage();
        $listener->handle(new DriversLicenseSaving($license));
        $license->save();

        // Image to be orphaned
        $oldImage = $license->image;

        // Update the license and resend the event
        $license->number++;
        $listener->handle(new DriversLicenseSaving($license));
        $license->save();

        // New generated image
        $newImage = $license->image;

        // The orphaned image should have been replaced
        $this->assertNotEquals($oldImage, $newImage);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($newImage);
    }
}
