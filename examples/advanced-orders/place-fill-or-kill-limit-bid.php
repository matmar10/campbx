<?php

include __DIR__ . '/../../vendor/autoload.php';

use Matmar10\CampBX\CampBXAuthClient;
use Matmar10\CampBX\Parameter;

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

$command = $client->getCommand('placeAdvancedTradeOrder');
$command->set('type', Parameter::TRADE_ORDER_TYPE_BID);
$command->set('amount', 0.01);
$command->set('price', 1);
$command->set('fillType', Parameter::TRADE_ORDER_FILL_TYPE_FILL_OR_KILL);
$result = $client->execute($command);

$orderId = $result->get('id');
if(!is_null($orderId)) {
    echo sprintf("Successfully placed trade order ID '%s'\n", $orderId);
    // terminate normally
    exit(0);
}

$info = $result->get('info');
if(!is_null($info)) {
    echo $info . "\n";
    // terminate normally
    exit(0);
}

// error executing trade order
exit(1);

