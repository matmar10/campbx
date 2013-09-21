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

Usage
-----

### General ###

Include the Client object to create and execute commands.

Use the factory method to create a new client instance:

```php

use Matmar10\CampBX\CampBXClient;

$client = CampBXClient::factory(array(
    'username' => 'your-username',
    'password' => 'your-password'
));

```

Now your username and password will automatically be
included in each API request requiring authentication.

### Market Data ###

#### Market Depth ####

See ```examples/market-data/market-depth.php``` for a nicely formatted example.

```php

$command = $client->getCommand('GetMarketDepth');
$response = $client->execute($command);

foreach($response->get('ask') as $marketDepthPrice) {
    echo $marketDepthPrice->getPrice()->getamountDisplay() . ": ";
    echo $marketDepthPrice->getAmount()->getamountDisplay() . "\n";
}

```

#### Ticker ####