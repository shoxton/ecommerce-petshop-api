<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_index_route_fetches_products(): void
    {
        $product = \App\Models\Product::factory()->create(['title' => 'Sample product']);

        $this->getJson(route('product.index'), [])
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'Sample product',
            ]);
    }

    public function test_product_index_route_is_paginated(): void
    {

        $product = \App\Models\Product::factory()->times(20)->create();
        $recentlyAddedProduct = \App\Models\Product::factory()->create(['title' => 'Sample product']);

        $this->getJson(route('product.index'), [])
            ->assertOk()
            ->assertJsonStructure(['total', 'per_page', 'current_page', 'last_page', 'data'])
            ->assertJsonFragment([
                'total' => 21,
            ])
            ->assertDontSee('Sample product');

        $this->getJson(route('product.index', ['page' => 2]), [])
            ->assertOk()
            ->assertJsonStructure(['total', 'per_page', 'current_page', 'last_page', 'data'])
            ->assertJsonFragment([
                'current_page' => 2,
            ])
            ->assertSee('Sample product');

    }
}
