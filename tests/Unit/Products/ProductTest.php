<?php

namespace Tests\Unit\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
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
}
