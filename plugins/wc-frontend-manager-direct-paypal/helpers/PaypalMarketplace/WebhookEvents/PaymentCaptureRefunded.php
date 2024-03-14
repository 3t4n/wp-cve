<?php
namespace WCFM\PaypalMarketplace\WebhookEvents;

class PaymentCaptureRefunded {
    protected $event;

    public function __construct( $event ) {
        $this->event = $event;
    }

    public function handle() {
        $refund_id  = sanitize_text_field( $this->event->resource->id );
        $order_id   = sanitize_text_field( $this->event->resource->invoice_id );
        $order      = wc_get_order( $order_id );

        if ( $this->event->resource->status == 'COMPLETED' ) {
            wcfm_paypal_log('[WCFM Paypal Marketplace] Paypal Refund(#' . $refund_id . ') Completed for order #' . $order_id . ': ' . $this->event->summary, 'info');
        }

        $order->update_meta_data('_wcfm_paypal_payment_refund_details_' . $refund_id, $this->event);
        $order->save();
    }
}
