<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    CONST UUID = '12345aaa-4807-4672-966f-b5af446f27be';
    CONST USERNAME = 'firstname.lastname';
    CONST NAME = 'Firstname Resident';
    CONST PASSWORD = 'super_secure123!!';

    // This is actually a real IP (for login.agni.Lindenlab.com), to avoid the need to mock the DNS
    const SL_SERVER = '216.82.55.233';

    public function setUp()
    {
        parent::setUp();

        // The real Hash::make returns something different every time, so mock it
        Hash::shouldReceive('make')->with(self::PASSWORD)->andReturn("hashed");
        Hash::shouldReceive('make')->with(self::PASSWORD, Mockery::type('array'))->andReturn("hashed");
    }

    public function testHappyRegistration()
    {
        // Attempt to register the user
        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => self::SL_SERVER])
            ->post('api/register', [
                'uuid' => $this::UUID,
                'username' => $this::USERNAME,
                'name' => $this::NAME,
                'password' => $this::PASSWORD
            ]);

        // Should successfully save
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'uuid' => $this::UUID,
            'username' => $this::USERNAME,
            'name' => $this::NAME,
            'password' => Hash::make($this::PASSWORD)
        ]);
    }

    public function testExistingAccountShouldUpdate()
    {
        $newName = 'NewName Resident';
        $newPassword = 'secret_abc123';

        Hash::shouldReceive('make')->with($newPassword)->andReturn('hashed2');

        // Register the user the first time
        $this
            ->withServerVariables(['REMOTE_ADDR' => self::SL_SERVER])
            ->post('api/register', [
                'uuid' => $this::UUID,
                'username' => $this::USERNAME,
                'name' => $this::NAME,
                'password' => $this::PASSWORD
            ]);

        // Send a different request for the same user
        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => self::SL_SERVER])
            ->post('api/register', [
                'uuid' => $this::UUID,
                'username' => $this::USERNAME,
                'name' => $newName,
                'password' => $newPassword
            ]);

        // Should successfully update the existing user
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'uuid' => $this::UUID,
            'name' => $newName,
            'password' => Hash::make($newPassword)
        ]);
    }

    public function testInvalidDataShouldFail()
    {
        $badUuid = '1';

        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => self::SL_SERVER])
            ->post('api/register', [
                'uuid' => $badUuid,
                'username' => $this::USERNAME,
                'name' => $this::NAME,
                'password' => $this::PASSWORD
            ]);

        $response->assertStatus(422);
    }

    public function testOutsideRequestsShouldFail()
    {
        $badRemoteIp = '93.184.216.34'; // cannot use 127.0.0.1 for some reason

        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => $badRemoteIp])
            ->post('api/register', [
                'uuid' => $this::UUID,
                'username' => $this::USERNAME,
                'name' => $this::NAME,
                'password' => $this::PASSWORD
            ]);

        $response->assertStatus(404);
    }
}
