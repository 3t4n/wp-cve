<?php

namespace ShopMagicVendor\WPDesk\Persistence;

use ShopMagicVendor\Psr\Container\ContainerInterface;
/**
 * Container that allows to get all data stored by container.
 *
 * @package WPDesk\Persistence
 */
interface AllDataAccessContainer extends ContainerInterface
{
    /**
     * Get all values.
     *
     * @return array
     */
    public function get_all() : array;
}
