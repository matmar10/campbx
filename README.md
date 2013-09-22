campbx
======

A REST API Client for CampBX Bitcoin exchange using Guzzle library and sophisticated integer math under the hood


Installation
------------

This library uses composer and psr-0 autloading standard.

Add the following to your composer.json file:

```javascript
{
    "require": {
        "matmar10/campbox": "dev-master"
    }
}
```

Reload your dependencies using ```php composer.phar update```

Be sure to include the generated autoloader in your implementation:

```php

include __DIR__.'/vendor/autoload.php';


```

General Usage
-------------

### REST Client ###

Include the Client object to create and execute commands.

Use the factory method to create a new client instance:

```php

use Matmar10\CampBX\CampBXClient;

$client = CampBXClient::factory();

```

API methods requiring authentication must use the auth client
which requires username and password as required arguments:

```php

use Matmar10\CampBX\CampBXAuthClient;

$client = CampBXAuthClient::factory(array(
    'username' => 'your username',
    'password' => 'your password'
));

```

### Commands ###

This library uses Guzzle PHP, which implements the command pattern.

Available commands are described in ```src/Matmar10/CampBX/client-service-description.json```

When a response is returned, root level properties of the response can be
retrieved via the ```->get()``` method:

```php

$command = $client->getCommand('GetTicker');
$response = $client->execute($command);

// returns an instance of Matmar10\Money\Entity\CurrencyPair
$askPrice = $response->get('ask');

```

Available parameters of the response object are described in the ```models``` section
of the ```client-service-description.json``` file. For example:

```javascript

...
{

// ...
    "models": {
        "GetTicker": {
            "type": "object":,
            "properties": {
                "lastTrade": {
                    "location": "json",
                    "sentAs": "Last Trade",
                    "type": "object",
                    "filters": [{
                        "method": "Matmar10\\CampBX\\Filter\\Response::asCurrencyPairFromFloat",
                        "args": [ "@value", "BTC", 8, 8, "USD", 2, 2, "B⃦", "$"]
                    }]
                },
                "bid": {
                    "location": "json",
                    "sentAs": "Best Bid",
                    "type": "object",
                    "filters": [{
                        "method": "Matmar10\\CampBX\\Filter\\Response::asCurrencyPairFromFloat",
                        "args": [ "@value", "BTC", 8, 8, "USD", 2, 2, "B⃦", "$"]
                    }]
                },
                "ask": {
                    "location": "json",
                    "sentAs": "Best Ask",
                    "type": "object",
                    "filters": [{
                        "method": "Matmar10\\CampBX\\Filter\\Response::asCurrencyPairFromFloat",
                        "args": [ "@value", "BTC", 8, 8, "USD", 2, 2, "B⃦", "$"]
                    }]
                }
            }
        }
    }

// ...

```

Market Data
-----------

### Market Depth ###

See ```examples/market-data/market-depth.php``` for a more complete example.

```php

$command = $client->getCommand('GetMarketDepth');
$response = $client->execute($command);

foreach($response->get('ask') as $marketDepthPrice) {
    echo $marketDepthPrice->getPrice()->getamountDisplay() . ": ";
    echo $marketDepthPrice->getAmount()->getamountDisplay() . "\n";
}

```

### Ticker ###

See ```examples/market-data/ticker.php``` for a more complete example.

```php

$command = $client->getCommand('GetTicker');
$response = $client->execute($command);

echo "BUY: " . $response->get('bid')->getMultiplier() . "\n";
echo "SELL: " . $response->get('ask')->getMultiplier() . "\n";

```

Account Data
------------

### Get Account Balances ###

* Requires Authentication *

See also: ```examples/account-data/balance.php```

```php

$client = CampBXAuthClient::factory(array(
    'username' => 'your-campbx-username',
    'password' => 'changeme',
));

$command = $client->getCommand('GetAccountBalances');
$response = $client->execute($command);
echo "Total BTC: " . $response->get('totalBTC')->getAmountFloat() . "\n";
echo "Total USD: " . $response->get('totalUSD')->getAmountFloat() . "\n";

```


