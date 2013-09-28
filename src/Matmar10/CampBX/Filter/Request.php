<?php

namespace Matmar10\CampBX\Filter;

use DateInterval;
use DateTime;
use DateTimeZone;
use Matmar10\Campbx\Resource\MarketDepthPrice;
use Matmar10\Campbx\Resource\ResourceProxy;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\Money;
use RuntimeException;

class Request
{

    public static function convertMoneyToFloat(Money $input)
    {
        return $input->getAmountFloat();
    }

    public static function convertTradeOrderType($input, $isQuickBuyOrSell = false)
    {

        $prefix = ($isQuickBuyOrSell) ? 'Quick' : '';

        if('BID' === $input) {
            return $prefix . 'Buy';
        }

        if('ASK' === $input) {
            return $prefix . 'Sell';
        }

        throw new RuntimeException(sprintf("Invalid trade order type '%s' specified", $input));
    }

    public static function convertTradeOrderAmountToQuantity($input)
    {
        if($input instanceof Money)
        {
            self::assertCurrencyCode($input, 'BTC');
            return $input->getAmountFloat();
        }

        // assume float
        return $input;
    }

    public static function convertTradeOrderPrice($input)
    {
        if($input instanceof Money)
        {
            self::assertCurrencyCode($input, 'USD');
            return $input->getAmountFloat();
        }

        // assume float
        return $input;
    }

    public static function assertCurrencyCode(Money $money, $code)
    {
        if($code !== $money->getCurrency()->getCurrencyCode()) {
            throw new RuntimeException(sprintf("Unsupported currency '%s' provided", $money->getCurrency()->getCurrencyCode()));
        }
    }
}
