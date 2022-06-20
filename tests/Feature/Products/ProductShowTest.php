<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_a_product_can_not_be_found()
    {
        $this->get('api/products/nope')->assertStatus(404);
    }

    public function test_it_shows_product()
    {
        $product = Product::factory()->create();

        $anotherProduct = Product::factory()->create();

        $this->get("api/products/{$product->slug}")->assertJsonFragment([
            'id' => $product->id
        ]);
    }
}
