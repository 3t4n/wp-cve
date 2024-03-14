<?php

namespace Baqend\SDK\Exception;

/**
 * Class NotConnectedException created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Exception
 */
class NotConnectedException extends \RuntimeException
{

    /**
     * NotConnectedException constructor.
     * @param \Exception|null $previous
     */
    public function __construct(\Exception $previous = null) {
        $message = 'Baqend SDK is not connected to an app';
        parent::__construct($message, 0, $previous);
    }
}
