<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_store_route_persists_new_product_to_db(): void
    {

        $product = \App\Models\Product::factory()->make(['title' => 'New product']);

        $this->actingAsJwtAdmin()->postJson(route('product.store'), $product->toArray())
            ->assertCreated()
            ->assertJsonStructure(['title', 'uuid'])
            ->assertJsonFragment([
                'title' => 'New product',
            ]);

        $this->assertDatabaseHas('products', [
            'title' => 'New product',
        ]);

    }

    public function test_product_validates_data_sent(): void
    {

        $this->actingAsJwtAdmin()->postJson(route('product.store'), [])
            ->assertJsonValidationErrorFor('title');

    }

    public function test_only_admin_users_can_store_new_products(): void
    {

        // test unauthenticated users
        $this->postJson(route('product.store'), ['title' => 'Lorem ipsum'])
            ->assertUnauthorized();

        // test unauthorized users
        $this->actingAsJwtUser()
            ->postJson(route('product.store'), ['title' => 'Lorem ipsum'])
            ->assertForbidden();

        // test admin users
        $this->actingAsJwtAdmin()
            ->postJson(route('product.store'), ['title' => 'Lorem ipsum'])
            ->assertCreated();
    }

    public function test_admin_user_can_store_product_passing_valid_jwt(): void
    {

        $admin = \App\Models\User::factory()->admin()->create([
            'email' => 'johndoe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $response = $this->postJson(route('user.login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ])->assertJsonStructure(['token']);

        $jwt = $response->json('token');

        $this->postJson(route('product.store'), [
            'title' => 'Test product'
        ], [
            'Authorization' => 'Bearer ' . $jwt
        ])->assertCreated();

    }
}
