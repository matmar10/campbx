<?php

include __DIR__ . '/../../vendor/autoload.php';

use Matmar10\CampBX\CampBXAuthClient;

// to use fancy output format in CLI results
setlocale(LC_MONETARY, 'en_US');

// use leading spaces to align nicely
// from: http://php.net/manual/en/function.money-format.php
$usdFormat = '%!= 6#6n';
$btcFormat = '%!= 6#6.8n';

$client = CampBXAuthClient::factory(array(
    'username' => 'your-campbx-username',
    'password' => 'changeme',
));

$command = $client->getCommand('GetAccountBalances');
$response = $client->execute($command);

echo "\n";
echo "Account balances: \n";
echo "----------------------------\n";
echo "Total BTC: " . money_format($btcFormat, $response->get('totalBTC')->getAmountFloat()) . "\n";
echo "Total USD: " . money_format($usdFormat, $response->get('totalUSD')->getAmountFloat()) . "\n";
echo "\n";
