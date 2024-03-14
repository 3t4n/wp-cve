<?php

namespace DropshippingXmlFreeVendor\WPDesk\License\LicenseServer;

use DropshippingXmlFreeVendor\WPDesk\License\PluginRegistratorInterface;
use DropshippingXmlFreeVendor\WPDesk_Plugin_Info;
/**
 * New server license manager.
 * Fields in this class can be replaced during build process and/or package preparation on the license server.
 *
 * @package WPDesk\License\LicenseServer
 */
class PluginRegistrator implements \DropshippingXmlFreeVendor\WPDesk\License\PluginRegistratorInterface
{
    /** @var WPDesk_Plugin_Info */
    private $plugin_info;
    /**
     * Field CAN be replaced during build process.
     *
     * @var string License server URL.
     */
    private $server = 'https://license.wpdesk.dev';
    /**
     * Token WILL BE REPLACED during package preparation on the license server.
     *
     * @var string User token.
     */
    private static $token = '00000000-0000-0000-0000-000000000000';
    /**
     * This field WILL BE REPLACED during package preparation on the license server.
     * Thanks to this field we know whether a plugin has been downloaded from license server.
     *
     * @var bool Should use license server.
     */
    private static $should_use_license_server = \false;
    public static function get_token() : string
    {
        return \apply_filters('wpdesk/license/token', self::$token);
    }
    public static function should_use_license_server() : bool
    {
        return \apply_filters('wpdesk/license/use_license', self::$should_use_license_server);
    }
    public function __construct(\DropshippingXmlFreeVendor\WPDesk_Plugin_Info $plugin_info)
    {
        $this->plugin_info = $plugin_info;
    }
    public function is_active() : bool
    {
        return (new \DropshippingXmlFreeVendor\WPDesk\License\LicenseServer\PluginLicense($this->plugin_info))->is_active();
    }
    public function initialize_license_manager()
    {
        (new \DropshippingXmlFreeVendor\WPDesk\License\LicenseServer\PluginUpgrade($this->plugin_info, $this->server, self::get_token()))->hooks();
        (new \DropshippingXmlFreeVendor\WPDesk\License\LicenseServer\PluginExternalBlocking($this->plugin_info, $this->server, self::get_token()))->hooks();
        (new \DropshippingXmlFreeVendor\WPDesk\License\LicenseServer\PluginViewVersionInfo($this->plugin_info, $this->server))->hooks();
    }
}
