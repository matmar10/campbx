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
$command->set('type', Parameter::TRADE_ORDER_TYPE_ASK);
$command->set('amount', 0.01);
$command->set('price', Parameter::PRICE_MARKET_ORDER);
$command->set('fillType', Parameter::TRADE_ORDER_FILL_TYPE_FILL_OR_KILL);
$result = $client->execute($command);

$orderId = $result->get('id');
if(!is_null($orderId)) {
    if(0 === $orderId) {
        echo "Trade order was executed immediately.";
    } else {
        echo sprintf("Successfully placed trade order ID '%s'\n", $orderId);
    }
    // terminate normally
    exit(0);
}

$info = $result->get('info');
if(!is_null($info)) {
    echo $info . "\n";
    // terminate normally
    exit(0);
}


echo "Could not place trade order: an error occurred:\n";
echo $result->get('Error');

// error executing trade order
exit(1);

