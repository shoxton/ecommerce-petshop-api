<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_user()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'johndoe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $response = $this->postJson(route('user.login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ])->assertJsonStructure(['token']);

        $jwt = $response->json('token');

        $this->getJson(route('user.show'), [
            'Authorization' => 'Bearer ' . $jwt
        ])->assertJsonFragment([
            'email' => 'johndoe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }
}
