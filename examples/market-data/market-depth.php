<?php

include __DIR__ . '/../../vendor/autoload.php';

use Matmar10\CampBX\CampBXClient;

// to use fancy output format in CLI results
setlocale(LC_MONETARY, 'en_US');

// use leading spaces to align nicely
// from: http://php.net/manual/en/function.money-format.php
$usdFormat = '%(#6n';
$btcFormat = '%= ^-16#8.8i';

$client = CampBXClient::factory();

$command = $client->getCommand('getMarketDepth');
$response = $client->execute($command);

echo "\n\n";
echo "Market depth for SELL orders: \n\n";
echo "Sell price          Depth (in BTC)\n";
echo "----------------------------------\n";
foreach($response->get('ask') as $marketDepthPrice) {
    echo money_format($usdFormat, $marketDepthPrice->getPrice()->getAmountFloat());
    echo money_format($btcFormat, $marketDepthPrice->getAmount()->getAmountFloat());
    echo "\n";
}

echo "\n\n";
echo "Market depth for BUY orders: \n\n";
echo "Buy price          Depth (in BTC)\n";
echo "----------------------------------\n";
foreach($response->get('bid') as $marketDepthPrice) {
    echo money_format($usdFormat, $marketDepthPrice->getPrice()->getAmountFloat());
    echo money_format($btcFormat, $marketDepthPrice->getAmount()->getAmountFloat());
    echo "\n";
}

