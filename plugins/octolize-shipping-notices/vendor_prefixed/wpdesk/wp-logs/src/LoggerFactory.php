<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Logger;

use OctolizeShippingNoticesVendor\Monolog\Logger;
/*
 * @package WPDesk\Logger
 */
interface LoggerFactory
{
    /**
     * Returns created Logger
     *
     * @param string $name
     *
     * @return Logger
     */
    public function getLogger($name);
}
