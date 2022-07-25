<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{

    use RefreshDatabase;
    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('PATCH', 'api/cart/1')
            ->assertStatus(401);
    }

    public function test_it_fails_if_the_product_can_not_be_found()
    {
        $user = User::factory()->create();

        $response = $this->jsonAs($user,'PATCH', 'api/cart/1')
            ->assertStatus(404);
    }


    public function test_it_requires_quantity()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $response = $this->jsonAs($user,'PATCH', "api/cart/{$product->id}")
            ->assertJsonValidationErrors('quantity');
    }

    public function test_it_requires_a_numeric_quantity()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $response = $this->jsonAs($user,'PATCH', "api/cart/{$product->id}",[
            'quantity' => 'one'
        ])
            ->assertJsonValidationErrors('quantity');
    }

    public function test_it_requires_a_quantity_of_one_or_more()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $response = $this->jsonAs($user,'PATCH', "api/cart/{$product->id}",[
            'quantity' => 0
        ])
            ->assertJsonValidationErrors('quantity');
    }


    public function test_it_updates_the_quantity()
    {
        $user = User::factory()->create();


        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),[
                'quantity'=>1
            ]
            );
        
        $response = $this->jsonAs($user,'PATCH', "api/cart/{$product->id}",[
            'quantity' => 5
        ]);

        $this->assertDatabaseHas('cart_user',[
            'product_variation_id'=>$product->id,
            'quantity'=>5
        ]);
    }
}
