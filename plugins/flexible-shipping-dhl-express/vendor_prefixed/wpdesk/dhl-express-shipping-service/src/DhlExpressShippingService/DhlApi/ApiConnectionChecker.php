<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService\DhlApi;

/**
 * Can check connection.
 */
interface ApiConnectionChecker
{
    /**
     * Pings API.
     * Throws exception on failure.
     *
     * @return void
     * @throws \Exception .
     */
    public function check_connection();
}
