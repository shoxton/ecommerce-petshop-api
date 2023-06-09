<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_can_be_deleted(): void
    {

        $product = \App\Models\Product::factory()->create(['title' => 'Product to be deleted']);

        $this->actingAsJwtAdmin()->deleteJson(route('product.destroy', [$product]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('products', [
            'uuid' => $product->uuid,
            'title' => 'Product to be deleted'
        ]);

    }

    public function test_only_admin_users_can_delete_products(): void
    {

        $product = \App\Models\Product::factory()->create();


        $this->deleteJson(route('product.destroy', [$product]))
            ->assertUnauthorized();

        $this->actingAsJwtUser()->deleteJson(route('product.destroy', [$product]))
            ->assertForbidden();


        $this->actingAsJwtAdmin()->deleteJson(route('product.destroy', [$product]))
            ->assertSuccessful();

        $this->assertDatabaseMissing('products', [
            'uuid' => $product->uuid,
            'title' => 'Product to be deleted'
        ]);
    }

    public function test_admin_user_can_delete_product_passing_valid_jwt(): void
    {

        $user = \App\Models\User::factory()->admin()->create([
            'email' => 'johndoe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $response = $this->postJson(route('user.login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ])->assertJsonStructure(['token']);

        $jwt = $response->json('token');

        $product = \App\Models\Product::factory()->create();

        $this->deleteJson(route('product.destroy', [$product]), [], [
            'Authorization' => 'Bearer ' . $jwt
        ])->assertSuccessful();

    }
}
