<?php

namespace Tests\Unit;

use App\LicensePlate;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Storage;
use Tests\TestCase;

class LicensePlateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        // For controlling expiry
        Carbon::setTestNow(Carbon::now());

        // Avoid polluting the public directory with fake license plate images
        Storage::fake('public');
    }

    public function testCreateShouldGenerateTagIfEmpty()
    {
        $plate = factory(LicensePlate::class)->create();
        $this->assertNotEmpty($plate->tag);
    }

    public function testCreateShouldNotGenerateTagIfFilled()
    {
        $plate = factory(LicensePlate::class)->create(['tag' => 'S4MPL3']);
        $this->assertEquals('S4MPL3', $plate->tag);
    }

    public function testCreateShouldSetExpiry()
    {
        $plate = factory(LicensePlate::class)->create();
        $this->assertGreaterThan(Carbon::now(), $plate->expires_at);
    }

    public function testDirtySaveShouldGenerateAndSaveImage()
    {
        $plate = factory(LicensePlate::class)->create();
        Storage::disk('public')->assertExists($plate->image);
    }

    public function testGenerateNewImageShouldDeleteOldImage()
    {
        $plate = factory(LicensePlate::class)->create();
        $oldImage = $plate->iamge;

        $plate->touch();
        $newImage = $plate->image;

        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($newImage);
    }
}
