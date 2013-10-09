<?php

namespace Matmar10\CampBX\Filter;

use DateInterval;
use DateTime;
use DateTimeZone;
use Matmar10\CampBX\Exception\InvalidArgumentException;
use Matmar10\CampBX\Parameter;
use Matmar10\Campbx\Resource\MarketDepthPrice;
use Matmar10\Campbx\Resource\ResourceProxy;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\Money;
use RuntimeException;

class Request
{

    public static function convertMoneyToFloat(Money $input)
    {
        return $input->getAmountFloat();
    }

    public static function convertTradeOrderType($input, $quickOrAdvanced = null)
    {

        if(is_null($quickOrAdvanced)) {
            if(false === array_key_exists($input, Parameter::$tradeOrderTypes)) {
                throw new InvalidArgumentException(sprintf(
                    "Invalid trade order type '%s' specified (valid types are: %s)",
                    $input,
                    implode(',', array_keys(Parameter::$tradeOrderTypes))
                ));
            }
            return Parameter::$tradeOrderTypes[$input];
        }

        if(Parameter::QUICK_TRADE_ORDER === $quickOrAdvanced) {
            if(false === array_key_exists($input, Parameter::$quickTradeOrderTypes)) {
                throw new InvalidArgumentException(sprintf(
                    "Invalid trade order type '%s' specified (valid types are: %s)",
                    $input,
                    implode(',', array_keys(Parameter::$quickTradeOrderTypes))
                ));
            }
            return Parameter::$quickTradeOrderTypes[$input];
        }

        if(Parameter::ADVANCED_TRADE_ORDER === $quickOrAdvanced) {
            if(false === array_key_exists($input, Parameter::$advancedTradeOrderTypes)) {
                throw new InvalidArgumentException(sprintf(
                    "Invalid trade order type '%s' specified (valid types are: %s)",
                    $input,
                    implode(',', array_keys(Parameter::$advancedTradeOrderTypes))
                ));
            }
            return Parameter::$advancedTradeOrderTypes[$input];
        }

        throw new InvalidArgumentException(sprintf(
            "Invalid argument for quickOrAdvanced '%' specified (expected '%s' or '%s')",
            $quickOrAdvanced,
            Parameter::ADVANCED_TRADE_ORDER,
            Parameter::QUICK_TRADE_ORDER
        ));
    }

    public static function convertTradeOrderAmountToQuantity($input)
    {
        if($input instanceof Money) {
            self::assertCurrencyCode($input, 'BTC');
            return $input->getAmountFloat();
        }

        // assume float
        return $input;
    }

    public static function convertTradeOrderPrice($input)
    {
        if(Parameter::PRICE_MARKET_ORDER === $input) {
            return $input;
        }

        if($input instanceof Money) {
            self::assertCurrencyCode($input, 'USD');
            return $input->getAmountFloat();
        }

        // assume float
        return $input;
    }

    public static function assertCurrencyCode(Money $money, $code)
    {
        if($code !== $money->getCurrency()->getCurrencyCode()) {
            throw new InvalidArgumentException(sprintf(
                "Unsupported currency '%s' provided",
                $money->getCurrency()->getCurrencyCode()
            ));
        }
    }

    public static function convertTradeOrderFillType($input)
    {
        if(false === array_key_exists($input, Parameter::$tradeOrderFillTypes)) {
            throw new InvalidArgumentException(sprintf(
                "Unsupported fill type '%s' provided (valid types are %s)",
                $input,
                implode(',', array_keys(Parameter::$tradeOrderFillTypes))
            ));
        }
        return Parameter::$tradeOrderFillTypes[$input];
    }

    public static function convertBoolean($input)
    {
        return Parameter::$booleans[(boolean)$input];
    }
}
