<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreUserTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_user_is_created_and_persisted_with_correct_data(): void
    {
        $this->withoutExceptionHandling();

        $userData = \App\Models\User::factory()
            ->state(['first_name' => "John"])
            ->raw();

        $this->postJson(route('user.store'), $userData)->assertCreated();

        $this->assertDatabaseHas('users', [
            'first_name' => 'John'
        ]);
    }
}
