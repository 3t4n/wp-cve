<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Tax;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Adds vat types from WooCommerce.
 *
 * This class takes over the logic for the tax rates.
 * Tax rates are only taken from WooCommerce and rate settings disappear from the plugin settings.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class Taxes implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_filter('inspire_invoices_vat_types', [$this, 'add_woocommerce_currencies_vat_types_filter']);
    }
    /**
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function add_woocommerce_currencies_vat_types_filter() : array
    {
        global $wpdb;
        $tax_classes = \WC_Tax::get_tax_classes();
        foreach ($tax_classes as $key => $tax_class) {
            $tax_classes[$key] = \sanitize_title($tax_class);
        }
        //phpcs:disable
        $found_rates = $wpdb->get_results("\n                SELECT distinct tax_rates.*\n                FROM {$wpdb->prefix}woocommerce_tax_rates as tax_rates\n                ORDER BY CAST(tax_rate as DECIMAL(10,5)) desc, tax_rate_priority, tax_rate_order\n                ");
        //phpcs:enable
        $matched_tax_rates = [];
        foreach ($found_rates as $found_rate) {
            if (\trim($found_rate->tax_rate_class) === '' || \in_array($found_rate->tax_rate_class, $tax_classes, \true)) {
                $matched_tax_rates[$found_rate->tax_rate_id] = ['rate' => $found_rate->tax_rate, 'label' => $found_rate->tax_rate_name, 'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no', 'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'];
            }
        }
        $rates = $matched_tax_rates;
        $types = [];
        if (!empty($rates)) {
            foreach ($rates as $index => $rate) {
                $name = $rate['label'];
                $types[$index] = ['index' => $index, 'rate' => (float) $rate['rate'], 'name' => $name];
            }
        }
        return $types;
    }
}
