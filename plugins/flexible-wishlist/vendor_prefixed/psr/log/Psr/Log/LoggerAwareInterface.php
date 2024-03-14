<?php

namespace FlexibleWishlistVendor\Psr\Log;

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
    public function setLogger(\FlexibleWishlistVendor\Psr\Log\LoggerInterface $logger);
}
