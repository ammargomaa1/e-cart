<?php

namespace Tests\Unit\Models\Countries;

use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CountryTest extends TestCase
{

    use RefreshDatabase;
    public function test_it_has_many_shipping_methods()
    {
        $country = Country::factory()->create();

        $country->shippingMethods()->attach(
            ShippingMethod::factory()->create()
        );

        $this->assertInstanceOf(ShippingMethod::class,$country->shippingMethods->first());
    }
}
