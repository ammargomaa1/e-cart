<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_requires_a_name()
    {
         $this->json('POST','api/auth/register')
        ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST','api/auth/register')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_requires_a_email()
    {
        $this->json('POST','api/auth/register')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('POST','api/auth/register',[
            'email'=>'anything that is not an email'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_unique_email()
    {
        $user = User::factory()->create();
        $this->json('POST','api/auth/register',[
            'email'=>$user->email
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST','api/auth/register',[
            'email'=>$email = 'a@A.a',
            'password'=>'apples',
            'name' => 'Ahmed Mohamed'
        ]);

        $this->assertDatabaseHas('users',['email'=>$email]);
    }

    public function test_it_returns_a_user_on_registration()
    {
        $this->json('POST','api/auth/register',[
            'email'=>$email = 'a@A.a',
            'password'=>'apples',
            'name' => 'Ahmed Mohamed'
        ])->assertJsonFragment([
            'email' => $email
        ]);

    }

}
