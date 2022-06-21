<?php

namespace App\Cart;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;

class Money
{
    protected $money;

    public function __construct($value)
    {
        $this->money = new BaseMoney($value,new Currency('EGP'));
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new \NumberFormatter('en_EGP',\NumberFormatter::DECIMAL),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }

}
