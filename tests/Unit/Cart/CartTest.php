<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
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

        $this->assertCount(1, $user->fresh()->cart);
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

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
    }

    public function test_it_can_update_quantities_in_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 1
            ]
        );

        $cart->update($product->id, 2);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
    }

    public function test_it_can_delete_a_product_from_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 1
            ]
        );

        $cart->delete($product->id);

        $this->assertCount(0, $user->fresh()->cart);
    }

    public function test_it_can_empty_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create()
        );

        $cart->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }

    public function test_it_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create()
        );



        $this->assertInstanceOf(Money::class, $cart->subTotal());
    }


    public function test_it_gets_the_correct_subtotal()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create([
                'price' => 1000
            ]),
            [
                'quantity' => 2
            ]
        );



        $this->assertEquals(2000, $cart->subTotal()->amount());
    }

    public function test_it_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create()
        );



        $this->assertInstanceOf(Money::class, $cart->total());
    }

    public function test_it_syncs_the_cart_to_update_quantities()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $product = ProductVariation::factory()->create();

        $anotherProduct = ProductVariation::factory()->create();

        $user->cart()->attach(
            [
                $product->id =>
                [
                    'quantity' => 2
                ],
                $anotherProduct->id =>
                [
                    'quantity' => 2
                ]
            ]
        );

        $cart->sync();

        $this->assertEquals(0, $user->fresh()->cart->first()->quantity);
        $this->assertEquals(0, $user->fresh()->cart->get(1)->quantity);

    }

    public function test_it_can_check_if_the_cart_has_changed_after_syncing()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        
        $product = ProductVariation::factory()->create();

        $anotherProduct = ProductVariation::factory()->create();

        $user->cart()->attach(
            [
                $product->id =>
                [
                    'quantity' => 2
                ],
                $anotherProduct->id =>
                [
                    'quantity' => 0
                ]
            ]
        );


        $cart->sync();

        $this->assertTrue($cart->hasChanged());
    }


    public function test_it_doesnt_change_the_cart_if_no_change_made()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $cart->sync();

        $this->assertFalse($cart->hasChanged());
    }

    public function test_it_can_return_the_correct_total_without_shipping()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create([
                'price' =>1000
            ]),
            [
                'quantity' => 2
            ]
        );

        $this->assertEquals($cart->total()->amount(), 2000);
    }

    public function test_it_can_return_the_correct_total_with_shipping()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $shipping = ShippingMethod::factory()->create([
            'price' => 1000
        ]);

        $user->cart()->attach(
            $product = ProductVariation::factory()->create([
                'price' =>1000
            ]),
            [
                'quantity' => 2
            ]
        );

        $cart = $cart->withShipping($shipping->id);

        $this->assertEquals($cart->total()->amount(), 3000);
    }

    public function test_it_returns_products_in_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );


        $user->cart()->attach(
            $product = ProductVariation::factory()->create([
                'price' =>1000
            ]),
            [
                'quantity' => 2
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $cart->products()->first());
    }
}
