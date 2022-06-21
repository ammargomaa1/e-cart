<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductScopingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_scope_by_category()
    {
        $product = Product::factory()->create();

        $product->categories()->save(
            $category = Category::factory()->create()
        );

        $anotherProduct = Product::factory()->create();

        $anotherProduct->categories()->save(
            Category::factory()->create()
        );

        $this->get("api/products?category={$category->slug}")->assertJsonCount(1,'data');
    }
}
