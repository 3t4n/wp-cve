<?php

namespace DropshippingXmlFreeVendor\WPDesk\License\LicenseServer;

use DropshippingXmlFreeVendor\WPDesk_Plugin_Info;
/**
 * Provides plugin license information and gives a change to modify it.
 */
class PluginLicense
{
    const ACTIVATED_VALUE = 'Activated';
    /**
     * @var WPDesk_Plugin_Info
     */
    private $plugin_info;
    /**
     * @param WPDesk_Plugin_Info $info
     */
    public function __construct(\DropshippingXmlFreeVendor\WPDesk_Plugin_Info $info)
    {
        $this->plugin_info = $info;
    }
    public function is_active() : bool
    {
        return \get_option($this->prepare_option_is_active()) === self::ACTIVATED_VALUE;
    }
    public function set_active()
    {
        \update_option($this->prepare_option_is_active(), self::ACTIVATED_VALUE);
    }
    public function set_inactive()
    {
        \update_option($this->prepare_option_is_active(), 'Inactive');
    }
    private function prepare_option_is_active() : string
    {
        return $this->prepare_option_name('activated');
    }
    private function prepare_option_name(string $field) : string
    {
        return \sprintf('api_%1$s_%2$s', \basename($this->plugin_info->get_plugin_slug()), $field);
    }
}
