<?php

namespace Paygreen\Module;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WC_Paygreen_Payment_Blocks_Support class.
 *
 * @extends AbstractPaymentMethodType
 */
class WC_Paygreen_Payment_Blocks_Support extends AbstractPaymentMethodType
{
    /**
     * Payment method name defined by payment methods extending this class.
     *
     * @var string
     */
    protected $name = 'paygreen_payment';

    /**
     * Initializes the payment method type.
     */
    public function initialize()
    {
        $this->settings = get_option('woocommerce_paygreen_payment_settings');
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active()
    {
        return !empty($this->settings['enabled']) && 'yes' === $this->settings['enabled'];
    }

    public function get_payment_method_script_handles()
    {
        wp_register_script(
            'paygreen_payment_checkout_block',
            plugins_url() . '/' . WC_Paygreen_Payment_Gateway::ID . '/build/js/checkoutBlock.js',
            [],
            null,
            true
        );

        return ['paygreen_payment_checkout_block'];
    }

    public function get_payment_method_data()
    {
        return [
            'name' => $this->name,
            'title' => $this->get_setting('title'),
            'description' => $this->get_setting('description'),
            'supports' => $this->get_supported_features()
        ];
    }

    /**
     * Returns an array of supported features.
     *
     * @return string[]
     */
    public function get_supported_features() {
        $gateway = new WC_Paygreen_Payment_Gateway();
        return array_filter($gateway->supports, [$gateway, 'supports']);
    }
}