<?php

namespace Matmar10\CampBX\Filter;

use Matmar10\Campbx\Resource\MarketDepthPrice;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\Money;

class Response
{
    public static function convertMarketDepth($input)
    {
        $marketDepth = array();
        foreach($input as $depthData) {
            list($price, $ordersValue) = $depthData;
            $marketDepthPrice = new MarketDepthPrice((float)$price, (float)$ordersValue);
            array_push($marketDepth, $marketDepthPrice);
        }
        return $marketDepth;
    }

    public static function asMoneyFromFloat(
        $input,
        $currencyCode,
        $calculationPrecision,
        $displayPrecision,
        $symbol
    )
    {
        $currency =  new Currency($currencyCode, $calculationPrecision, $displayPrecision, $symbol);
        $amount = new Money($currency);
        $amount->setAmountFloat($input);
        return $amount;
    }


    public static function asCurrencyPairFromFloat(
        $input,
        $fromCurrencyCode,
        $fromCurrencyCalculationPrecision,
        $fromCurrencyDisplayPrecision,
        $toCurrencyCode,
        $toCurrencyCalculationPrecision,
        $toCurrencyDisplayPrecision,
        $fromCurrencySymbol = '',
        $toCurrencySymbol= ''
    )
    {
        $fromCurrency = new Currency($fromCurrencyCode, $fromCurrencyCalculationPrecision, $fromCurrencyDisplayPrecision, $fromCurrencySymbol);
        $toCurrency = new Currency($toCurrencyCode, $toCurrencyCalculationPrecision, $toCurrencyDisplayPrecision, $toCurrencySymbol);
        $pair = new CurrencyPair($fromCurrency, $toCurrency, (float)$input);
        return $pair;
    }
}
