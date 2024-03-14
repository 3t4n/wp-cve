<?php

namespace ACFWF\Models\Objects\Report_Widgets;

use ACFWF\Abstracts\Abstract_Report_Widget;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Discounted order revenue report widget data.
 *
 * @since 4.3
 */
class Discounted_Order_Revenue extends Abstract_Report_Widget {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new Report Widget object.
     *
     * @since 4.3
     * @access public
     *
     * @param Date_Period_Range $report_period Date period range object.
     */
    public function __construct( $report_period ) {
        $this->key         = 'discounted_order_revenue';
        $this->widget_name = __( 'Discounted Order Revenue', 'advanced-coupons-for-woocommerce-free' );
        $this->type        = 'big_number';
        $this->description = __( 'Discounted Order Revenue', 'advanced-coupons-for-woocommerce-free' );

        // build report data.
        parent::__construct( $report_period );
    }

    /*
    |--------------------------------------------------------------------------
    | Query methods
    |--------------------------------------------------------------------------
    */

    /**
     * Query report data freshly from the database.
     *
     * @since 4.3
     * @access protected
     */
    protected function _query_report_data() {
        $orders        = $this->_query_orders();
        $total_revenue = wc_add_number_precision( 0.0 );

        // Get total order revenue.
        foreach ( $orders as $order ) {
            // Skip if order has no coupons applied.
            if ( empty( $order->get_coupons() ) ) {
                continue;
            }

            // Get order total.
            $order_total    = apply_filters( 'acfw_filter_amount', floatval( $order->get_total() ), true, array( 'user_currency' => $order->get_currency() ) );
            $order_total    = apply_filters( 'acfw_query_report_data_order_total', $order_total, $order );
            $total_revenue += wc_add_number_precision( $order_total );
        }

        $this->raw_data = wc_remove_number_precision( $total_revenue );
    }

    /*
    |--------------------------------------------------------------------------
    | Utility methods
    |--------------------------------------------------------------------------
     */

    /**
     * NOTE: This method needs to be override on the child class.
     *
     * @since 4.3
     * @access public
     */
    protected function _format_report_data() {
        $this->title = $this->_format_price( $this->raw_data );
    }
}
