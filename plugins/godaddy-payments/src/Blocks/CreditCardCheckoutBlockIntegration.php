<?php

namespace GoDaddy\WooCommerce\Poynt\Blocks;

use GoDaddy\WooCommerce\Poynt\Gateways\CreditCardGateway;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

/**
 * GoDaddy Payments checkout block integration for the {@see CreditCardGateway}.
 *
 * @since 1.7.0
 *
 * @property Plugin $plugin the plugin instance
 * @property CreditCardGateway $gateway the gateway instance
 */
class CreditCardCheckoutBlockIntegration extends Framework\Payment_Gateway\Blocks\Gateway_Checkout_Block_Integration
{
    /**
     * Constructor.
     *
     * @since 1.7.0
     *
     * @param Plugin $plugin
     * @param CreditCardGateway $gateway
     */
    public function __construct(Framework\SV_WC_Payment_Gateway_Plugin $plugin, Framework\SV_WC_Payment_Gateway $gateway)
    {
        parent::__construct($plugin, $gateway);

        $this->add_main_script_dependency('poynt-collect');
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
     * @param CreditCardGateway $gateway
     * @return array<string, mixed>
     */
    public function add_payment_method_data(array $payment_method_data, Framework\SV_WC_Payment_Gateway $gateway) : array
    {
        $payment_method_data['gateway'] = array_merge(
            $payment_method_data['gateway'] ?: [],
            [
                'appId'        => $gateway->getAppId(),
                'businessId'   => $gateway->getBusinessId(),
                'mountOptions' => $gateway->getMountOptions('block'),
            ]
        );

        return $payment_method_data;
    }
}
