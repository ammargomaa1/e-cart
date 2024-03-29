<?php

namespace Tests\Feature\Orders;

use App\Events\Order\OrderCreated;
use App\Models\Address;
use App\Models\Country;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('POST', 'api/orders')
            ->assertStatus(401);
    }

    public function test_it_requires_an_address()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );
        
        $response = $this->jsonAs($user,'POST', 'api/orders')
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_exists()
    {
        $user = User::factory()->create();

        
$user->cart()->sync(
    $product = $this->productWithStock()
);

        
        $response = $this->jsonAs($user,'POST', 'api/orders',[
            'address_id' => 6
        ])
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_belongs_to_the_authenticated_user()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        
$user->cart()->sync(
    $product = $this->productWithStock()
);

        
        $response = $this->jsonAs($user,'POST', 'api/orders',[
            'address_id' => $address->id
        ])
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_a_shipping_method()
    {
        $user = User::factory()->create();

        
$user->cart()->sync(
    $product = $this->productWithStock()
);

        
        $response = $this->jsonAs($user,'POST', 'api/orders')
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_requires_a_shipping_method_that_exists()
    {
        $user = User::factory()->create();

        
$user->cart()->sync(
    $product = $this->productWithStock()
);

        
        $response = $this->jsonAs($user,'POST', 'api/orders',[
            'shipping_method_id' => 6
        ])
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_requires_a_shipping_method_valid_for_the_given_address()
    {
        $user = User::factory()->create();

        
$user->cart()->sync(
    $product = $this->productWithStock()
);


        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $shipping = ShippingMethod::factory()->create();
        
        $response = $this->jsonAs($user,'POST', 'api/orders',[
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id
        ])
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_can_create_an_order()
    {
        $user = User::factory()->create();
        list($address, $shipping) = $this->orderDependencies($user);

        
        $user->cart()->sync(
            $product = $this->productWithStock()
        );
        


        $this->jsonAs($user,'POST', 'api/orders',[
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);
        $this->assertDatabaseHas('orders',[
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);
    }

    public function test_it_attaches_the_products_to_the_order() {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);

        $this->assertDatabaseHas('product_variation_order', [
                    'product_variation_id' => $product->id,
                    'order_id' => $response->json()['data']['id']
                ]);

    }

    public function test_it_fails_to_create_order_if_cart_is_empty() {

        $user = User::factory()->create();

        $user->cart()->sync([
            ($product = $this->productWithStock())->id =>
            [
                'quantity' => 0
            ]
        ]);

        list($address, $shipping) = $this->orderDependencies($user);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ])->assertStatus(400);

        

    }

    protected function productWithStock(){
        $product = ProductVariation::factory()->create();

        Stock::factory()->create([
            'product_variation_id' => $product->id
        ]);

        return $product;
    }

    protected function orderDependencies(User $user)
    {
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $shipping = ShippingMethod::factory()->create();

        $shipping->countries()->attach($address->country);

        return [$address, $shipping];

    }

    public function test_it_fires_an_order_created_event() {
        Event::fake();
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);


        Event::assertDispatched(OrderCreated::class,function($event) use ($response) {
            return $event->order->id ===  $response->json()['data']['id'];
        });

    }

    public function test_it_empties_the_cart_when_ordering() {
        
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping) = $this->orderDependencies($user);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);


        $this->assertEmpty($user->cart);

    }

}
