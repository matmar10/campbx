<?php

namespace Matmar10\Campbx\Test;

use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\Money;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Tests\GuzzleTestCase;

class CampBXClientTest extends GuzzleTestCase
{

    protected $plugin;
    protected $client;

    public function setUp()
    {
        $this->plugin = new MockPlugin();
        $this->client = $this->getServiceBuilder()->get('test.campbx');
        $this->client->addSubscriber($this->plugin);
    }

    /**
     * @dataProvider provideTestGetMarketDepth
     */
    public function testGetMarketDepth($response)
    {
        $this->plugin->addResponse($response);
        $command = $this->client->getCommand('getMarketDepth');
        $result = $this->client->execute($command);

        $asks = $result->get('ask');
        $this->assertInternalType('array', $asks);
        $ask = reset($asks);
        $this->assertInstanceOf('\\Matmar10\\CampBX\\Resource\\MarketDepthPrice', $ask);
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Currency', $ask->getFromCurrency());
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Currency', $ask->getToCurrency());
        $this->assertInternalType('float', $ask->getMultiplier());
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Money', $ask->getAmount());

        // test scenario when you want to buy $1234.56 USD worth of BTC
        $baseCurrency = new Currency('USD', 2, 2, '$');
        $desiredAmountAsUsd = new Money($baseCurrency);
        $desiredAmountAsUsd->setAmountFloat(1234.56);
        $tradeCurrency = new Currency('BTC', 8, 8, 'B⃦');
        $expectedAmountAsBtc = new Money($tradeCurrency);
        $expectedAmountAsBtc->setAmountFloat(4.93);

        $calculatedAmountAsBtc = $ask->convert($desiredAmountAsUsd);
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Money', $calculatedAmountAsBtc);
        $this->assertEquals($expectedAmountAsBtc, $calculatedAmountAsBtc);

        $bids = $result->get('bid');
        $this->assertInternalType('array', $bids);
        $bid = reset($bids);
        $this->assertInstanceOf('\\Matmar10\\CampBX\\Resource\\MarketDepthPrice', $bid);
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Currency', $bid->getFromCurrency());
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Currency', $bid->getToCurrency());
        $this->assertInternalType('float', $bid->getMultiplier());
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Money', $bid->getAmount());


        // test scenario when you want to buy $1234.56 USD worth of BTC
        $tradeCurrency = new Currency('BTC', 8, 8, 'B⃦');
        $baseCurrency = new Currency('USD', 2, 2, '$');

        $desiredSellAmountAsBtc = new Money($tradeCurrency);
        $desiredSellAmountAsBtc->setAmountFloat(1234.56);

        $expectedAmountAsUsd = new Money($baseCurrency);
        $expectedAmountAsUsd->setAmountFloat(302467.20);

        $calculatedAmountAsUsd = $bid->convert($desiredSellAmountAsBtc);
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Money', $calculatedAmountAsBtc);
        $this->assertEquals($expectedAmountAsUsd, $calculatedAmountAsUsd);

    }

    public function provideTestGetMarketDepth()
    {
        return array(
            array(
                new Response(200, null, '{ "Asks":[ [ 250, 18.40000000 ], [ 250.01, 0.10000000 ], [ 250.5, 0.03000000 ], [ 300, 35.00000000 ], [ 999, 0.01000000 ], [ 1000, 8.27000000 ], [ 1111, 11.11000000 ], [ 1111.1, 0.10000000 ] ], "Bids":[ [ 245, 10.00000000 ], [ 220, 2.00000000 ], [ 110.01, 91.28515746 ], [ 110, 26.29271574 ], [ 109, 18.24825842 ], [ 101.1, 4.30374200 ], [ 98, 2.24930951 ], [ 97, 1.60091670 ], [ 96.5, 0.14300000 ], [ 95, 7.00000000 ], [ 92, 5.00000000 ], [ 90, 90.00000000 ], [ 85, 55.00000000 ], [ 80, 5.00000000 ], [ 75, 10.00000000 ], [ 69, 4.14541950 ], [ 67, 10.00000000 ], [ 65, 100.01000000 ], [ 60, 0.01000000 ], [ 55, 0.01000000 ], [ 50, 0.01000000 ], [ 45, 0.01000000 ], [ 40, 0.01000000 ], [ 35, 0.01000000 ], [ 30, 0.01000000 ], [ 25, 0.01000000 ], [ 20, 0.01000000 ], [ 15, 0.02000000 ], [ 10, 0.01000000 ], [ 5, 1.01000000 ], [ 3, 3.00000000 ], [ 1, 1.01000000 ], [ 0.15, 500.00000000 ], [ 0.13, 0.01000000 ], [ 0.12, 1000.01000000 ] ] }'),
            ),
        );
    }

    /**
     * @dataProvider provideTestGetTicker
     */
    public function testGetTicker($response)
    {
        $this->plugin->addResponse($response);
        $command = $this->client->getCommand('getTicker');
        $result = $this->client->execute($command);

        $lastTrade = $result->get('lastTrade');
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\ExchangeRate', $lastTrade);

        $bid = $result->get('bid');
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\ExchangeRate', $bid);

        $ask = $result->get('ask');
        $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\ExchangeRate', $ask);

    }

    public function provideTestGetTicker()
    {
        return array(
            array(
                new Response(200, null, '{"Last Trade":"122.50","Best Bid":"122.40","Best Ask":"122.50"}'),
            ),
        );
    }
}
