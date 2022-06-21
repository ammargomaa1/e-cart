<?php

namespace Tests\Unit\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_one_variation_type()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(ProductVariationType::class,$variation->type);
    }

    public function test_it_belongs_to_a_product()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Product::class,$variation->product);
    }

    public function test_it_returns_a_money_instance_for_the_price(){
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Money::class,$variation->price);
    }

    public function test_it_returns_formatted_price(){
        $variation = ProductVariation::factory()->create([
            'price' => 1050
        ]);

        $this->assertEquals('10.5',$variation->formattedPrice);
    }

    public function test_it_returns_the_product_price_if_null(){

        $product = Product::factory()->create([
            'price' => 1000
        ]);
        $variation = ProductVariation::factory()->create([
            'price' => null,
            'product_id' => $product->id
        ]);

        $this->assertEquals($product->formattedPrice,$variation->formattedPrice);
    }

    public function test_it_can_check_if_the_variation_price_is_different_to_the_product(){

        $product = Product::factory()->create([
            'price' => 1000
        ]);
        $variation = ProductVariation::factory()->create([
            'price' => 2000,
            'product_id' => $product->id
        ]);

        $this->assertTrue($variation->priceVaries());
    }


}
