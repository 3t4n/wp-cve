<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Load custom scripts and styles for WooCommerce.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class CheckoutAssets implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const SCRIPTS_VERSION = '3';
    /**
     * @var string
     */
    private $assets_url;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var string
     */
    private $scripts_version;
    /**
     * @param Settings $settings
     * @param string   $assets_url
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, string $assets_url)
    {
        $this->settings = $settings;
        $this->assets_url = $assets_url;
        $this->scripts_version = $this->get_scripts_version();
    }
    /**
     * @return string
     */
    private function get_scripts_version() : string
    {
        if (\defined('FLEXIBLE_INVOICES_DEBUG')) {
            return (string) \time();
        }
        return self::SCRIPTS_VERSION;
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    /**
     * Admin enqueue scripts.
     *
     * @internal You should not use this directly from another application
     */
    public function enqueue_scripts()
    {
        if (\is_checkout() && $this->settings->get('woocommerce_add_invoice_ask_field') === 'yes') {
            \wp_enqueue_style('fiw-checkout', $this->assets_url . 'css/checkout.css', '', $this->scripts_version);
            \wp_enqueue_script('fiw-checkout', $this->assets_url . 'js/checkout.js', ['jquery'], $this->scripts_version, \true);
        }
    }
}
