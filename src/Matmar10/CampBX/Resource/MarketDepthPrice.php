<?php

namespace Matmar10\Campbx\Resource;

use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\Money;

class MarketDepthPrice extends CurrencyPair
{

    protected $price;
    protected $amount;

    public function __construct($priceAsFloat, $amount)
    {
        $this->fromCurrency = new Currency('BTC', 8, 8, 'B⃦');
        $this->toCurrency = new Currency('USD', 2, 2, '$');
        $this->multiplier = $priceAsFloat;

        $this->price = new Money($this->toCurrency);
        $this->price->setAmountFloat($priceAsFloat);

        $this->amount = new Money($this->fromCurrency);
        $this->amount->setAmountFloat($amount);
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
