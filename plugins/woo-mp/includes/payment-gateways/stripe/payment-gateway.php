<?php

namespace Woo_MP\Payment_Gateways\Stripe;

defined( 'ABSPATH' ) || die;

/**
 * Stripe payment gateway.
 */
class Payment_Gateway extends \Woo_MP\Payment_Gateway\Payment_Gateway {

    const ID = 'stripe';

    public function get_title() {
        return 'Stripe';
    }

    public function get_payment_method_title() {
        return get_option( 'woo_mp_stripe_title', 'Credit Card (Stripe)' );
    }

    public function get_settings_section() {
        return new Settings_Section();
    }

    public function get_payment_meta_box_helper() {
        return new Payment_Meta_Box_Helper();
    }

    public function get_payment_processor() {
        return new Payment_Processor();
    }

}
