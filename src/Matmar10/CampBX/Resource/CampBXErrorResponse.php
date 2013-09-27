<?php

namespace Matmar10\CampBX\Exception;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\ErrorResponse\ErrorResponseExceptionInterface;
use Guzzle\Service\Command\CommandInterface;
use Matmar10\CampBX\Exception\ClientErrorResponseException;

class CampBXErrorResponse implements ErrorResponseExceptionInterface
{
    public static function fromCommand(CommandInterface $command, Response $response)
    {
        return new ClientErrorResponseException();
    }
}
