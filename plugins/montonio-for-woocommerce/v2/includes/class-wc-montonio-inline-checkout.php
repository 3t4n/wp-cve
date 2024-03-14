<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Inline_Checkout {
    
    public function __construct() {
        add_action('wp_ajax_get_payment_intent', array( $this, 'get_payment_intent' ) );
        add_action('wp_ajax_nopriv_get_payment_intent', array( $this, 'get_payment_intent' ) );
    }

    public function get_payment_intent() {
        global $wpdb;

        try {
            $sandbox_mode = $_POST['sandbox_mode'];
            $method = $_POST['method'];

            $montonio_api = new WC_Montonio_API( $sandbox_mode );
            $response = $montonio_api->create_payment_intent( $method );

            WC()->session->set( 'montonio_' . $method . '_intent_uuid', $response->uuid );
            wp_send_json_success( $response );
        } catch ( Exception $e ) {
            WC()->session->set( 'montonio_' . $method . '_intent_uuid', null );
            wp_send_json_error( $e->getMessage() );
        }
    }
}
$wc_montonio_inline_checkout = new WC_Montonio_Inline_Checkout();