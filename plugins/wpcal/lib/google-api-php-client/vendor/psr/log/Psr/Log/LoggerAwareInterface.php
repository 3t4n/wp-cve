<?php

namespace WPCal\GoogleAPI\Psr\Log;

/**
 * Describes a logger-aware instance.
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(\WPCal\GoogleAPI\Psr\Log\LoggerInterface $logger);
}
