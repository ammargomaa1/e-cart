<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('GET', 'api/addresses/1/shipping')
            ->assertStatus(401);
    }

    public function test_it_fails_if_address_cant_be_found()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'GET', 'api/addresses/1/shipping')
            ->assertStatus(404);
    }

    public function test_it_fails_if_the_user_doesnt_own_the_address()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id'=> User::factory()->create()->id
        ]);


        $response = $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping")
            ->assertStatus(403);
    }

    public function test_it_shows_shipping_methods_for_a_given_address()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id'=> $user->id,
            'country_id'=> ($country = Country::factory()->create())->id
        ]);

        $country->shippingMethods()->save(
            $shipping = ShippingMethod::factory()->create()
        );


        $response = $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping")
            ->assertJsonFragment([
                'name' => $shipping->name,
                'id' => $shipping->id
            ]);
    }
}
