<?php

/**
 * Assets.
 *
 * @package WPDesk\WooCommerceShipping
 */
namespace DhlVendor\WPDesk\WooCommerceShipping\DhlExpress;

use DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Loads assets.
 *
 */
class Assets implements \DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Scripts version.
     *
     * @var string
     */
    private $scripts_version = '1';
    /**
     * Assets URL.
     *
     * @var string
     */
    private $assets_url = '';
    /**
     * Assets constructor.
     *
     * @param string $assets_url .
     * @param string $scripts_version .
     */
    public function __construct($assets_url, $scripts_version)
    {
        $this->assets_url = $assets_url;
        $this->scripts_version = $scripts_version;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        \add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }
    /**
     * Enqueue admin scripts.
     */
    public function admin_enqueue_scripts()
    {
        global $current_screen;
        if ('woocommerce_page_wc-settings' === $current_screen->id) {
            $handle = 'wpdesk_wc_shipping_dhl_express';
            \wp_register_script($handle, \trailingslashit($this->assets_url) . 'js/settings.js', ['jquery'], $this->scripts_version);
            \wp_enqueue_script($handle);
        }
    }
}
