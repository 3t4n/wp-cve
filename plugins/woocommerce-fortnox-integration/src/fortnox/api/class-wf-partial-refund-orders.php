<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Plugin;
use WC_Order;

class WF_Partial_Refund_Orders
{

    /**
     * @param $order
     * @param $refunded_order
     * @return mixed
     * @throws Exception
     */
    public static function create_partial_order_refund( $order, $refunded_order ){

        $order_number = WF_Orders::get_order_number( $order->get_id(), $order );
        try {
            $customer_number = WF_Customers::get_customer_number( $order );
            $fortnox_order = [
                'CustomerNumber'             => $customer_number,
                'YourOrderNumber'            => $order_number,
                'ExternalInvoiceReference1'  => WF_Orders::get_order_number( $order->get_id(), $order ),
                'VATIncluded'                => false,
                'Currency'                   => $refunded_order->get_currency(),
            ];

            $order_rows = self::format_partial_order_refund_rows( $order, $refunded_order, [ 'DeliveryCountryCode' => $order->get_shipping_country() ] );

            if ( intval( $refunded_order->get_shipping_total() ) != 0 ) {
                $shipping = WF_Refunds::get_custom_refund_shipping( $order, $refunded_order );
                $order_rows[] = $shipping;
                $fortnox_order['Freight'] = 0;
            }

            $fortnox_order['OrderRows'] = $order_rows;
            $fortnox_order = WF_Orders::handle_currency( $fortnox_order, $refunded_order->get_currency() );
            $fortnox_order = WF_Orders::handle_fees( $refunded_order, $fortnox_order );
            $fortnox_order['PriceList'] = WF_Products::fortnox_price_list();

            if ( $cost_center = get_option( 'fortnox_cost_center' ) ) {
                $fortnox_order['CostCenter'] = $cost_center;
            }

            WF_Request::put("/orders/{$order_number}", [
                'Order' => $fortnox_order
            ] );

            WF_Refunds::set_order_refund_as_synced( $order );

            $order->add_order_note( __( 'Refund sent to Fortnox: ', WF_Plugin::TEXTDOMAIN ) );

        } catch ( \Exception $error ) {
            WF_Orders::add_order_log( $order, $error );
            WF_Orders::set_order_notice_flag( $order );
            throw new \Exception( $error->getMessage(), $error->getCode() );
        }

        do_action( 'wf_order_after_partial_refund', $order, $refunded_order );
    }

    /**
     * @param \WC_Order $order
     * @param $customer
     * @return array
     * @throws \Exception
     */
    public static function format_partial_order_refund_rows( $order, $refunded_order, $customer ){

        $order_rows = [];
        $refund_product_ids = [];
        foreach( $refunded_order->get_items() as $refund_order_item ) {
            $refund_product_ids[] = $refund_order_item->get_product_id();
            $order_item = self::get_order_item_by( $order, $refund_order_item->get_product_id() );
            $new_quantity = $order_item->get_quantity() + $refund_order_item->get_quantity();
            $order_row = self::create_partial_order_refund_row( $refund_order_item, $new_quantity, $refunded_order, $customer );
            if ( isset( $order_row[0] ) && is_array( $order_row[0] ) ) {
                $func = function( $row ) {
                    return $row;
                };
                $order_rows = array_merge( $order_rows, array_map( $func, $order_row ) );
            }
            else{
                $order_rows[] = $order_row;
            }
        }

        foreach( $order->get_items() as $order_item ) {

            if ( ! in_array( $order_item->get_product_id(), $refund_product_ids ) ){
                $order_row = self::create_partial_order_refund_row( $order_item, $order_item->get_quantity(), $refunded_order, $customer );
                if ( isset( $order_row[0] ) && is_array( $order_row[0] ) ) {
                    $func = function( $row ) {
                        return $row;
                    };
                    $order_rows = array_merge( $order_rows, array_map( $func, $order_row ) );
                }
                else{
                    $order_rows[] = $order_row;
                }
            }

        }
        return $order_rows;
    }

    private static function get_order_item_by( $order, $product_id ){
        foreach( $order->get_items() as $item ) {
            if( $item->get_product_id() === $product_id ){
                return $item;
            }
        }
    }

    /**
     * Creates order row array
     *
     * @param \WC_Order_Item_Product $item
     * @param \WC_Order $order
     * @param WC_Customer $customer
     * @return mixed
     * @throws \Exception
     */
    public static function create_partial_order_refund_row( $item, $new_quantity, $refunded_order, $customer ){

        $product = ( WF_Orders::item_is_variation( $item ) ) ? wc_get_product( $item->get_variation_id() ) : wc_get_product( $item->get_product_id() );
        $product_name = WF_Orders::get_product_name( $item );

        if ( wc_get_product( $product->get_id() ) ) {
            WF_Products::sync( $product->get_id(), $sync_stock=false );
        }

        $subtotal = $refunded_order->get_item_subtotal( $item, false, false );
        $total = $refunded_order->get_item_total( $item, false, false );

        if( $item->get_quantity() != 0 ){
            $order_row = apply_filters( 'wf_order_row_payload_before_create_or_update', [
                'ArticleNumber'     => WF_Products::sanitized_sku( $product->get_sku() ),
                'Description'       => WF_Products::sanitize_description( $product_name ),
                'DeliveredQuantity' => $new_quantity,
                'OrderedQuantity'   => $new_quantity,
                'Unit'              => "st",
                'Price'             => abs( $subtotal ),
                'Discount'          => WF_Orders::calculate_item_discount( abs( $subtotal ), abs( $total ), $new_quantity ),
                'DiscountType'      => "AMOUNT",
                'VAT'               => WF_Orders::get_tax( $refunded_order, $product, $customer ),
            ], $product, $item, wc_get_order( $refunded_order->get_parent_id() ) );
        }
        else{
            $order_row = apply_filters( 'wf_order_row_payload_before_create_or_update', [
                'Description'       => WF_Products::sanitize_description( $product_name ),
                'DeliveredQuantity' => -1,
                'OrderedQuantity'   => -1,
                'Unit'              => "st",
                'Price'             => abs( $refunded_order->get_line_total( $item, false, false ) ),
                'Discount'          => WF_Orders::calculate_item_discount( abs( $subtotal ), abs( $total ), $item->get_quantity() ),
                'DiscountType'      => "AMOUNT",
                'VAT'               => WF_Orders::get_tax( $refunded_order, $product, $customer ),
            ], $product, $item, wc_get_order( $refunded_order->get_parent_id() ) );
        }

        $account_number = false;
        if ( has_filter( 'wetail_fortnox_modify_order_row_sales_account'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_modify_order_row_sales_account filter', '', 'wf_order_row_sales_account'  );
            $account_number = apply_filters( 'wetail_fortnox_modify_order_row_sales_account', false, $refunded_order, $item );
        }

        if( $account_number ){
            $order_row['AccountNumber'] = $account_number;
        }

        return $order_row;
    }
}

