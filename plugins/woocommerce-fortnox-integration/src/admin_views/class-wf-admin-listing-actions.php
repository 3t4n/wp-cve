<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\api\WF_Delivery_Ways;
use src\fortnox\api\WF_Invoices;
use src\fortnox\api\WF_Orders;
use src\fortnox\api\WF_Payment_Terms;
use src\fortnox\api\WF_Predefined_Accounts;
use src\fortnox\api\WF_Price_Lists;
use src\fortnox\api\WF_Products;
use src\fortnox\api\WF_Refunds;
use src\fortnox\api\WF_Request;
use src\fortnox\WF_Plugin;
use src\fortnox\WF_Utils;
use src\help\WF_Help_Links;

class WF_Admin_Listing_Actions{


    /** Handler function of order listing manual sync
     * @return array
     * @throws \Exception
     */
    public static function ajax_sync_order(){
        if( empty( $_REQUEST['order_id'] ) ) {
            return [
                'error' => true,
                'message' => __( "Missing order ID.", WF_Plugin::TEXTDOMAIN )
            ];
        }
        else{
            return self::manual_sync_order( $_REQUEST['order_id'] );
        }

    }

    public static function manual_sync_order( $order_id ){
        try {
            $response = WF_Orders::sync( $order_id );
            if( $refund_response = self::maybe_sync_refund( $order_id ) ){
                $response = $refund_response;
            }
        }
        catch( \Exception $error ) {

            if ( $error->getCode() === WF_Orders::FORTNOX_ERROR_CODE_ORDER_ALREADY_INVOICED || $error->getCode() === WF_Orders::FORTNOX_ERROR_CODE_ORDER_ALREADY_INVOICED_2){
                if( $refund_response = self::maybe_sync_refund( $order_id ) ){
                    fortnox_write_log("Doing refund!");
                    return $refund_response;
                }
            }
            return [
                'error' => true,
                'message' => $error->getMessage() . WF_Help_Links::get_error_text( $error->getCode() )
            ];
        }

        if( empty( $response['error'] ) ){
            return [
                'error' => false,
                'message' => __( "Order successfully synced.", WF_Plugin::TEXTDOMAIN )
            ];
        }

        return [
            'error' => true,
            'message' => __( "Error", WF_Plugin::TEXTDOMAIN )
        ];
    }

    /** Handler function of product listing manual sync
     * @return array
     */
    public static function ajax_sync_product(){

        if( empty( $_REQUEST['product_id'] ) ) {
            return [
                'error' => true,
                'message' => __( "Missing product ID.", WF_Plugin::TEXTDOMAIN )
            ];
        }

        try {
            WF_Products::sync( $_REQUEST['product_id'], true );
        }
        catch( \Exception $error ) {
            return [
                'error' => true,
                'message' => $error->getMessage() . WF_Help_Links::get_error_text( $error->getCode() )
            ];
        }

         return [
            'error' => false,
            'message' => __( "Product successfully synced.", WF_Plugin::TEXTDOMAIN )
         ];

    }

    /** Handler function of product listing manual sync
     * @return array
     */
    public static function ajax_send_invoice(){

        if ( empty( $_REQUEST['order_id'] ) ) {
            return [
                'error' => true,
                'message' => __("Missing order ID.", WF_Plugin::TEXTDOMAIN )
            ];
        }

        try {
            $response = WF_Invoices::send_invoice_PDF( $_REQUEST['order_id'] );
        } catch (\Exception $error ) {
            return [
                'error' => true,
                'message' => $error->getMessage() . ' ' . WF_Help_Links::get_error_text( $error->getCode())
            ];
        }

        if ( ! WF_Request::is_error( $response ) ) {

            $order = wc_get_order( $_REQUEST['order_id'] );
            $order->add_meta_data( "invoice_sent", "yes" );
            $order->save();

            return [
                'error' => false,
                'message' => __("Invoice successfully sent to your e-mail.", WF_Plugin::TEXTDOMAIN )
            ];
        }

        return [
            'error' => true,
            'message' => __( "Error", WF_Plugin::TEXTDOMAIN )
        ];
    }

    /**
     *
     */
    public static function bulk_sync_products(){
        $product_ids = self::get_products();
        if ( ! $product_ids ) {
            return [
                'error'   => true,
                'message' => __( 'No products available.',
                    WF_Plugin::TEXTDOMAIN ),
            ];
        }
        return [
            'error'   => false,
            'product_ids' => $product_ids,
        ];
    }


    /**
     * Fetches settings from Fortnox
     */
    public static function fetch_settings(){
        WF_Payment_Terms::get_payment_terms();
        WF_Delivery_Ways::get_delivery_ways();
        WF_Predefined_Accounts::get_predefined_accounts();
        WF_Price_Lists::get_price_lists();

        return  [
            'error'   => false
        ];
    }

    /**
     *
     */
    public static function bulk_sync_orders(){
        $order_ids = self::get_orders_for_date_range_sync( $_REQUEST['from_date'], $_REQUEST['to_date'], $_REQUEST['status'] );
        if ( ! $order_ids ) {
            return [
                'error'   => true,
                'message' => __( 'No orders available in specified range.',
                    WF_Plugin::TEXTDOMAIN ),
            ];
        }
        return  [
            'error'   => false,
            'order_ids' => $order_ids,
        ];
    }



    /** Flush accesstoken
     *
     */
    public static function ajax_flush_access_token(){
        update_option( 'fortnox_access_token', '' );

        return [
            'error' => false,
            'message' => __( "Access token has been removed.", WF_Plugin::TEXTDOMAIN )
        ];
    }

    /**
     *
     * @param $from_date
     * @param $to_date
     * @return array
     */
    private static function get_orders_for_date_range_sync( $from_date, $to_date, $status ) {
        $query_args   = [
            'numberposts' => -1,
            'post_type'      => 'shop_order',
            'post_status'    => 'wc-' . $status,
            'orderby'   => 'post_date',
            'order' => 'ASC',
            'date_query'     => [
                'after'  => $from_date . '00:00:00',
                'before' => $to_date . '23:59:59',
            ],
        ];
        $ids = wp_list_pluck( get_posts( $query_args ), 'ID' );
        return $ids;

    }

    /**
     * @param $order_id
     * @return array
     * @throws \Exception
     */
    private static function maybe_sync_refund( $order_id ) {
        $did_do_refund = false;

        /** Checks if there are any WooCommerce refunds made on this order.
         * If so, then condition is true and all refund_ids are assigned
         * to var $refund_ids
         */
        if( $refund_ids = WF_Utils::get_refunds( $order_id ) ){

            $wc_order = wc_get_order( $order_id );

            /** Checks if order is refunded and that all refunds are NOT synced to fortnox
             * If so, a full refund is processed
             */
            if( $wc_order->get_status() == 'refunded' && ! WF_Refunds::is_refund_synced( $order_id ) ){
                $did_do_refund = true;
                $order = wc_get_order( $order_id );
                WF_Refunds::process_full_refund( $order, $refund_ids[0] );
            }
            else{
                /** If order is not refunded or if all refunds are not synced to Fortnox, every
                 * refund of order is processed individually as partial refunds
                 */
                foreach ( $refund_ids as $refund_id ){
                    if( ! WF_Refunds::is_refund_synced( $refund_id ) ){
                        $did_do_refund = true;
                        $order = wc_get_order( $order_id );
                        WF_Refunds::process_partial_refund( $order, $refund_id );
                    }
                }
            }

            if( $did_do_refund ){
                return [
                    'error' => false,
                    'message' => __( "Order refund successfully synced.", WF_Plugin::TEXTDOMAIN )
                ];
            }
        }
    }

    /**
     * @return array
     */
    private static function get_products(){
        $query = new \WC_Product_Query(array(
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
        ));
        return $query->get_products();
    }

}
