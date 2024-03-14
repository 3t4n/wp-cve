<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

/**
 * WooCommerce Settings.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
class SettingsWoocommerceStrategy extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\AbstractSettingsStrategy
{
    /**
     * Set payment methods.
     */
    public function get_payment_methods() : array
    {
        $payment_methods = parent::get_payment_methods();
        $gateways = \WC()->payment_gateways->payment_gateways();
        $woo_payment_methods = [];
        foreach ($gateways as $gateway) {
            $woo_payment_methods['woocommerce'][$gateway->id] = $gateway->title;
        }
        return \array_merge($payment_methods, $woo_payment_methods);
    }
    /**
     * @return array
     */
    public function get_order_statuses() : array
    {
        $statuses_options = [];
        $woocommerce_statuses = \wc_get_order_statuses();
        foreach ($woocommerce_statuses as $status => $status_display) {
            $status = \str_replace('wc-', '', $status);
            if ($status === 'pending') {
                continue;
            }
            $statuses_options[$status] = $status_display;
        }
        return $statuses_options;
    }
}
