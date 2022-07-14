<?php

namespace Tests\Unit\Models\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashes_password_while_creating()
    {
        $user = User::factory()->create([
            'password'=>'cats'
        ]);

        $this->assertNotEquals('cats',$user->password);
    }
}
