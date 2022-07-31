<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('GET', 'api/cart')
            ->assertStatus(401);
    }

    public function test_it_shows_products_in_users_cart()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = ProductVariation::factory()->create()
        );
        $response = $this->jsonAs($user,'GET', 'api/cart')
            ->assertJsonFragment([
                'id' => $product->id
            ]);
    }

    public function test_it_shows_formatted_subtotal()
    {
        $user = User::factory()->create();

        
        $response = $this->jsonAs($user,'GET', 'api/cart')
            ->assertJsonFragment([
                'subtotal' => "0"
            ]);
    }

    public function test_it_shows_formatted_total()
    {
        $user = User::factory()->create();

        
        $response = $this->jsonAs($user,'GET', 'api/cart')
            ->assertJsonFragment([
                'total' => "0"
            ]);
    }
}
