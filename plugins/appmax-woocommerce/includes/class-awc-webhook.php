<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_WebHook
{
    public function __construct()
    {
        add_action( 'rest_api_init', function ( $server ) {
            $server->register_route( 'webhook-system', '/webhook-system', [
                'methods' => 'POST',
                'callback' => array( $this, 'awc_web_hook_post' ),
            ]);
        });
    }

    public function awc_web_hook_post( WP_REST_Request $request )
    {
        $data = $request->get_params();

        $awc_web_hook_post = new AWC_WebHook_Post( $data['data'] );

        if ( AWC_Helper::awc_trim_event( $data['event'] ) == AWC_Events::AWC_ORDER_BILLET_OVERDUE ) {
            $awc_web_hook_post->awc_order_billet_overdue();
        }

        if ( AWC_Helper::awc_trim_event( $data['event'] ) == AWC_Events::AWC_ORDER_PAID ) {
            $awc_web_hook_post->awc_order_paid();
        }

        if ( AWC_Helper::awc_trim_event( $data['event'] ) == AWC_Events::AWC_ORDER_INTEGRATED ) {
            $awc_web_hook_post->awc_order_integrated();
        }

        if ( AWC_Helper::awc_trim_event( $data['event'] ) == AWC_Events::AWC_ORDER_REFUND ) {
            $awc_web_hook_post->awc_order_refund();
        }

        if ( AWC_Helper::awc_trim_event( $data['event'] ) == AWC_Events::AWC_PAYMENT_NOT_AUTHORIZED ) {
            $awc_web_hook_post->awc_payment_not_authorized();
        }
    }
}

new AWC_WebHook();