<?php

/**
 * @since      1.0.0
 * @package    PPCP_Paypal_Checkout_For_Woocommerce
 * @subpackage PPCP_Paypal_Checkout_For_Woocommerce/includes
 * @author     PayPal <mbjwebdevelopment@gmail.com>
 */
class PPCP_Paypal_Checkout_For_Woocommerce_i18n {

    /**
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                'express-checkout', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}
