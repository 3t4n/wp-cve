<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Gateways;

use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Payment form handler.
 *
 * @since 1.0.0
 *
 * @method CreditCardGateway get_gateway()
 */
class PaymentForm extends Framework\SV_WC_Payment_Gateway_Payment_Form
{
    /**
     * Gets the JavaScript class name.
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_js_handler_class_name() : string
    {
        return 'WC_Poynt_Payment_Form_Handler';
    }

    /**
     * Gets the JavaScript handler arguments.
     *
     * @since 1.0.0
     *
     * @return array
     */
    protected function get_js_handler_args() : array
    {
        $args = parent::get_js_handler_args();

        $customerContactInformation = $this->get_gateway()->getCustomerContactInformation();

        $args['appId'] = $this->get_gateway()->getAppId();
        $args['businessId'] = $this->get_gateway()->getBusinessId();
        $args['customerName'] = $this->get_gateway()->getCustomerName();
        $args['customerAddress'] = $this->get_gateway()->getCustomerAddress();
        $args['customerEmailAddress'] = $customerContactInformation['emailAddress'];
        $args['customerPhone'] = $customerContactInformation['phone'];
        $args['shipping'] = $this->get_gateway()->getShippingInformation();
        $args['isLoggingEnabled'] = ! $this->get_gateway()->debug_off();
        $args['mountOptions'] = $this->get_gateway()->getMountOptions();

        return $args;
    }

    /**
     * Renders the payment fields HTML.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function render_payment_fields()
    {
        $gateway = $this->get_gateway(); ?>
        <input type="hidden" name="wc_<?php echo $gateway->get_id(); ?>_nonce" id="wc-<?php echo $gateway->get_id_dasherized(); ?>-nonce">
        <div id="wc-poynt-credit-card-hosted-form"></div>
        <?php
    }
}
