<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Helpers;

use GoDaddy\WooCommerce\Poynt\Plugin;

defined('ABSPATH') or exit;

/**
 * Helpers for handling credentials.
 *
 * @since 1.3.1
 */
class WCHelper
{
    /**
     * Checks to see whether the appropriate payment settings are being accessed by the current request.
     *
     * Accepts gateway as a parameter to validate
     *
     * @param string $gatewayId
     *
     * @return bool
     */
    public static function isAccessingSettings(string $gatewayId) : bool
    {
        if (is_admin()) {
            if (! isset($_REQUEST['page']) || 'wc-settings' !== $_REQUEST['page']) {
                return false;
            }

            if (! isset($_REQUEST['tab']) || 'checkout' !== $_REQUEST['tab']) {
                return false;
            }

            if (! isset($_REQUEST['section']) || $gatewayId !== $_REQUEST['section']) {
                return false;
            }

            return true;
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            global $wp;

            if (isset($wp->query_vars['rest_route']) && false !== strpos($wp->query_vars['rest_route'], '/payment_gateways')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks to see whether the user is access payment settings page or not.
     *
     * @return bool
     */
    public static function isAccessingPaymentSettings() : bool
    {
        if (is_admin()) {
            if (! isset($_REQUEST['page']) || 'wc-settings' !== $_REQUEST['page']) {
                return false;
            }

            if (! isset($_REQUEST['tab']) || 'checkout' !== $_REQUEST['tab']) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Checks to see whether the payment gateway is active.
     *
     * @param string $gatewayId
     * @return bool
     */
    public static function isPaymentGatewayActive(string $gatewayId) : bool
    {
        if (! poynt_for_woocommerce()->has_gateway($gatewayId)) {
            return false;
        }

        $gateway = poynt_for_woocommerce()->get_gateway($gatewayId);

        return $gateway && $gateway->is_enabled() && $gateway->is_configured();
    }

    /**
     * Confirms if the Pay In Person gateway should be loaded.
     *
     * Returns true if the base country & store currency is supported,
     * and the Credit Card gateway is configured properly.
     *
     * @return bool
     */
    public static function shouldLoadPayInPersonGateway() : bool
    {
        $baseCountry = function_exists('wc_get_base_location') ? wc_get_base_location()['country'] : null;

        return
            Plugin::isCountrySupported($baseCountry)
            && Plugin::isCurrencySupported(get_woocommerce_currency())
            && PoyntHelper::isGDPConnected();
    }

    /**
     * Check if the order has a certain shipping method. Accepts a string or
     * array of strings and returns true if the order uses at least *one* of
     * the provided $methods.
     *
     * @param \WC_Order $order
     * @param string|array $methods
     *
     * @return bool
     */
    public static function orderHasShippingMethods(\WC_Order $order, $methods) : bool
    {
        foreach (ArrayHelper::wrap($order->get_shipping_methods()) as $shippingMethod) {
            if (ArrayHelper::contains(ArrayHelper::wrap($methods), $shippingMethod->get_method_id())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines if a WC Order is already captured.
     *
     * @param \WC_Order $order
     *
     * @return bool
     * @throws Exception
     */
    public static function hasCapturedOrder(\WC_Order $order) : bool
    {
        return 'yes' === $order->get_meta('_wc_poynt_credit_card_charge_captured')
        || (! static::orderHasPoyntProvider($order) && ! empty($order->get_date_paid()) && (! empty($order->get_transaction_id())));
    }

    /**
     * Confirms if the order has poynt as provider.
     *
     * @param \WC_Order
     *
     * @return bool|GatewayAPI
     */
    public static function orderHasPoyntProvider(\WC_Order $wcOrder) : bool
    {
        return
            // to handle the poynt credit card gateway
            StringHelper::contains($wcOrder->get_payment_method(), 'poynt')
            || 'poynt' === $wcOrder->get_meta('_wc_poynt_provider_name');
    }

    /**
     * Confirms the order ready for capture.
     *
     * @param \WC_Order
     *
     * @return bool
     */
    public static function hasOpenAuthorization(\WC_Order $wcOrder) : bool
    {
        if (! $wcOrder->get_meta('_wc_poynt_credit_card_trans_id')) {
            return false;
        }

        if (in_array($wcOrder->get_status(), ['cancelled', 'refunded', 'failed'])) {
            return false;
        }

        return true;
    }

    /**
     * Core Payment Gateways.
     *
     * @return array
     */
    public static function corePaymentMethods() : array
    {
        return [
            Plugin::CREDIT_CARD_GATEWAY_ID,
            Plugin::PAYINPERSON_GATEWAY_ID,
        ];
    }
}
