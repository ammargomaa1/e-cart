<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_it_returns_collection_of_categories(){
            $categories = Category::factory()->count(2)->create();

            $response = $this->get('api/categories');

            $categories->each(function ($category) use ($response){
                $response->assertJsonFragment([
                    'slug' => $category->slug
                ]);
            });

    }

    public function test_it_returns_only_parent_categories(){
        $category = Category::factory()->create();

        $category->children()->save(
             Category::factory()->create()
        );
        $this->get('api/categories')->assertJsonCount(1,'data');

    }

    public function test_it_returns_categories_by_their_given_order(){
        $category = Category::factory()->create([
            'order' => 2
        ]);

        $anotherCategory = Category::factory()->create([
            'order' => 1
        ]);


        $this->get('api/categories')->assertSeeInOrder([
            $anotherCategory->slug ,
            $category->slug
        ]);

    }

}
