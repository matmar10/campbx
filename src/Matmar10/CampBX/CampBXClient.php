<?php

namespace Matmar10\CampBX;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

class CampBXClient extends Client
{

    private $username;
    private $password;

    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array('base_url' => 'http://testnet.campbx.com/api');

        // The following values are required when creating the client
        $required = array(
            'base_url',
            'username',
            'password',
        );

        // merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);
        $client = new self($config->get('base_url'), $config);

        $client->setUsername($config['username']);
        $client->setPassword($config['password']);

        // use description file to configure the client
        $servicesDescriptionFilename = dirname(__FILE__) . '/client-service-description.json';
        $description = ServiceDescription::factory($servicesDescriptionFilename);
        $client->setDescription($description);

        return $client;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }
}