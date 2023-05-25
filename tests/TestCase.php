<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function actingAsJwtUser()
    {
        $user = \App\Models\User::factory()->create();

        return $this->actingAs($user, 'jwt');
    }

    public function actingAsJwtAdmin()
    {
        $admin = \App\Models\User::factory()->admin()->create();

        return $this->actingAs($admin, 'jwt');
    }
}
