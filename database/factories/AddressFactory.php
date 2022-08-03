<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address_1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code'=> $this->faker->postcode,
            'country_id' => Country::factory()->create()->id,
        ];
    }
}