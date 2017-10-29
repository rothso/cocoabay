<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicensePlateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        // For controlling expiry
        Carbon::setTestNow(Carbon::now());
    }


    public function testDirtySaveShouldGenerateAndSaveImage()
    {
        //
    }

    public function testGenerateNewImageShouldDeleteOldImage()
    {
        //
    }
}
