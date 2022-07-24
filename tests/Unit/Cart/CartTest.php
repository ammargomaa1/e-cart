<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_add_products_to_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $product = ProductVariation::factory()->create();

        $cart->add([
            ['id' => $product->id , 'quantity' => 1]
        ]);

        $this->assertCount(1,$user->fresh()->cart);
    }

    public function test_it_increments_quantity_when_adding_more_products()
    {
        $product = ProductVariation::factory()->create();


        $cart = new Cart(
            $user = User::factory()->create()
        );

        

        $cart->add([
            ['id' => $product->id , 'quantity' => 1]
        ]);

        $cart = new Cart(
            $user->fresh()
        );

        $cart->add([
            ['id' => $product->id , 'quantity' => 1]
        ]);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity,2);
    }
}
