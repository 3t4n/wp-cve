<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Plugin;
use WC_Order;

class WF_Partial_Refund_Invoices extends WF_Refund_Invoices
{
    /**
     * @param $refunded_order
     * @param $invoice_number
     * @throws Exception
     */
    public static function post_process( $order, $refunded_order, $refunded_invoice_number ){
        if ( get_option( 'fortnox_auto_post_refund_invoice' ) ) {

            if ( get_option( 'fortnox_has_warehouse_module' ) ) {
                WF_Invoices::mark_as_ready( $refunded_invoice_number );
            }
            WF_Request::put("/invoices/" . $refunded_invoice_number . "/bookkeep" );
            //WF_Refund_Invoices::set_credit_invoice_reference( $order, $refunded_invoice_number );

            if ( get_option( 'fortnox_auto_set_refund_invoice_as_paid' ) ) {

                if ( floatval( $refunded_order->get_total() ) != 0.0 ) {
                    self::make_refund_invoice_payment( $order, $refunded_order, $refunded_invoice_number );
                }
            }
        }
    }

    /**
     * @param $order
     * @param $refunded_order
     * @return mixed
     * @throws Exception
     */
    public static function create_partial_credit_invoice( $order, $refunded_order ){


        $order_number = WF_Orders::get_order_number( $refunded_order->get_id(), $refunded_order );
        try {
            $customer_number = WF_Customers::get_customer_number( $order );
            $fortnox_invoice = apply_filters( 'wf_refund_invoice_payload_before_create', [
                'CustomerNumber'             => $customer_number,
                'YourOrderNumber'            => $order_number,
                'ExternalInvoiceReference1'  => WF_Orders::get_order_number( $order->get_id(), $order ),
                'InvoiceDate'                => substr( $refunded_order->get_date_created(), 0, 10 ), # To cut off order time
                'VATIncluded'                => false,
                'Currency'                   => $refunded_order->get_currency(),
            ], $refunded_order );

            $invoice_rows = self::format_partial_refund_invoice_rows( $refunded_order, [ 'DeliveryCountryCode' => $order->get_shipping_country()] );

            if ( intval( $refunded_order->get_shipping_total() ) != 0 ) {
                $shipping = WF_Refunds::get_custom_refund_shipping( $order, $refunded_order );
                $invoice_rows[] = $shipping;
                $fortnox_invoice['Freight'] = 0;
            }

            $fortnox_invoice['InvoiceRows'] = $invoice_rows;
            $fortnox_invoice = WF_Orders::handle_currency( $fortnox_invoice, $refunded_order->get_currency() );
            $fortnox_invoice = WF_Orders::handle_fees( $refunded_order, $fortnox_invoice );
            $fortnox_invoice['PriceList'] = WF_Products::fortnox_price_list();

            if ( $cost_center = get_option( 'fortnox_cost_center' ) ) {
                $fortnox_invoice['CostCenter'] = $cost_center;
            }

            $invoice_number = self::send_invoice_to_fortnox( $fortnox_invoice );

            WF_Refunds::set_order_refund_as_synced( $refunded_order );
            self::set_refund_invoice_number( $order, $invoice_number );

            $order->add_order_note(__( 'Refund sent to Fortnox ID: ', WF_Plugin::TEXTDOMAIN) . $invoice_number );

        } catch (\Exception $error ) {
            WF_Orders::add_order_log( $order, $error );
            WF_Orders::set_order_notice_flag( $order );
            throw new \Exception( $error->getMessage(), $error->getCode() );
        }

        do_action( 'wf_order_after_partial_refund', $order, $refunded_order );

        return $invoice_number;
    }

    /**
     * @param \WC_Order $order
     * @param $customer
     * @return array
     * @throws \Exception
     */
    public static function format_partial_refund_invoice_rows( $refunded_order, $customer ){

        $order_rows = [];

        foreach( $refunded_order->get_items() as $item ) {
            $order_row = self::create_partial_refund_invoice_row( $item, $refunded_order, $customer );
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
        return $order_rows;
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
    public static function create_partial_refund_invoice_row( $item, $refunded_order, $customer ){

        $product = ( WF_Orders::item_is_variation( $item ) ) ? wc_get_product( $item->get_variation_id() ) : wc_get_product( $item->get_product_id() );
        $product_name = WF_Orders::get_product_name( $item );

        if ( wc_get_product( $product->get_id() ) ) {
            WF_Products::sync( $product->get_id(), $sync_stock=false );
        }

        $subtotal = $refunded_order->get_item_subtotal( $item, false, false );
        $total = $refunded_order->get_item_total( $item, false, false );

        if( $item->get_quantity() != 0 ){
            $order_row = apply_filters( 'wf_invoice_refund_row_payload_before_create_or_update', [
                'ArticleNumber'     => WF_Products::sanitized_sku( $product->get_sku() ),
                'Description'       => WF_Products::sanitize_description( $product_name ),
                'DeliveredQuantity' => $item->get_quantity(),
                'Unit'              => "st",
                'Price'             => abs( $subtotal ),
                'Discount'          => WF_Orders::calculate_item_discount( abs( $subtotal ), abs( $total ), $item->get_quantity() ),
                'DiscountType'      => "AMOUNT",
                'VAT'               => WF_Orders::get_tax( $refunded_order, $product, $customer ),
            ], $product, $item, wc_get_order( $refunded_order->get_parent_id() ) );
        }
        else{
            $order_row = apply_filters( 'wf_invoice_refund_row_payload_before_create_or_update', [
                'Description'       => WF_Products::sanitize_description( $product_name ),
                'DeliveredQuantity' => -1,
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
