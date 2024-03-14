<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\WF_Utils;

class WF_Refunds{

    /** Controller function for handling a refund
     * @param int $order_id
     * @param int $refunded_order_id
     * @return mixed
     * @throws \Exception
     */
    public static function handle_refund( $order_id, $refunded_order_id ){
        $order = wc_get_order( $order_id );
        try{
            if( self::order_is_totally_refunded( $order ) ){
                return self::process_full_refund( $order, $refunded_order_id );
            }
            else{
                return self::process_partial_refund( $order, $refunded_order_id );
            }
        }
        catch( \Exception $error ) {
            WF_Orders::add_order_log( $order, $error );
        }

    }

    /**
     * Returns true if order refund total equals order total
     * @param WC_Order $order
     * @return bool
     */
    private static function order_is_totally_refunded( $order ){
        return $order->get_total() == $order->get_total_refunded();
    }

    /** Controller function for handling a partial refund
     * @param \WC_Order $order
     * @param int $refunded_order_id
     * @return mixed
     * @throws \Exception
     */
    public static function process_partial_refund( $order, $refunded_order_id ){
        $refunded_order = wc_get_order( $refunded_order_id );
        if( WF_Invoices::get_invoice_number( $order ) ){
            $refunded_invoice_number = WF_Partial_Refund_Invoices::create_partial_credit_invoice( $order, $refunded_order );
            WF_Partial_Refund_Invoices::post_process( $order, $refunded_order, $refunded_invoice_number );
        }
        else{
            WF_Partial_Refund_Orders::create_partial_order_refund( $order, $refunded_order );
        }

    }

    /** Controller function for handling a full refund
     * @param \WC_Order $order
     * @return mixed
     * @throws \Exception
     */
    public static function process_full_refund( $order, $refund_id ){
        if( WF_Invoices::get_invoice_number( $order ) ){
            $refunded_invoice_number = WF_Full_Refund_Invoices::create_full_credit_invoice( $order, $refund_id );
            //TODO FIX wc_get_order( $refund_id )
            WF_Full_Refund_Invoices::post_process( $order,wc_get_order( $refund_id ), $refunded_invoice_number );
        }
        else{
            WF_Orders::cancel_order( $order );
        }
    }

    /**
     * Check whether order refund is synced to Fortnox

     *
     * @param int $order_id
     * @return mixed
     */
    public static function is_refund_synced( $refund_order_id ) {
        return WF_Utils::get_order_meta_compat( $refund_order_id, '_fortnox_order_refund_synced' );
    }

    /**
     * Handle Custom Shipping
     *
     * @param $order
     * @param $refunded_order
     * @return mixed
     * @throws \Exception
     */
    public static function get_custom_refund_shipping( $order, $refunded_order ){

        $address = $order->get_address();
        $shipping_account = WF_Orders::get_shipping_account( $address['country'] );

        $shipping_account = apply_filters( 'wf_order_shipping_account', $shipping_account, $order );

        return [
            'AccountNumber'     => $shipping_account,
            'Description'       => "Frakt",
            'DeliveredQuantity' => -1,
            'Unit'              => "st",
            'Price'             => abs( $refunded_order->get_total_shipping() ),
            'Discount'          => 0,
            'DiscountType'      => "AMOUNT",
            'VAT'               => self::get_max_vat_rate( $order, $address['country'] )
        ];
    }

    /** Returns max VAT percentage used by order based on total sum grouped by VAT percentage
     * @param $order
     * @param $country_code
     * @return int
     */
    public static function get_max_vat_rate( $order, $country_code ){
        $max  = [ 'value' => 0, 'rate' => '' ];

        foreach( $order->get_items() as $line_item ){
            /**
             * @var \WC_Order_Item_Product $line_item
             */
            $tax        = (float)$line_item->get_total_tax();
            $total      = (float)$line_item->get_total();

            if( ! $total || ! $tax ) continue;

            if( ! ( $tax_rate = WF_Utils::get_wc_tax_rate( $line_item->get_product(), $country_code ) ) ) continue;

            $rate[ $tax_rate ] = ( empty( $rate[ $tax_rate ] ) ? $total : $rate[ $tax_rate ] + $total );

            if( $rate[ $tax_rate ] > $max[ 'value' ] ){
                $max[ 'value' ] = $rate[ $tax_rate ];
                $max[ 'rate'  ] = $tax_rate;
            }
        }
        return $max['rate'];
    }

    /**
     * Sets postmeta '_fortnox_order_refund_synced' of shop_order to true
     * @param int $order_id
     */
    public static function set_order_refund_as_synced( $refund_order ) {
        $refund_order->update_meta_data( '_fortnox_order_refund_synced', 1 );
        $refund_order->save();
    }
}
