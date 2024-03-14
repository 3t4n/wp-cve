<?php
namespace WCFM\PaypalMarketplace\WebhookEvents;

use WCFM\PaypalMarketplace\Helper;

class CheckoutOrderCompleted {
    protected $event;

    public function __construct( $event ) {
        $this->event = $event;
    }

    public function handle() {
        $paypal_order_id = sanitize_text_field( $this->event->resource->id );
        $order_id        = sanitize_text_field( $this->event->resource->purchase_units[0]->invoice_id );
        $order           = wc_get_order( $order_id );

        if ( ! $order ) {
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Invalid Order', 'error' );
            return;
        }

        if ( $order->get_payment_method() !== Helper::payment_gateway_id() ) {
            return;
        }

        $is_captured = $order->get_meta( '_wcfm_paypal_payment_charge_captured', true );

        // check if order is already processed
        if ( $is_captured ) {
            return;
        }

        $purchase_units = json_decode( wp_json_encode( $this->event->resource->purchase_units ), true );

        // validate order status as completed, (COMPLETED = The payment was authorized or the authorized payment was captured for the order. )
        if (
            isset( $this->event->resource->intent, $this->event->resource->status ) &&
            'CAPTURE' === $this->event->resource->intent &&
            'COMPLETED' === $this->event->resource->status
        ) {
            // make paymet completed
            $order->payment_complete();
            $order->update_meta_data( 'paypal_payment_info', $this->event );
            $order->update_meta_data( '_wcfm_paypal_payment_charge_captured', true );
            $order->save();
            
            /**
             * @args WC_Order $order Main Order ID
             * @args array $purchase_units
             * $args string $paypal_order_id
             */
            do_action( 'wcfm_paypal_capture_payment_completed', $order, $purchase_units, $paypal_order_id );
        }
    }
}
