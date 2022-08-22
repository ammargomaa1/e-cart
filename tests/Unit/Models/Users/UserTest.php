<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashes_password_while_creating()
    {
        $user = User::factory()->create([
            'password'=>'cats'
        ]);

        $this->assertNotEquals('cats',$user->password);
    }

    public function test_it_has_many_cart_products()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $this->assertInstanceOf(ProductVariation::class,$user->cart->first());
    }

    public function test_it_has_quantity_foreach_cart_product()
    {
        $user = User::factory()->create();
        
        $user->cart()->attach(
            ProductVariation::factory()->create(),[
                'quantity' => $quantity = 5
            ]
        );

        $this->assertEquals($user->cart->first()->pivot->quantity,$quantity);
    }

    public function test_it_has_many_addresses()
    {
        $user = User::factory()->create();

        $user->addresses()->save(
            Address::factory()->make()
        );

        $this->assertInstanceOf(Address::class,$user->addresses->first());
    }

    public function test_it_has_many_orders()
    {
        $user = User::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(Order::class, $user->orders->first());

    }
}
