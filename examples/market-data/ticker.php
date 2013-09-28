<?php

include __DIR__ . '/../../vendor/autoload.php';

use Matmar10\CampBX\CampBXClient;
use Matmar10\Money\Entity\Money;

// to use fancy output format in CLI results
setlocale(LC_MONETARY, 'en_US');

// from: http://php.net/manual/en/function.money-format.php
$usdFormat = '%n';

$client = CampBXClient::factory();

$command = $client->getCommand('getTicker');
$response = $client->execute($command);

echo "\n";

echo "Market summary:\n";
echo "------------------\n";
echo " BUY at: " . money_format($usdFormat, $response->get('bid')->getMultiplier()) . "\n";
echo "SELL at: " . money_format($usdFormat, $response->get('ask')->getMultiplier()) . "\n";

echo "\n";

if(false === array_key_exists(1, $argv)) {
    exit(0);
}

echo sprintf("Showing calculations for input amount '%s'\n", $argv[1]);

echo "\n";

echo "Estimated proceeds:\n";
echo "--------------------\n";


// see how much we'd get for selling amount specified as BTC
$sellBtc = new Money($response->get('bid')->getFromCurrency());
$sellBtc->setAmountFloat($argv[1]);
// convert to USD based on the current going SELL rate
$anticipatedProceeds = $response->get('bid')->convert($sellBtc);
echo $sellBtc->getAmountFloat() . ' BTC ~ ' . money_format($usdFormat, $anticipatedProceeds->getAmountFloat()) . "\n";

echo "\n";

echo "Estimated price:\n";
echo "--------------------\n";
// see how much we'd need to pay to buy amount of BTC specified  for selling amount specified as BTC
$buyBtc = new Money($response->get('ask')->getFromCurrency());
$buyBtc->setAmountFloat($argv[1]);
// convert to USD based on the current going SELL rate
$anticipatedPrice = $response->get('ask')->convert($buyBtc);
echo $buyBtc->getAmountFloat() . ' BTC ~ ' . money_format($usdFormat, $anticipatedPrice->getAmountFloat()) . "\n";

echo "\n";
