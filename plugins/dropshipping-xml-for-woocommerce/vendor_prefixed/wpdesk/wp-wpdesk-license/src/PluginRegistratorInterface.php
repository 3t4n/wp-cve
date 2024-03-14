<?php

namespace DropshippingXmlFreeVendor\WPDesk\License;

/**
 * @package    WPDesk\License
 */
interface PluginRegistratorInterface
{
    public function is_active() : bool;
    /**
     * Initializes license manager.
     */
    public function initialize_license_manager();
}
