<?php

include __DIR__ . '/../../vendor/autoload.php';

use Matmar10\CampBX\CampBXAuthClient;

// to use fancy output format in CLI results
setlocale(LC_MONETARY, 'en_US');

// use leading spaces to align nicely
// from: http://php.net/manual/en/function.money-format.php
$usdFormat = '%!= 6#6n';
$btcFormat = '%!= 6#6.8n';

$config = json_decode(file_get_contents(__DIR__.'/../../examples-config.json'), true);
$client = CampBXAuthClient::factory($config);
/**
 * Equivalent to:
 *
 * $client = CampBXAuthClient::factory(array(
 *     'username' => 'YOUR_USERNAME',
 *     'password' => 'YOUR_PASSWORD',
 * ));
 */

$command = $client->getCommand('getOrders');
$result = $client->execute($command);
print_r($result);
