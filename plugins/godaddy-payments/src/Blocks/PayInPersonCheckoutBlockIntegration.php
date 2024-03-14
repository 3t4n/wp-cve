<?php

namespace GoDaddy\WooCommerce\Poynt\Blocks;

use Exception;
use GoDaddy\WooCommerce\Poynt\Gateways\PayInPersonGateway;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

/**
 * GoDaddy Payments checkout block integration for the {@see PayInPersonGateway}.
 *
 * @since 1.7.0
 *
 * @property Plugin $plugin the plugin instance
 * @property PayInPersonGateway $gateway the gateway instance
 */
class PayInPersonCheckoutBlockIntegration extends Framework\Payment_Gateway\Blocks\Gateway_Checkout_Block_Integration
{
    /**
     * Determines if the gateway is active and configured to enable the payment method in the checkout block.
     *
     * @since 1.7.0
     *
     * @return bool
     * @throws Exception
     */
    public function is_active() : bool
    {
        /*
         * We cannot call the parent method because there is no context about order eligibility - the front end will take care of that.
         *
         * @see Gateway_Checkout_Block_Integration::is_active()
         * @see PayInPersonGateway::is_available()
         */
        return $this->gateway->is_enabled() && $this->gateway->is_configured();
    }

    /**
     * Adds payment method data.
     *
     * @internal
     *
     * @see Gateway_Checkout_Block_Integration::get_payment_method_data()
     *
     * @since 1.7.0
     *
     * @param array<string, mixed> $payment_method_data
     * @param PayInPersonGateway $gateway
     * @return array<string, mixed>
     * @throws Exception
     */
    public function add_payment_method_data(array $payment_method_data, Framework\SV_WC_Payment_Gateway $gateway) : array
    {
        $payment_method_data['gateway'] = array_merge(
            $payment_method_data['gateway'] ?: [],
            [
                'enableForShippingMethods'    => ArrayHelper::wrap($gateway->get_option('enable_for_methods', [])),
                'smartTerminalProductPageUrl' => $gateway->getSmartTerminalProductPageUrl(),
            ]
        );

        return $payment_method_data;
    }
}
