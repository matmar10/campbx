<?php

namespace Matmar10\Campbx\Test;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Tests\GuzzleTestCase;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\Money;

class CampBXAuthClientTest extends GuzzleTestCase
{

    protected $plugin;
    protected $client;

    public function setUp()
    {
        $this->plugin = new MockPlugin();
        $this->client = $this->getServiceBuilder()->get('test.campbx_auth');
        $this->client->addSubscriber($this->plugin);
    }

    /**
     * @dataProvider provideTestGetAccountBalancesData
     */
    public function testGetAccountBalances(Response $response, array $balances)
    {
        $this->plugin->addResponse($response);
        $command = $this->client->getCommand('getAccountBalances');
        $result = $this->client->execute($command);
        foreach($balances as $accountName => $expectedBalance) {
            $accountBalance = $result->get($accountName);
            $this->assertInstanceOf('\\Matmar10\\Money\\Entity\\Money', $accountBalance, sprintf('Retrieve balance for %s account', $accountName));
            $this->assertEquals($expectedBalance, $accountBalance->getAmountFloat());
        }
    }

    public function provideTestGetAccountBalancesData()
    {
        return array(
            array(
                new Response(200, null, '{"Total USD":"1.23","Total BTC":"4.56000000","Liquid USD":"7.89","Liquid BTC":"9.87000000","Margin Account USD":"6.54","Margin Account BTC":"3.21000000"}'),
                array(
                    'totalUSD' => 1.23,
                    'totalBTC' => 4.56,
                    'liquidUSD' => 7.89,
                    'liquidBTC' => 9.87,
                    'marginUSD' => 6.54,
                    'marginBTC' => 3.21,
                )
            ),
        );
    }

    /**
     * @dataProvider provideTestGetOrdersListData
     */
    public function testGetOrdersList($response, $instanceTypes, $internalTypes, $countBid, $countSell)
    {
        $this->plugin->addResponse($response);
        $command = $this->client->getCommand('getOrdersList');
        $result = $this->client->execute($command);

        $buyOrders = $result->get('bid');
        $this->assertInternalType('array', $buyOrders);
        $this->assertCount($countBid, $buyOrders);
        if($countBid) {
            foreach($buyOrders as $buyOrder) {
                foreach($instanceTypes as $responseParam => $expectedType) {
                    $methodName = 'get' . ucfirst($responseParam);
                    $this->assertInstanceOf($expectedType, $buyOrder->$methodName());
                }
                foreach($internalTypes as $responseParam => $expectedType) {
                    $methodName = 'get' . ucfirst($responseParam);
                    $this->assertInternalType($expectedType, $buyOrder->$methodName());
                }
            }
        }

        $sellOrders = $result->get('ask');
        $this->assertInternalType('array', $sellOrders);
        $this->assertCount($countSell, $sellOrders);
        if($countSell) {
            foreach($sellOrders as $sellOrder) {
                foreach($instanceTypes as $responseParam => $expectedType) {
                    $methodName = 'get' . ucfirst($responseParam);
                    $this->assertInstanceOf($expectedType, $sellOrder->$methodName());
                }
                foreach($internalTypes as $responseParam => $expectedType) {
                    $methodName = 'get' . ucfirst($responseParam);
                    $this->assertInternalType($expectedType, $sellOrder->$methodName());
                }
            }
        }
    }

    public function provideTestGetOrdersListData()
    {
        $objectParameterExpectedTypes = array(
            'entered' => '\\DateTime',
            'expires' => '\\DateTime',
            'quantity' => '\\Matmar10\\Money\\Entity\\Money',
            'price' => '\\Matmar10\\Money\\Entity\\CurrencyPair',
        );
        $primitiveParameterExpectedTypes = array(
            'type' => 'string',
            'marginPercent' => 'float',
            'stopLoss' => 'bool',
            'fillType' => 'string',
            'darkPool' => 'bool',
            'orderId' => 'string',
        );
        return array(
            array(
                new Response(200, null, '{"Buy":[{"Order Entered":"2013-09-22 00:11:19","Order Expiry":"2013-12-29 00:00:00","Order Type":"Quick Buy","Margin Percent":"None","Quantity":"1.00000000","Price":"0.52","Stop-loss":"No","Fill Type":"Incremental","Dark Pool":"No","Order ID":"1567535"}],"Sell":[{"Info":"No open Sell Orders"}]}'),
                $objectParameterExpectedTypes,
                $primitiveParameterExpectedTypes,
                1,
                0
            ),
        );
    }

    /**
     * @dataProvider provideTestGenerateWalletAddressData
     */
    public function testGenerateWalletAddress($response, $expectedWalletAddress, $expectedDateTime)
    {
        $this->plugin->addResponse($response);
        $command = $this->client->getCommand('generateDepositAddress');
        $result = $this->client->execute($command);
        $this->assertEquals($expectedWalletAddress, $result->get('walletAddress'));


    }

    public function provideTestGenerateWalletAddressData()
    {
        return array(
            array(
                new Response(200, null, '{"Success":"1Dtdcx5iWc3PEYmdKtfLg3jLYXUZGdJVHy","Expiry":1473298}'),
                '1Dtdcx5iWc3PEYmdKtfLg3jLYXUZGdJVHy',
                1473298,
            ),
        );
    }

    /**
     * @dataProvider provideTestPlaceTradeOrderData
     */
    public function testPlaceTradeOrder($type, $amount, $price, $response, $expectedTradeOrderId)
    {
        $this->plugin->addResponse($response);
        $command = $this->client->getCommand('placeTradeOrder');

        $btc = new Currency('BTC', 8, 8);
        $amountBtc = new Money($btc);
        $amountBtc->setAmountFloat($amount);

        $usd = new Currency('USD', 2, 2);
        $priceUsd = new Money($usd);
        $priceUsd->setAmountFloat($price);

        $command->set('type', $type);
        $command->set('amount', $amountBtc);
        $command->set('price', $priceUsd);

        $result = $this->client->execute($command);
        $this->assertEquals($expectedTradeOrderId, $result->get('tradeOrderId'));
    }

    public function provideTestPlaceTradeOrderData()
    {
        return array(
            array(
                'bid',
                0.01,
                10,
                new Response(200, null, '{"Success":"1581755"}'),
                '1581755',
            ),
        );
    }
}
