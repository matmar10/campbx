<?php

include __DIR__.'/../vendor/autoload.php';

use Guzzle\Service\Builder\ServiceBuilder;
use Guzzle\Tests\GuzzleTestCase;

$builder = ServiceBuilder::factory(array(
    'test.campbx' => array(
        'class' => '\\Matmar10\\CampBX\\CampBXClient',
        'params' => array(),
    ),
    'test.campbx_auth' => array(
        'class' => '\\Matmar10\\CampBX\\CampBXAuthClient',
        'params' => array(
            'username' => 'testuser',
            'password' => 'testpassword',
        ),
    ),
));

Guzzle\Tests\GuzzleTestCase::setServiceBuilder($builder);
