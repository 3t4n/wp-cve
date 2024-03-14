<?php

namespace Woo_MP\Payment_Gateways\Stripe;

defined( 'ABSPATH' ) || die;

/**
 * Stripe payment meta box helper.
 *
 * The core payment meta box controller uses this class to add
 * all the gateway-specific parts of the frontend.
 */
class Payment_Meta_Box_Helper implements \Woo_MP\Payment_Gateway\Payment_Meta_Box_Helper {

    public function get_currency( $order_currency ) {
        return $order_currency;
    }

    public function validation() {
        $validation = [];

        if ( ! is_ssl() ) {
            $validation[] = [
                'message' => 'Stripe requires SSL. An SSL certificate helps keep your customer\'s payment information secure. Without SSL, only test API keys will work. Click <a href="https://make.wordpress.org/support/user-manual/web-publishing/https-for-wordpress/" target="_blank">here</a> for more information. If you need help activating SSL, please contact your website administrator, web developer, or hosting provider.',
                'type'    => 'warning',
                'valid'   => true,
            ];
        }

        return $validation;
    }

    public function enqueue_assets() {
        wp_enqueue_script( 'woo-mp-stripe-script', WOO_MP_URL . '/includes/payment-gateways/stripe/assets/script.js', [], WOO_MP_VERSION, true );
        wp_enqueue_script( 'woo-mp-payment-processor-script', 'https://js.stripe.com/v2/', [], null, true );
    }

    public function client_data() {
        return [
            'publishableKey' => get_option( 'woo_mp_stripe_publishable_key' ),
        ];
    }

    public function get_template_directories() {
        return [ WOO_MP_PATH . '/includes/payment-gateways/stripe/templates' ];
    }

}
