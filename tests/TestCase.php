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
}
