<?php

namespace ACFWF\Abstracts;

use ACFWF\Abstracts\Abstract_Report_Widget;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Abstract coupons report widget class.
 *
 * @since 4.3
 */
class Abstract_Coupons_Report_Widget extends Abstract_Report_Widget {
    /*
    |--------------------------------------------------------------------------
    | Query methods
    |--------------------------------------------------------------------------
    */

    /**
     * Query coupons table usage and discounted data for the provided date period range.
     *
     * @since 4.3
     * @since 4.5.1 Add support for BOGO, Add Products and Shipping overrides discounts.
     * @access protected
     *
     * @return array Coupon table data.
     */
    protected function _query_coupons_table_data() {
        $orders = $this->_query_orders();
        $data   = array();

        foreach ( $orders as $order ) {
            // Skip orders without coupons.
            if ( empty( $order->get_coupons() ) ) {
                continue;
            }

            $currency_settings  = array( 'user_currency' => $order->get_currency() );
            $order_coupons_data = array_map(
                function ( $item ) use ( $order, $currency_settings ) {
                    $discount     = apply_filters( 'acfw_filter_amount', $item->get_discount(), $currency_settings );
                    $discount_tax = apply_filters( 'acfw_filter_amount', $item->get_discount_tax(), $currency_settings );
                    return array(
                        'order_item_id'  => $item->get_id(),
                        'ID'             => $item->get_id(),
                        'code'           => $item->get_code(),
                        'discount'       => apply_filters( 'acfw_query_report_get_discount', $discount, $item, $order ),
                        'discount_tax'   => apply_filters( 'acfw_query_report_get_discount_tax', $discount_tax, $item, $order ),
                        'order_currency' => $order->get_currency(),
                        'extra_discount' => apply_filters( 'acfw_query_report_extra_discount', \ACFWF()->Helper_Functions->get_coupon_order_item_extra_discounts( $item, false ), $item, $order ),
                    );
                },
                $order->get_coupons(),
            );

            $data = array_merge( $data, $order_coupons_data );
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Utility methods
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate usage per coupon based on the results of the coupon table data.
     *
     * @since 4.3
     * @access protected
     *
     * @param array $results Coupon table data.
     * @return array Coupon and usage key value pair.
     */
    protected function _calculate_usage_per_coupon( $results ) {
        $usage = array();
        foreach ( $results as $row ) {

            // create coupon entry if it doesn't exist yet.
            if ( ! isset( $usage[ $row['ID'] ] ) ) {
                $usage[ $row['ID'] ] = 0;
            }

            // increment usage count for coupon.
            ++$usage[ $row['ID'] ];
        }

        return $usage;
    }

    /**
     * Calculate usage per coupon based on the results of the coupon table data.
     *
     * @since 4.3
     * @since 4.5.1 Add support for BOGO, Add Products and Shipping overrides discounts.
     * @access protected
     *
     * @param array $results Coupon table data.
     * @return array Coupon and discounted total key value pair
     */
    protected function _calculate_discount_total_per_coupon( $results ) {
        $discounted = array();
        foreach ( $results as $row ) {
            // create coupon entry if it doesn't exist yet.
            if ( ! isset( $discounted[ $row['ID'] ] ) ) {
                $discounted[ $row['ID'] ] = 0;
            }

            // add discounted amount to total for coupon.
            $discounted[ $row['ID'] ] += \wc_add_number_precision( $row['discount'] ) + \wc_add_number_precision( $row['discount_tax'] ) + \wc_add_number_precision( $row['extra_discount'] );
        }

        return array_map( 'wc_remove_number_precision', $discounted );
    }

    /**
     * Format coupon table data from raw data.
     *
     * @since 4.3
     * @access protected
     */
    protected function _format_coupon_table_data() {
        $this->table_data = array_map(
            function ( $d ) {
            /* Translators: %s: Coupon usage total value */
            $d['usage_total']    = sprintf( _n( '%s use', '%s uses', $d['usage_total'], 'advanced-coupons-for-woocommerce-free' ), $d['usage_total'] );
            $d['discount_total'] = \ACFWF()->Helper_Functions->api_wc_price( $d['discount_total'] );
            return $d;
            },
            $this->raw_data
        );
    }
}
