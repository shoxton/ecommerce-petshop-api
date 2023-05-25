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
            ->assertUnauthorized();

        // test admin users
        $this->actingAsJwtAdmin()
            ->postJson(route('product.store'), ['title' => 'Lorem ipsum'])
            ->assertCreated();
    }
}
