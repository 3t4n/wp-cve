<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Driver;

/**
 * Forces advanced logic to drivers.
 *
 * @author Jordan Stout <j@jrdn.org>
 */
interface AdvancedDriverInterface extends \WPPayVendor\Metadata\Driver\DriverInterface
{
    /**
     * Gets all the metadata class names known to this driver.
     *
     * @return string[]
     */
    public function getAllClassNames() : array;
}
