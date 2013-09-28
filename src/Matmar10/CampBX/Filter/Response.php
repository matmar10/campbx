<?php

namespace Matmar10\CampBX\Filter;

use DateInterval;
use DateTime;
use DateTimeZone;
use Matmar10\Campbx\Parameter;
use Matmar10\Campbx\Resource\MarketDepthPrice;
use Matmar10\Campbx\Resource\ResourceProxy;
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

    public static function marshalStringToDateTime($input, $timezone)
    {
        return new DateTime($input, new DateTimeZone($timezone));
    }

    public static function marshalMarginPercent($input)
    {
        if(Parameter::MARGIN_PERCENT_ZERO === $input) {
            return 0;
        }

        return (float)$input;
    }

    public static function parseBoolean($input)
    {
        return Parameter::$booleans[$input];
    }

    public static function marshalResourceProxy($input)
    {
        return new ResourceProxy($input, array_keys($input));
    }

    public static function normalizeOrdersListEmptyArray($input)
    {
        if(1 === count($input)) {
            $firstElement = reset($input);
            if(false !== array_search('Info', $firstElement->getValidKeys())) {
                return array();
            }
        }
        return $input;
    }

    public static function convertSecondsToDateTime($secondsFromNow)
    {
        $date = new DateTime('now', new DateTimeZone('GMT'));
        $date->add(DateInterval::createFromDateString('+ ' . $secondsFromNow . ' seconds'));
        return $date;
    }
}
