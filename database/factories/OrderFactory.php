<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_id' => Address::factory()->create()->id,
            'shipping_method_id' => ShippingMethod::factory()->create()->id,
            'subtotal' => 1000
        ];
    }
}
