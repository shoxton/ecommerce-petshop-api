<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_delete_its_account()
    {

        $this->withoutExceptionHandling();

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

        // dd($jwt);

        $this->deleteJson(route('user.destroy'), [],  [
            'Authorization' => 'Bearer ' . $jwt
        ])->dump()->assertOk();

        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@example.com'
        ]);
    }
}
