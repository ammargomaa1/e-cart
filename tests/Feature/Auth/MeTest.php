<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_the_user_is_not_authenticated()
    {
        $this->json('GET','api/auth/me')
            ->assertStatus(401);
    }

    public function test_it_fails_if_the_user_details()
    {
        $user = User::factory()->create();
        
        $this->jsonAs($user,'GET','api/auth/me')
            ->assertJsonFragment([
                'email'=>$user->email
            ]);
    }
}
