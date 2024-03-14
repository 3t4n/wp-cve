<?php

namespace Woo_MP\Payment_Gateways\Eway;

defined( 'ABSPATH' ) || die;

/**
 * Eway payment meta box helper.
 *
 * The core payment meta box controller uses this class to add
 * all the gateway-specific parts of the frontend.
 */
class Payment_Meta_Box_Helper implements \Woo_MP\Payment_Gateway\Payment_Meta_Box_Helper {

    public function get_currency( $order_currency ) {
        return get_woocommerce_currency();
    }

    public function validation() {
        return [];
    }

    public function enqueue_assets() {
        wp_enqueue_script( 'woo-mp-eway-script', WOO_MP_URL . '/includes/payment-gateways/eway/assets/script.js', [], WOO_MP_VERSION, true );
        wp_enqueue_script( 'woo-mp-payment-processor-script', 'https://api.ewaypayments.com/JSONP/v3/js', [], null, true );
    }

    public function client_data() {
        return [
            'responseCodeMessages' => Transaction_Processor::get_response_code_messages(),
        ];
    }

    public function get_template_directories() {
        return [ WOO_MP_PATH . '/includes/payment-gateways/eway/templates' ];
    }

}
