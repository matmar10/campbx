<?php

namespace Matmar10\Campbx\Resource;

use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\ExchangeRate;
use Matmar10\Money\Entity\Money;

/**
 * Encapsulates a market depth point which has an exchange rate and order volume
 *
 * @package matmar10-cambpx
 */
class MarketDepthPrice extends ExchangeRate
{

    /**
     * The order volume at this exchange rate
     *
     * @var \Matmar10\Money\Entity\Money
     */
    protected $amount;

    /**
     * Constructs a new market depth point
     *
     * @param float $priceAsFloat The BTC:USD exchange rate
     * @param float $amount The amount of market supply or demand at this exchange rate
     */
    public function __construct($priceAsFloat, $amount)
    {
        $this->fromCurrency = new Currency('BTC', 8, 8, 'B⃦');
        $this->toCurrency = new Currency('USD', 2, 2, '$');
        $this->multiplier = $priceAsFloat;

        $this->amount = new Money($this->fromCurrency);
        $this->amount->setAmountFloat($amount);
    }

    /**
     * Returns the amount of demand at this exchange rate
     *
     * @return \Matmar10\Money\Entity\Money The amount of demand
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
