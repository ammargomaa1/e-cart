<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_shows_collection_of_products()
    {
        $product = Product::factory()->create();

        $this->get('api/products')->assertJsonFragment([
            'id' => $product->id
        ]);
    }

    public function test_it_has_paginated_data()
    {
        $this->get('api/products')
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);
    }
}
