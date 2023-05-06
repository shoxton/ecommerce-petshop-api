<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_be_updated(): void
    {

        $user = \App\Models\User::factory()->create(['email' => 'johndoe@example.com']);

        $response = $this->postJson(route('user.login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ])->assertJsonStructure(['token']);

        $jwt = $response->json('token');

        $this->putJson(
            route('user.update'),
            ['first_name' => 'John'],
            ['Authorization' => 'Bearer ' . $jwt]
        )->assertOk();

        $this->assertDatabaseHas('users', [
            'first_name' => 'John'
        ]);
    }
}
