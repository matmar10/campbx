<?php

namespace Matmar10\CampBX;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

class CampBXClient extends Client
{
    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array('baseUrl' => 'http://testnet.campbx.com/api');

        // The following values are required when creating the client
        $required = array(
            'baseUrl',
            'username',
            'password',
        );

        // merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);
        $client = new self($config->get('baseUrl'), $config);

        // use description file to configure the client
        $servicesDescriptionFilename = dirname(__FILE__) . '/Resources/client-service-description.json';
        $description = ServiceDescription::factory($servicesDescriptionFilename);
        $client->setDescription($description);

        return $client;
    }
}