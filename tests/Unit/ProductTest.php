<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_uuid_is_automatically_filled(): void
    {

        $product = \App\Models\Product::factory()->create();

        $this->assertNotNull($product->uuid);

    }
}
