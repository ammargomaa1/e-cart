<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_requires_an_email()
    {
        $this->json('POST','api/auth/login')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_password()
    {
        $this->json('POST','api/auth/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_returns_validation_error ()
    {
        $user = User::factory()->create();

        $this->json('POST','api/auth/login',[
            'email'=>$user->email,
            'password'=>'nope'
        ])
            ->assertJsonValidationErrors(['email']);
    }


    public function test_it_returns_a_token_if_credentials_do_match ()
    {
        $user = User::factory()->create([
            'password' =>'cats'
        ]);

        $this->json('POST','api/auth/login',[
            'email'=>$user->email,
            'password'=>'cats'
        ])
            ->assertJsonStructure([
                'meta'=>[
                    'token'
                ]
            ]);
    }

    public function test_it_returns_a_user_if_credentials_do_match ()
    {
        $user = User::factory()->create([
            'password' =>'cats'
        ]);

        $this->json('POST','api/auth/login',[
            'email'=>$user->email,
            'password'=>'cats'
        ])
            ->assertJsonFragment([
                'email'=>$user->email
            ]);
    }

}
