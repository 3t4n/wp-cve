<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Utils;
use WC_Order;

class WF_Refund_Invoices {

    /**
     * Sends invoice to Fortnox
     * @param mixed $fortnox_invoice
     * @return mixed
     * @throws \Exception
     */
    public static function send_invoice_to_fortnox( $fortnox_invoice ){
        $response =  WF_Request::post( "/invoices/", [
            'Invoice' => $fortnox_invoice
        ] );
        return $response->Invoice->DocumentNumber;
    }

    /**
     * @param $order
     * @return mixed
     */
    public static function get_refund_invoice_number( $order ){
        return $order->get_meta( 'fortnox_refund_invoice_number'  );
    }

    /**
     * @param $order
     */
    public static function set_refund_invoice_number( $order, $invoice_number ){
        $order->add_meta_data( 'fortnox_refund_invoice_number', $invoice_number, true );
        $order->save();
    }

    /**
     * Sets invoiced as paid
     * @param WC_Order $order
     * @return void
     * @throws \Exception
     */
    public static function make_refund_invoice_payment( $order, $refunded_order, $invoice_number ){
        if ( !is_a( $order, 'WC_Order_Refund' ) ) {
            $payment_method = $order->get_payment_method();
        } else {
            $parent_order = wc_get_order( $order->get_parent_id() );
            $payment_method = $parent_order->get_payment_method();
        }

        $invoice_payment = apply_filters( 'wf_refund_invoice_payment_before_create_or_update', [
            "AmountCurrency" => $refunded_order->get_total(),
            "InvoiceNumber" => $invoice_number,
            "PaymentDate" => substr( $refunded_order->get_date_created(), 0, 10),
            "ModeOfPaymentAccount" => WF_Invoices::get_payment_account( $payment_method )
        ], $order );

        $response = WF_Request::post("/invoicepayments", [
            "InvoicePayment" => $invoice_payment
        ]);

        WF_Request::put("/invoicepayments/" . $response->InvoicePayment->Number . "/bookkeep");
    }

    /**
     * Sets invoiced as paid
     * @param WC_Order $order
     * @return void
     * @throws \Exception
     */
    public static function set_credit_invoice_reference( $order, $refunded_invoice_number ){
        if ( $invoice_number = WF_Invoices::get_invoice_number( $order ) ) {
            $invoice = [
                "CreditInvoiceReference" => $refunded_invoice_number,
            ];
            try {
                WF_Request::put("/invoices/" . $invoice_number, [ 'Invoice' => $invoice ] );
            } catch (Exception $e) {

            }

        }
    }

    /**
     * @param $order
     * @return bool
     * @throws Exception
     */
    /*public static function create_credit_invoice( $order ){
        $invoice_number = WF_Invoices::get_invoice_number( $order );

        if( $invoice_number ){
            $response = WF_Request::put("/invoices/" . $invoice_number . "/credit" );
            return $response->Invoice->CreditInvoiceReference;
        }
        return false;
    }*/
}
