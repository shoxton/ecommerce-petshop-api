<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_is_created_persisted_and_returns_jwt_token(): void
    {
        $this->withoutExceptionHandling();

        $userData = [
            "first_name" => "John",
            "last_name" => "Doe",
            "address" => "758 Gibson Stravenue Suite 866",
            "phone_number" => "501.737.1571",
            "email" => "johndoe@example.com",
            'password' => 'johndoe1234',
            "password_confirmation" => "johndoe1234",
        ];

        $response = $this->postJson(route('user.store'), $userData)
            ->assertCreated();

        $jwt = $response->json('data.token');

        $response->assertJsonFragment(['token' => $jwt]);

        $this->assertDatabaseHas('users', [
            'first_name' => 'John'
        ]);
    }

    public function test_cannot_create_a_user_with_an_already_existing_email(): void
    {

        $johndoe = \App\Models\User::factory()->create(['email' => 'johndoe@example.com']);

        $userData = [
            "first_name" => "John",
            "last_name" => "Doe",
            "address" => "758 Gibson Stravenue Suite 866",
            "phone_number" => "501.737.1571",
            "email" => "johndoe@example.com",
            'password' => 'johndoe1234',
            "password_confirmation" => "johndoe1234",
        ];

        $response = $this->postJson(route('user.store'), $userData)
            ->assertJsonValidationErrorFor('email');
    }

    public function test_user_can_be_created_with_minimal_info(): void
    {

        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'johndoe1234',
            'password_confirmation' => 'johndoe1234'
        ];

        $this->postJson(route('user.store'), $userData)
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com'
        ]);

    }
}
