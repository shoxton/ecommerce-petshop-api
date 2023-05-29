<?php

namespace Tests\Feature\Products;

use Tests\TestCase;

class ProductUpdateTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_product_can_be_updated_and_changes_persisted_to_db(): void
    {

        $product = \App\Models\Product::factory()->create();

        $this->actingAsJwtAdmin()->putJson(route('product.update', [$product]), [
            'title' => 'Product updated title'
        ])->assertSuccessful()
            ->assertJsonFragment([
                'title' => 'Product updated title'
            ]);

        $this->assertDatabaseHas('products', [
            'title' => 'Product updated title',
        ]);

    }

    public function test_product_update_validates_data_sent(): void
    {

        $product = \App\Models\Product::factory()->create();

        $this->actingAsJwtAdmin()->putJson(route('product.update', [$product]), ['title' => 1])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('title');

    }

    public function test_only_admin_users_can_update_products(): void
    {

        $product = \App\Models\Product::factory()->create();

        $this->putJson(route('product.update', [$product]), [
            'title' => 'Product updated title'
        ])->assertUnauthorized();

        $this->actingAsJwtUser()->putJson(route('product.update', [$product]), [
            'title' => 'Product updated title'
        ])->assertUnauthorized();

        $this->actingAsJwtAdmin()->putJson(route('product.update', [$product]), [
            'title' => 'Product updated title'
        ])->assertSuccessful();

    }
}
