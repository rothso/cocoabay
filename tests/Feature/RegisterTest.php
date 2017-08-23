<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    // TODO:
    // - testMissingHeaderShouldFailSilently
    // - testInvalidDataShouldFail
    // - testExistingAccount~ShouldUpdate

    public function testHappyRegistration()
    {
        // Potential user data from the client
        $uuid = '2e045f99-4807-4672-966f-b5af446f27be';
        $username = 'username';
        $name = 'displayName';
        $password = 'password';

        // Attempt to register the user
        $response = $this->post('api/register', [
            'uuid' => $uuid,
            'username' => $username,
            'name' => $name,
            'password' => $password
        ]);

        // Should successfully save
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'uuid' => $uuid,
            'username' => $username,
            'name' => $name
        ]);
    }
}
