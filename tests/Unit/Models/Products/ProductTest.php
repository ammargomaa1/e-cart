<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_uses_the_slug_for_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(),'slug');
    }

    public function test_it_has_many_products(){
        $product = Product::factory()->create();

        $product->categories()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class,$product->categories->first());
    }


    public function test_it_has_many_variations(){

        $product = Product::factory()->create();

        $product->variations()->save(
            ProductVariation::factory()->create([
                'product_id' => $product->id
            ])
        );

        $this->assertInstanceOf(ProductVariation::class,$product->variations->first());
    }

    public function test_it_returns_a_money_instance_for_the_price(){
        $product = Product::factory()->create();

        $this->assertInstanceOf(Money::class,$product->price);
    }

    public function test_it_returns_formatted_price(){
        $product = Product::factory()->create([
            'price' => 1050
        ]);

        $this->assertEquals('10.5',$product->formattedPrice);
    }

    public function test_it_can_check_if_its_in_stock(){
        $product = Product::factory()->create();
        $product->variations()->save(
            $variation = ProductVariation::factory()->create()
        );

        $variation->stocks()->save(
            Stock::factory()->make()
        );


        $this->assertTrue((boolean)$product->inStock());
    }

    public function test_it_can_get_the_stock_count(){
        $product = Product::factory()->create();
        $product->variations()->save(
            $variation = ProductVariation::factory()->create()
        );

        $variation->stocks()->save(
            Stock::factory()->make([
                'quantity' => $quantity = 5
            ])
        );


        $this->assertEquals($product->stockCount(),$quantity);
    }
}
