<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_many_children()
    {
        $category = Category::factory()->create();

        $category->children()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class,$category->children->first());
    }

    public function test_it_can_fetch_only_parents(){
        $category = Category::factory()->create();
        $category->children()->save(
            Category::factory()->create()
        );

        $this->assertEquals(1,Category::parents()->count());
    }

    public function test_it_is_orderable_by_a_numbered_order(){
        $category = Category::factory()->create([
            'order' => 2
        ]);

        $anotherCategory = Category::factory()->create([
            'order' => 1
        ]);

        $this->assertEquals($anotherCategory->name,Category::ordered()->first()->name);

    }
}
