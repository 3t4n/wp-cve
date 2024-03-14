<?php

namespace ACFWF\Models\Objects\Report_Widgets;

use ACFWF\Abstracts\Abstract_Report_Widget;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Amounts discounted report widget data.
 *
 * @since 4.3
 */
class Amount_Discounted extends Abstract_Report_Widget {
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
        $this->key         = 'amount_discounted';
        $this->widget_name = __( 'Amount Discounted', 'advanced-coupons-for-woocommerce-free' );
        $this->type        = 'big_number';
        $this->description = __( 'Amount Discounted', 'advanced-coupons-for-woocommerce-free' );

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
     * @since 4.5.1 Add support for BOGO, Add Products and Shipping overrides discounts.
     * @since 4.5.6 Refactor query so it is valid for HPOS.
     * @access protected
     */
    protected function _query_report_data() {
        $orders         = $this->_query_orders();
        $total_discount = wc_add_number_precision( 0.0 );

        /**
         * Get total discounts.
         * - The formula to get total discounts is : discount + discount_tax + extra_discount.
         */
        foreach ( $orders as $order ) {
            foreach ( $order->get_coupons() as $item ) {
                $discount        = apply_filters( 'acfw_query_report_get_discount', $item->get_discount(), $item, $order );
                $discount_tax    = apply_filters( 'acfw_query_report_get_discount_tax', $item->get_discount_tax(), $item, $order );
                $extra_discount  = apply_filters( 'acfw_query_report_extra_discount', \ACFWF()->Helper_Functions->get_coupon_order_item_extra_discounts( $item ), $item, $order );
                $total_discount += wc_add_number_precision( $discount ) + wc_add_number_precision( $discount_tax ) + $extra_discount;
            }
        }

        $this->raw_data = wc_remove_number_precision( $total_discount );
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
