<?php

namespace Matmar10\CampBX\Filter;

use DateInterval;
use DateTime;
use DateTimeZone;
use Matmar10\Campbx\Parameter;
use Matmar10\Campbx\Resource\ResourceProxy;
use Matmar10\Money\CurrencyExchange\Entity\MarketDepthPrice;
use Matmar10\Money\CurrencyExchange\Entity\MarketDepthCollection;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\ExchangeRate;
use Matmar10\Money\Entity\Money;

class Response
{
    // CurrencyInterface $fromCurrency = null, CurrencyInterface $toCurrency = null, $rate = null, $type = null, MoneyInterface $depth = null
    public static function convertMarketDepth(
        $input,
        $fromCurrencyCode,
        $fromCurrencyPrecision,
        $fromCurrencyDisplayPrecision,
        $fromCurrencySymbol,
        $toCurrencyCode,
        $toCurrencyPrecision,
        $toCurrencyDisplayPrecision,
        $toCurrencySymbol,
        $type,
        $depthCurrency,
        $depthCurrencyPrecision,
        $depthCurrencyDisplayPrecision,
        $depthCurrencySymbol
    )
    {
        $fromCurrency = new Currency($fromCurrencyCode, $fromCurrencyPrecision, $fromCurrencyDisplayPrecision, $fromCurrencySymbol);
        $toCurrency = new Currency($toCurrencyCode, $toCurrencyPrecision, $toCurrencyDisplayPrecision, $toCurrencySymbol);
        $currencyPair = new CurrencyPair($fromCurrency, $toCurrency);
        $amountCurrency = new Currency($depthCurrency, $depthCurrencyPrecision, $depthCurrencyDisplayPrecision, $depthCurrencySymbol);
        $marketDepthCollection = new MarketDepthCollection($currencyPair);
        foreach($input as $depthData) {
            list($rate, $ordersValue) = $depthData;
            $ordersAmount = new Money($amountCurrency);
            $ordersAmount->setAmountFloat($ordersValue);
            $marketDepthPrice = new MarketDepthPrice($fromCurrency, $toCurrency, $rate, $type, $ordersAmount);
            $marketDepthCollection->add($marketDepthPrice);
        }
        return $marketDepthCollection;
    }

    public static function asMoneyFromFloat(
        $input,
        $currencyCode,
        $calculationPrecision,
        $displayPrecision,
        $symbol
    )
    {
        $currency = new Currency($currencyCode, $calculationPrecision, $displayPrecision, $symbol);
        $amount = new Money($currency);
        $amount->setAmountFloat($input);
        return $amount;
    }

    public static function asExchangeRateFromFloat(
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
        $pair = new ExchangeRate($fromCurrency, $toCurrency, (float)$input);
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
        return Parameter::$booleanResponses[$input];
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
