<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductShowTest extends TestCase
{

    use RefreshDatabase;

    public function test_product_show_route_can_fetch_product_by_uuid(): void
    {

        $product = \App\Models\Product::factory()->create(['title' => 'Sample product']);

        $this->getJson(route('product.show', [$product]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'title' => 'Sample product',
                'uuid' => $product->uuid
            ]);
    }
}
