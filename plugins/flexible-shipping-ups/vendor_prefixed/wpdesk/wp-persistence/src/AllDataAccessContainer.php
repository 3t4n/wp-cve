<?php

namespace UpsFreeVendor\WPDesk\Persistence;

use UpsFreeVendor\Psr\Container\ContainerInterface;
/**
 * Container that allows to get all data stored by container.
 *
 * @package WPDesk\Persistence
 */
interface AllDataAccessContainer extends \UpsFreeVendor\Psr\Container\ContainerInterface
{
    /**
     * Get all values.
     *
     * @return array
     */
    public function get_all() : array;
}
