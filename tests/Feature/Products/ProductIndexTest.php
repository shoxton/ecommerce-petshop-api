<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{

    use RefreshDatabase;

    public function test_unnauthenticated_user_cannot_fetch_products_list(): void
    {

        $products = \App\Models\Product::factory()->times(10)->create();

        $this->getJson(route('product.index'), [])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    public function test_authenticated_user_can_fetch_products_list(): void
    {
        $product = \App\Models\Product::factory()->create(['title' => 'Sample product']);

        $this->actingAsJwtUser()->getJson(route('product.index'), [])
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'Sample product',
            ]);
    }

    public function test_products_listing_is_paginated(): void
    {

        $product = \App\Models\Product::factory()->times(20)->create();
        $recentlyAddedProduct = \App\Models\Product::factory()->create(['title' => 'Sample product']);

        $this->actingAsJwtUser()->getJson(route('product.index'), [])
            ->assertOk()
            ->assertJsonStructure(['total', 'per_page', 'current_page', 'last_page', 'data'])
            ->assertJsonFragment([
                'total' => 21,
            ])
            ->assertDontSee('Sample product');

        $this->actingAsJwtUser()->getJson(route('product.index', ['page' => 2]), [])
            ->assertOk()
            ->assertJsonStructure(['total', 'per_page', 'current_page', 'last_page', 'data'])
            ->assertJsonFragment([
                'current_page' => 2,
            ])
            ->assertSee('Sample product');

    }
}
