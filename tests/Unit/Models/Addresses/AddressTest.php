<?php

namespace Tests\Unit\Models\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class AddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_one_country()
    {
        $address = Address::factory()->create([
            'user_id' =>User::factory()->create()->id
        ]);

        $this->assertInstanceOf(Country::class, $address->country);
        
    }

    public function test_it_belongs_to_a_user()
    {
        $address = Address::factory()->create([
            'user_id' =>User::factory()->create()->id
        ]);

        $this->assertInstanceOf(User::class, $address->user);
        
    }

    public function test_it_sets_old_addresses_to_not_default_when_creating()
    {
        $user = User::factory()->create();

        $oldAddress = Address::factory()->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        Address::factory()->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        $this->assertFalse((bool)$oldAddress->fresh()->default);
        
    }
}
