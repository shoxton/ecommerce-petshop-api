<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_it_creates_a_user(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->assertInstanceOf(\App\Models\User::class, $user);
    }

    public function test_uuid_is_automatically_filled()
    {

        $user = \App\Models\User::factory()->create();

        $this->assertNotNull($user->uuid);
        $this->assertIsString($user->uuid);

    }

    public function test_is_admin_helper_returns_valid_boolean(): void
    {

        $user = \App\Models\User::factory()->create();

        $this->assertFalse($user->isAdmin());

        $admin = \App\Models\User::factory()->admin()->create();

        $this->assertTrue($admin->isAdmin());

    }
}
