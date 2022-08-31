<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('GET', 'api/orders')
            ->assertStatus(401);
    }

    public function test_it_returns_a_collection_of_orders()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);
        $response = $this->jsonAs($user, 'GET', 'api/orders')
            ->assertJsonFragment([
                'id' => $order->id
            ]);
    }

    public function test_it_orders_by_the_latest_first()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $anotherOrder = Order::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDay()
        ]);


        $response = $this->jsonAs($user, 'GET', 'api/orders')
            ->assertSeeInOrder([
                $order->created_at->toDateTimeString(),
                $anotherOrder->created_at->toDateTimeString(),

            ]);
    }

    public function test_it_has_pagination()
    {
        $user = User::factory()->create();


        $this->jsonAs($user, 'GET', 'api/orders')
                    ->assertJsonStructure([
                        'links',
                        'meta'

                    ]);
    }
}
