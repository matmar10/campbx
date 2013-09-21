<?php

namespace Matmar10\CampBX\Command;

use Guzzle\Service\Command\OperationCommand;

class AuthenticatedCommand extends OperationCommand
{
    // add the username and password to the request
    public function build()
    {
        parent::build();
        $client = $this->getClient();
        $username = $client->getUsername();
        $password = $client->getPassword();

        $this->request = $this->getRequestSerializer()->prepare($this);
        $this->request->addPostFields(array(
            'user' => $username,
            'pass' => $password,
        ));
    }
}
