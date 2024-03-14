<?php

namespace DropshippingXmlFreeVendor\WPDesk\License;

use DropshippingXmlFreeVendor\WPDesk\License\ActivationForm\AjaxHandler;
use DropshippingXmlFreeVendor\WPDesk\License\ActivationForm\PluginsPageRenderer;
use DropshippingXmlFreeVendor\WPDesk\License\WpUpgrader\SubscriptionHandler;
use DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use DropshippingXmlFreeVendor\WPDesk_API_Manager_With_Update_Flag;
use DropshippingXmlFreeVendor\WPDesk_Plugin_Info;
/**
 * @depreacted Check LicenseServer namespace
 */
class LicenseManager implements \DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection
{
    use HookableParent;
    /**
     * @var WPDesk_Plugin_Info
     */
    private $plugin_info;
    /**
     * @var PluginsPageRenderer
     */
    private $plugins_page_renderer;
    /**
     * @var AjaxHandler
     */
    private $ajax_handler;
    /**
     * @param WPDesk_Plugin_Info $plugin_info
     */
    public function __construct(\DropshippingXmlFreeVendor\WPDesk_Plugin_Info $plugin_info)
    {
        $this->plugin_info = $plugin_info;
    }
    /**
     * @param bool $hooks_to_updates
     *
     * @return WPDesk_API_Manager_With_Update_Flag
     */
    public function create_api_manager(bool $hook_to_updates = \true) : \DropshippingXmlFreeVendor\WPDesk_API_Manager_With_Update_Flag
    {
        $address_repository = new \DropshippingXmlFreeVendor\WPDesk\License\ServerAddressRepository($this->plugin_info->get_product_id());
        return new \DropshippingXmlFreeVendor\WPDesk_API_Manager_With_Update_Flag($address_repository->get_default_update_url(), $this->plugin_info->get_version(), $this->plugin_info->get_plugin_file_name(), $this->plugin_info->get_product_id(), $this->plugin_info->get_plugin_file_name(), $this->plugin_info->get_plugin_slug(), $hook_to_updates, $this->plugin_info->get_plugin_name());
    }
    /**
     *
     */
    public function init_activation_form()
    {
        $this->plugins_page_renderer = new \DropshippingXmlFreeVendor\WPDesk\License\ActivationForm\PluginsPageRenderer($this->plugin_info);
        $this->add_hookable($this->plugins_page_renderer);
        $this->ajax_handler = new \DropshippingXmlFreeVendor\WPDesk\License\ActivationForm\AjaxHandler($this->plugin_info);
        $this->add_hookable($this->ajax_handler);
    }
    /**
     * .
     */
    public function init_wp_upgrader(bool $activated, $subscriptions_url)
    {
        $this->add_hookable(new \DropshippingXmlFreeVendor\WPDesk\License\WpUpgrader\SubscriptionHandler($this->plugin_info->get_plugin_file_name(), $activated, $subscriptions_url));
    }
    /**
     * .
     */
    public function hooks()
    {
        $this->hooks_on_hookable_objects();
    }
    /**
     * @return PluginsPageRenderer
     */
    public function get_plugins_page_renderer() : \DropshippingXmlFreeVendor\WPDesk\License\ActivationForm\PluginsPageRenderer
    {
        return $this->plugins_page_renderer;
    }
    /**
     * @return AjaxHandler
     */
    public function get_ajax_handler() : \DropshippingXmlFreeVendor\WPDesk\License\ActivationForm\AjaxHandler
    {
        return $this->ajax_handler;
    }
}
