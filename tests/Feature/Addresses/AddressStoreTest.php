<?php

namespace Tests\Feature\Addresses;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_user_unauthenticated()
    {
        $response = $this->json('POST', 'api/addresses')
            ->assertStatus(401);
    }

    public function test_it_requires_a_name()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses')
            ->assertJsonValidationErrors([
                'name'
            ]);
    }

    public function test_it_requires_a_address_1()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses')
            ->assertJsonValidationErrors([
                'address_1'
            ]);
    }

    public function test_it_requires_a_city()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses')
            ->assertJsonValidationErrors([
                'city'
            ]);
    }

    public function test_it_requires_a_postal_code()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses')
            ->assertJsonValidationErrors([
                'postal_code'
            ]);
    }

    public function test_it_requires_a_country_id()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses')
            ->assertJsonValidationErrors([
                'country_id'
            ]);
    }

    public function test_it_requires_a_country_id_to_exist()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses',[
            'country_id' => 1
        ])
            ->assertJsonValidationErrors([
                'country_id'
            ]);
    }

    public function test_it_stores_an_address()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses',$payload = [
            'name' =>'Ammar',
            'address_1' =>'blah blah',
            'postal_code' => '123',
            'city' =>'Alexandria',
            'country_id' => Country::factory()->create()->id
        ]);

        $this->assertDatabaseHas('addresses',$payload + [
            'user_id' =>$user->id
        ]);
    }

    public function test_it_returns_an_address_when_its_stored()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user,'POST', 'api/addresses',$payload = [
            'name' =>'Ammar',
            'address_1' =>'blah blah',
            'postal_code' => '123',
            'city' =>'Alexandria',
            'country_id' => Country::factory()->create()->id
        ]);

        $response->assertJsonFragment([
            'id' => json_decode($response->getContent())->data->id
        ]);

    }
}
