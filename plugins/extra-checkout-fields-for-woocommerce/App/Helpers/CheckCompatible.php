<?php

namespace ECFFW\App\Helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class CheckCompatible
{
    /**
     * Check Compatible construct.
     */
    public function __construct()
    {
        if (!$this->isEnvironmentCompatible()) {
            /* translators: %s is replaced with required version */
            wp_die(sprintf(__('This plugin can not be activated because it requires minimum PHP version of %s', 'extra-checkout-fields-for-woocommerce'), ECFFW_REQUIRED_PHP_VERSION));
        }
        if (!$this->isWooCommerceCompatible()) {
            /* translators: %1$s is replaced with plugin name and %2$s is replaced with required woocommerce version */
            wp_die(sprintf(__('%1$s requires WooCommerce v%2$s or above in order to work', 'extra-checkout-fields-for-woocommerce'), ECFFW_PLUGIN_NAME, ECFFW_REQUIRED_WC_VERSION));
        }
    }

    /**
     * Determines if the server environment is compatible with this plugin.
     * 
     * @return bool
     */
    public static function isEnvironmentCompatible()
    {
        return version_compare(PHP_VERSION, ECFFW_REQUIRED_PHP_VERSION, '>=');
    }

    /**
     * Determines if the woocommerce version is compatible with this plugin.
     * 
     * @return bool
     */
    public static function isWooCommerceCompatible()
    {
        if (!Woocommerce::isActive()) {
            return false;
        }
        if (defined('WC_VERSION')) {
            return version_compare(WC_VERSION, ECFFW_REQUIRED_WC_VERSION, '>=');
        }
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_folder = get_plugins('/woocommerce');
        $plugin_file = 'woocommerce.php';
        $wc_installed_version = null;
        if (isset($plugin_folder[$plugin_file]['Version'])) {
            $wc_installed_version = $plugin_folder[$plugin_file]['Version'];
        }
        return version_compare($wc_installed_version, ECFFW_REQUIRED_WC_VERSION, '>=');
    }
}
