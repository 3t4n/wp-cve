<?php

namespace Woo_MP\Payment_Gateways\Authorize_Net;

defined( 'ABSPATH' ) || die;

/**
 * Authorize.net payment gateway.
 */
class Payment_Gateway extends \Woo_MP\Payment_Gateway\Payment_Gateway {

    const ID = 'authorize_net';

    public function get_title() {
        return 'Authorize.net';
    }

    public function get_payment_method_title() {
        return get_option( 'woo_mp_authorize_net_title', 'Credit Card (Authorize.net)' );
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
