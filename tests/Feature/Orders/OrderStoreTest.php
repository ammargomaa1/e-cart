<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        
        $response = $this->jsonAs($user,'POST', 'api/orders')
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_exists()
    {
        $user = User::factory()->create();
        
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
        
        $response = $this->jsonAs($user,'POST', 'api/orders',[
            'address_id' => $address->id
        ])
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_a_shipping_method()
    {
        $user = User::factory()->create();
        
        $response = $this->jsonAs($user,'POST', 'api/orders')
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_requires_a_shipping_method_that_exists()
    {
        $user = User::factory()->create();
        
        $response = $this->jsonAs($user,'POST', 'api/orders',[
            'shipping_method_id' => 6
        ])
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    
}
