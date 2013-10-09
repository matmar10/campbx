<?php

namespace Matmar10\CampBX;

class Parameter
{

    static $booleans = array(
        true => 'Yes',
        false => 'No',
    );

    static $booleanResponses = array(
        'Yes' => true,
        'No' => false,
        0 => false,
        1 => true,
    );

    const QUICK_TRADE_ORDER = 'QUICK';
    const ADVANCED_TRADE_ORDER = 'ADVANCED';

    const TRADE_ORDER_TYPE_ASK = 'ASK';
    const TRADE_ORDER_TYPE_BID = 'BID';

    static $tradeOrderTypes = array(
        self::TRADE_ORDER_TYPE_ASK => 'Sell',
        self::TRADE_ORDER_TYPE_BID => 'Buy',
    );

    static $quickTradeOrderTypes = array(
        self::TRADE_ORDER_TYPE_ASK => 'QuickSell',
        self::TRADE_ORDER_TYPE_BID => 'QuickBuy',
    );

    static $advancedTradeOrderTypes = array(
        self::TRADE_ORDER_TYPE_ASK => 'AdvancedSell',
        self::TRADE_ORDER_TYPE_BID => 'AdvancedBuy',
    );

    const PRICE_MARKET_ORDER = 'Market';

    const TRADE_ORDER_FILL_TYPE_INCREMENTAL = 'INCREMENTAL';
    const TRADE_ORDER_FILL_TYPE_ALL_OR_NOTHING = 'ALL_OR_NOTHING';
    const TRADE_ORDER_FILL_TYPE_FILL_OR_KILL = 'FILL_OR_KILL';

    static $tradeOrderFillTypes = array(
        self::TRADE_ORDER_FILL_TYPE_INCREMENTAL => 'Incremental',
        self::TRADE_ORDER_FILL_TYPE_ALL_OR_NOTHING => 'AON',
        self::TRADE_ORDER_FILL_TYPE_FILL_OR_KILL => 'FOK',
    );

    const MARGIN_PERCENT_ZERO = 'None';

    const TICKER_TYPE_ASK = 'ask';
    const TICKER_TYPE_BID = 'bid';

}
