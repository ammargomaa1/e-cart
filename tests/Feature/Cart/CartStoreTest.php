<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartStoreTest extends TestCase
{
    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('POST', 'api/cart')
            ->assertStatus(401);
    }

    public function test_it_requires_products()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart')
            ->assertJsonValidationErrors(['products']);
    }

    public function test_it_requires_products_to_be_an_array()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => 1
        ])
            ->assertJsonValidationErrors(['products']);
    }

    public function test_each_product_requires_id()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity'=>1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_to_exist()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity'=>1,'id'=>1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_quantity_to_be_numeric()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity'=>'ad','id'=>1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_it_requires_products_quantity_to_be_at_least_one()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity'=>0,'id'=>1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_it_can_add_products_to_users_cart()
    {
        $user = User::factory()->create();
        $product = ProductVariation::factory()->create();
        $response = $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity'=>1,'id'=>$product->id]
            ]
        ]);

        $this->assertDatabaseHas('cart_user',[
            'product_variation_id' => $product->id,
            'quantity' => 1,
            'user_id' => $user->id
        ]);
    }
}
