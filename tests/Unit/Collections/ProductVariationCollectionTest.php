<?php

namespace Tests\Unit\Collections;

use App\Models\Collections\ProductVariationCollection;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariationCollectionTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_get_a_syncing_array()
    {
        $user = User::factory()->create();


        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => $quantity = 2
            ]
        );

        $collection = new ProductVariationCollection($user->cart);

        $this->assertEquals([
            $product->id => [
                'quantity' => $quantity
            ]
        ], $collection->forSyncing());
    }
}
