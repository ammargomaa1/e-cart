<?php

namespace Tests\Unit\Money;

use App\Cart\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Money\Money as BaseMoney;
use Tests\TestCase;

class MoneyTest extends TestCase
{

    use RefreshDatabase;


    public function test_it_can_get_the_raw_amount()
    {
        $money = new Money(1000);
        
        $this->assertEquals($money->amount(), 1000);
    }

    public function test_it_can_get_the_formatted_amount()
    {
        $money = new Money(1000);
        
        $this->assertEquals((int)$money->formatted(), (int)'EGP 10.00');
    }

    public function test_it_can_add_up()
    {
        $money = new Money(1000);
        $money = $money->add(new Money(1000));
        $this->assertEquals($money->amount(), 2000);
    }

    public function test_it_can_return_the_underlying_instance()
    {
        $money = new Money(1000);
        
        $this->assertInstanceOf(BaseMoney::class,$money->instance());
    }
}
