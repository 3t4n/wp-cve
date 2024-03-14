<?php
namespace WCFM\PaypalMarketplace\WebhookEvents;

use WCFM\PaypalMarketplace\Client;
use WCFM\PaypalMarketplace\Helper;

class CheckoutOrderApproved {
    protected $event;

    public function __construct( $event ) {
        $this->event = $event;
    }

    public function handle() {
        $client     = Client::init();
        $order_id   = sanitize_text_field( $this->event->resource->purchase_units[0]->invoice_id );
        $order      = wc_get_order( $order_id );

        // check payment gateway used was paypal marketplace
        if ( $order->get_payment_method() !== Helper::payment_gateway_id() ) {
            return;
        }
        
        $response   = $client->capture_payment($this->event->resource->id);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        foreach ( $response->purchase_units as $purchase_unit ) {
            $vendor_id  = Helper::get_user_id_by_merchant_id( $purchase_unit->payee->merchant_id );
            $capture_id = $purchase_unit->payments->captures[0]->id;
            $order->update_meta_data( '_wcfm_paypal_payment_charge_captured__for_vendor_' . $vendor_id, $capture_id );
            $order->add_order_note( sprintf( __( 'Paypal Transaction ID for %s(#%s): %s', 'wc-frontend-manager-direct-paypal' ), wcfm_get_vendor_store_name( $vendor_id ), $vendor_id, $capture_id ) );
        }

        //store paypal debug id
        $order->update_meta_data( '_wcfm_paypal_capture_payment_debug_id', $response->paypal_debug_id );
        $order->update_meta_data( '_wcfm_paypal_payment_charge_captured_data', $response );
        $order->save_meta_data();
    }
}
