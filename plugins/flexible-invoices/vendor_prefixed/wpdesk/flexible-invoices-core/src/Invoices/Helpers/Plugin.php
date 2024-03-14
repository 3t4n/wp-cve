<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
/**
 * Plugin helpers functions.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Plugin
{
    /**
     * @param string $plugin
     *
     * @return bool
     */
    public static function is_active(string $plugin) : bool
    {
        if (self::is_function_exists('is_plugin_active_for_network') && \is_plugin_active_for_network($plugin)) {
            return \true;
        }
        return \in_array($plugin, (array) \get_option('active_plugins', []), \true);
    }
    /**
     * @param string $name
     *
     * @return bool
     */
    public static function is_function_exists(string $name) : bool
    {
        return \function_exists($name);
    }
    /**
     * @return string
     */
    public static function get_activation_date() : string
    {
        $plugin = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::$plugin_filename;
        $name = 'plugin_activation_' . $plugin;
        $value = \get_option($name, '');
        if (\is_string($value)) {
            return $value;
        }
        return '';
    }
    /**
     * @param string $date
     *
     * @return bool
     */
    public static function is_activation_date_is_greater_than(string $date) : bool
    {
        return \strtotime(self::get_activation_date()) > \strtotime($date . ' 00:00:00');
    }
    /**
     * @param string $date
     *
     * @return bool
     */
    public static function is_activation_date_is_less(string $date) : bool
    {
        return \strtotime(self::get_activation_date()) < \strtotime($date . ' 00:00:00');
    }
    /**
     * @return string
     */
    public static function upgrade_to_pro_url() : string
    {
        return \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-plugins-upgrade-link' : 'https://www.flexibleinvoices.com/products/flexible-invoices-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-invoices-plugins-upgrade-link';
    }
    /**
     * @return bool
     */
    public static function is_template_addon_is_disabled() : bool
    {
        return !\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_active('flexible-invoices-templates/flexible-invoices-templates.php');
    }
    /**
     * Is flexible quantity pro plugin is enabled.
     *
     * @return bool
     */
    public static function is_fq_pro_addon_enabled() : bool
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_active('flexible-quantity/flexible-quantity.php');
    }
    /**
     * Is flexible quantity free plugin is enabled.
     *
     * @return bool
     */
    public static function is_fq_free_addon_enabled() : bool
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_active('flexible-quantity-measurement-price-calculator-for-woocommerce/flexible-quantity-measurement-price-calculator-for-woocommerce.php');
    }
}
