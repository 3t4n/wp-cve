<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Plugin;
use WC_Order;

class WF_Invoices {

    /**
     * @param \WC_Order $order
     * @throws \Exception
     */
    public static function maybe_create_invoice( $order ){
        if( get_option( 'fortnox_auto_create_order_invoice' ) ){

            if( get_option( 'fortnox_has_warehouse_module' ) ){
                WF_Orders::mark_as_ready( preg_replace( '/\D/', '', WF_Orders::get_order_number( $order->get_id(), $order ) ) );
            }

            self::create_invoice( $order, $order->get_id() );

            if( get_option( 'fortnox_auto_set_invoice_as_paid'  ) ){

                if( floatval( $order->get_total() ) >  0.0 ){
                    WF_Invoices::make_invoice_payment( $order );
                }
            }
        }
    }

    /**
     * Creates an invoice of synced order
     * @param WC_Order $order
     * @param int $order_id
     * @return int
     * @throws \Exception
     */
    public static function create_invoice( $order, $order_id ){

        # Create Invoice in Fortnox
        $response = WF_Request::put("/orders/" . WF_Orders::get_order_number( $order_id, $order ) . "/createinvoice");

        $invoice_number = $response->Order->InvoiceReference;

        self::set_invoice_number( $order, $invoice_number );

        # Update "OrderDate" field after the creation of the invoice - it may be earlier than the date of sync with the Fortnox
        # Thus it is possible to update also other fields - the main thing, this possible to do it only before the publication of the invoice
        $invoice = [
            'InvoiceDate' => substr( $order->get_date_created(), 0, 10 ),
            'YourReference' => $order->get_billing_first_name() . ' '  . $order->get_billing_last_name(),
            'ExternalInvoiceReference1' => apply_filters( 'woocommerce_order_number', $order_id, $order )
        ];

        $invoice = apply_filters( 'wf_invoice_before_create_or_update', $invoice, $order );

        if ( has_filter( 'wetail_fortnox_invoice_before_fortnox_submit'  ) ) {
            wc_deprecated_function( 'The filter', '', 'wf_invoice_before_create_or_update'  );
            $invoice = apply_filters( 'wetail_fortnox_invoice_before_fortnox_submit', $invoice, $order );
        }

        WF_Request::put("/invoices/" . $invoice_number, [ 'Invoice' => $invoice ] );

        if( get_option( 'fortnox_auto_post_order_invoice'  ) ){

            if( get_option( 'fortnox_has_warehouse_module'  ) ){
                WF_Invoices::mark_as_ready( $invoice_number );
            }

            try{
                fortnox_write_log( "bookkeep invoice: " );
                WF_Request::put("/invoices/" . $invoice_number . "/bookkeep" );
            }
            catch (\Exception $e ){
                fortnox_write_log( $e );
                if( WF_Orders::FORTNOX_ERROR_CODE_CANNOT_BOOKKEEP === $e->getCode() ){
                    self::make_invoice_payment( $order );
                    delete_option( 'fortnox_auto_set_invoice_as_paid'  );
                }
            }
        }

        if( get_option( 'fortnox_auto_send_order_invoice'  ) ) {
            WF_Invoices::send_invoice_PDF( $order_id );
        }

        do_action( 'wf_invoice_after_create', $order );

        return $invoice_number;
    }

    /**
     * Creates an invoice of synced order
     * @param \WC_Order  $order
     * @param \WC_Order $refunded_order
     * @return integer
     * @throws \Exception
     */
    public static function create_credit_note( $order, $refunded_order ){#TODO is this function needed

        # Create Invoice in Fortnox
        $response = WF_Request::put("/orders/" . WF_Orders::get_order_number( $refunded_order->get_id(), $refunded_order ) . "/createinvoice");

        $invoice_number = $response->Order->InvoiceReference;

        $refunded_order->add_meta_data( 'fortnox_invoice_number', $invoice_number );
        $refunded_order->save_meta_data();

        # Update "OrderDate" field after the creation of the invoice - it may be earlier than the date of sync with the Fortnox
        # Thus it is possible to update also other fields - the main thing, this possible to do it only before the publication of the invoice
        $invoice = [
            'InvoiceDate' => substr( $refunded_order->get_date_created(), 0, 10 ),
            'YourReference' => $order->get_billing_first_name() . ' '  . $order->get_billing_last_name(),
            'ExternalInvoiceReference1' => apply_filters( 'woocommerce_order_number', $refunded_order->get_id(), $refunded_order )
        ];

        WF_Request::put("/invoices/" . $invoice_number, [ 'Invoice' => $invoice ] );

        # Publication of the invoice - thereafter any changes inpossible
        if( get_option( 'fortnox_auto_post_refund_invoice'  ) ){

            if( get_option( 'fortnox_has_warehouse_module'  ) ){
                WF_Invoices::mark_as_ready( $invoice_number );
            }
            WF_Request::put("/invoices/" . $invoice_number . "/bookkeep" );
        }

        return $invoice_number;
    }

    /**
     * Creates an invoice of synced order
     * @param \WC_Order  $order
     * @param \WC_Order $refunded_order
     * @return integer
     * @throws \Exception
     */
    public static function create_full_credit_note( $order, $order_number ){

        # Create Invoice in Fortnox
        $response = WF_Request::put("/orders/" . $order_number . "/createinvoice");

        $invoice_number = $response->Order->InvoiceReference;

        # Update "OrderDate" field after the creation of the invoice - it may be earlier than the date of sync with the Fortnox
        # Thus it is possible to update also other fields - the main thing, this possible to do it only before the publication of the invoice
        $invoice = [
            'InvoiceDate' => substr( $order->get_date_modified(), 0, 10 ),
            'YourReference' => $order->get_billing_first_name() . ' '  . $order->get_billing_last_name(),
            'ExternalInvoiceReference1' => apply_filters( 'woocommerce_order_number', $order->get_id(), $order )
        ];

        WF_Request::put("/invoices/" . $invoice_number, [ 'Invoice' => $invoice ] );

        # Publication of the invoice - thereafter any changes inpossible
        if( get_option( 'fortnox_auto_post_refund_invoice'  ) ){

            if( get_option( 'fortnox_has_warehouse_module'  ) ){
                WF_Invoices::mark_as_ready( $invoice_number );
            }
            WF_Request::put("/invoices/" . $invoice_number . "/bookkeep" );
        }

        return $invoice_number;
    }

    /**
     * Creates an invoice of synced order
     * @param $invoice_number
     * @return mixed
     * @throws \Exception
     */
    public static function mark_as_ready( $invoice_number ){
        return WF_Request::put("/invoices/" . $invoice_number . "/warehouseready" );
    }

    /**
     * Sets invoiced as paid
     * @param WC_Order $order
     * @return void
     * @throws \Exception
     */
    public static function make_invoice_payment( $order ){

        $order->calculate_totals();
        $order_number = WF_Orders::get_order_number( $order->get_id(), $order );
        $response = WF_Request::get( "/orders/" . preg_replace('/\D/', '', $order_number ) );

        $invoice_payment = apply_filters( 'wf_invoice_payment_before_create_or_update', [
            "AmountCurrency"            => $order->get_total(),
            "InvoiceNumber"             => $response->Order->InvoiceReference,
            "PaymentDate"               => substr( $order->get_date_created(), 0, 10 ),
            "ModeOfPaymentAccount"      => self::get_payment_account( $order->get_payment_method() )
        ], $order );

        if ( has_filter( 'wetail_fortnox_invoice_payment'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_invoice_payment filter', '', 'wf_invoice_payment_before_create_or_update'  );
            $invoice_payment = apply_filters( 'wetail_fortnox_invoice_payment', [
                "AmountCurrency"            => $order->get_total(),
                "InvoiceNumber"             => $response->Order->InvoiceReference,
                "PaymentDate"               => substr( $order->get_date_created(), 0, 10 ),
                "ModeOfPaymentAccount"      => self::get_payment_account( $order->get_payment_method() )
            ], $order );
        }

        $response = WF_Request::post( "/invoicepayments", [
            "InvoicePayment" => $invoice_payment
        ] );

        WF_Request::put("/invoicepayments/" . $response->InvoicePayment->Number . "/bookkeep" );
    }

    /**
     * @param $order_id
     * @return bool|mixed
     * @throws \Exception
     */
    public static function send_invoice_PDF( $order_id ) {
        $order = wc_get_order( $order_id );

        if ( $invoice_number = self::get_invoice_number( $order ) ) {
            $order->add_order_note( __( 'Invoice email sent to customer', WF_Plugin::TEXTDOMAIN ) );
            return WF_Request::get( "/invoices/" . $invoice_number . "/email" );
        }
        else {
            return false;
        }
    }

    /** Adds custom payment account by order payment method
     * @param $payment_method
     * @return int
     */
    public static function get_payment_account( $payment_method ) {

        if ( get_option( 'fortnox_invoice_payment_account_' . $payment_method ) ) {
            return get_option( 'fortnox_invoice_payment_account_' . $payment_method );
        }
        return 1930;
    }

    /**
     * @param $order
     * @return mixed
     */
    public static function get_invoice_number( $order ){
        return $order->get_meta( 'fortnox_invoice_number' );
    }

    /**
     * @param $order
     */
    public static function set_invoice_number( $order, $invoice_number ){
        $order->add_meta_data( 'fortnox_invoice_number', $invoice_number, true );
        $order->save();
    }
}
