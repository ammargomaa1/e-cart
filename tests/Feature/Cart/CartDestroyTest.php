<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('DELETE', 'api/cart/1')
            ->assertStatus(401);
    }


    public function test_it_fails_if_the_product_can_not_be_found()
    {
        $user = User::factory()->create();

        $response = $this->jsonAs($user,'DELETE', 'api/cart/1')
            ->assertStatus(404);
    }


    public function test_it_deletes_the_item_from_the_cart()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = ProductVariation::factory()->create()
        );
        $response = $this->jsonAs($user,'DELETE', "api/cart/{$product->id}");

        $this->assertDatabaseMissing('cart_user',[
            'product_variation_id' => $product->id
        ]);
    }
}
