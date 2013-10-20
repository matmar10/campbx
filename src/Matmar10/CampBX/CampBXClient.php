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
        $default = array('base_url' => 'http://campbx.com/api');

        // The following values are required when creating the client
        $required = array(
            'base_url',
        );

        // merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);
        $client = new self($config->get('base_url'), $config);

        // use description file to configure the client
        $servicesDescriptionFilename = dirname(__FILE__) . '/service-description.json';
        $description = ServiceDescription::factory($servicesDescriptionFilename);
        $client->setDescription($description);

        return $client;
    }
}