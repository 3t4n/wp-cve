<?php

/**
 * Assets.
 *
 * @package WPDesk\WooCommerceShipping
 */
namespace FedExVendor\WPDesk\WooCommerceShipping;

use FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FedExVendor\WPDesk\ShowDecision\ShouldShowStrategy;
/**
 * Loads assets.
 *
 */
class Assets implements \FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const CUSTOM_SERVICES_CHECKBOX_CLASS = 'wpdesk_wc_shipping_custom_service_checkbox';
    /**
     * Scripts version.
     *
     * @var string
     */
    private $scripts_version = '14';
    /**
     * Assets URL.
     *
     * @var string
     */
    private $assets_url = '';
    /**
     * Assets URL.
     *
     * @var string
     */
    private $assets_suffix = '';
    /**
     * @var ShouldShowStrategy|null
     */
    private $should_show_strategy;
    /**
     * Assets constructor.
     *
     * @param string $assets_url .
     * @param string $assets_suffix .
     */
    public function __construct(string $assets_url, string $assets_suffix, ?\FedExVendor\WPDesk\ShowDecision\ShouldShowStrategy $should_show_strategy = null)
    {
        $this->assets_url = $assets_url;
        $this->assets_suffix = $assets_suffix;
        $this->should_show_strategy = $should_show_strategy;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        \add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        \add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    /**
     * Enqueue admin scripts.
     */
    public function admin_enqueue_scripts()
    {
        global $current_screen;
        if ('woocommerce_page_wc-settings' === $current_screen->id) {
            $handle = 'wpdesk_wc_shipping_' . $this->assets_suffix;
            \wp_register_style($handle, \trailingslashit($this->assets_url) . 'dist/app.css', array(), $this->scripts_version);
            \wp_enqueue_style($handle);
        }
    }
    /**
     * Enqueue scripts.
     */
    public function enqueue_scripts()
    {
        if ($this->get_show_strategy()->shouldDisplay()) {
            $suffix = \defined('SCRIPT_DEBUG') && \SCRIPT_DEBUG ? '' : '.min';
            $handle = 'wpdesk_wc_shipping_notices_' . $this->assets_suffix;
            \wp_register_script($handle, \trailingslashit($this->assets_url) . 'js/notices' . $suffix . '.js', [], $this->scripts_version, \true);
            \wp_enqueue_script($handle);
            \wp_register_style($handle, \trailingslashit($this->assets_url) . 'css/notices' . $suffix . '.css', [], $this->scripts_version);
            \wp_enqueue_style($handle);
        }
    }
    private function get_show_strategy()
    {
        if ($this->should_show_strategy === null) {
            $this->should_show_strategy = new \FedExVendor\WPDesk\WooCommerceShipping\AssetsShowStrategy();
        }
        return $this->should_show_strategy;
    }
}
