<?php

namespace Baqend\SDK\Exception;

/**
 * Class NeedsAuthorizationException created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Exception
 */
class NeedsAuthorizationException extends \Exception
{

    /**
     * NeedsAuthorizationException constructor.
     * @param string $method
     * @param string $requestType
     */
    public function __construct($method, $requestType) {
        $message = "Sending a $method $requestType request requires authorization";
        parent::__construct($message, 0);
    }
}
