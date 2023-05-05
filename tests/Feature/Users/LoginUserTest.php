<?php

namespace Tests\Feature\Users;

use App\Services\JWTService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token;
use Mockery;
use Tests\TestCase;

class LoginUserTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_login_returns_valid_token(): void
    {

        $user = \App\Models\User::factory()->create(['email' => 'johndoe@example.com']);

        $response = $this->postJson(route('user.login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ])->assertJsonStructure(['token']);
    }

    public function test_login_with_invalid_credentials_returns_validation_error()
    {
        $this->withoutExceptionHandling();

        $user = \App\Models\User::factory()->create(['email' => 'johndoe@example.com']);

        $response = $this->postJson(route('user.login'), [
            'email' => 'johndoe@example.com',
            'password' => 'wrongpass'
        ])->assertJson(['error' => 'Invalid credentials.']);

    }
}
