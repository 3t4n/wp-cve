<?php
namespace WCFM\PaypalMarketplace\WebhookEvents;

use WCFM\PaypalMarketplace\Helper;
use WCFM\PaypalMarketplace\Client;

class MerchantOnboardingCompleted {
    protected $event;

    public function __construct( $event ) {
        $this->event = $event;
    }

    public function handle() {
        $merchant_id = sanitize_text_field( $this->event->resource->merchant_id );
        $user_id     = Helper::get_user_id_by_merchant_id( $merchant_id );

        if ( ! $user_id || ! $merchant_id ) {
            wcfm_paypal_log( sprintf( '[%1$s] Webhook Error: Invalid User (%2$s) or Merchant ID (%3$s )', Helper::payment_gateway_title(), $user_id, $merchant_id ), 'error' );
            return;
        }

        $paypal_settings = get_user_meta( $user_id, Helper::get_paypal_marketplace_settings_key(), true );
        // check if merchant status is success
        if ( $paypal_settings['connection_status'] !== 'success' ) {
            $client = Client::init();
            $merchant_info = $client->get_merchant_info( $merchant_id );

            if ( is_wp_error( $merchant_info ) ) {
                wcfm_paypal_log( '[WCFM Paypal Marketplace] Unable to fetch merchant details: ' . print_r( $merchant_info, true ), 'error' );
            }

            Helper::update_merchant_info( $user_id, $merchant_info );
        }
    }
}
