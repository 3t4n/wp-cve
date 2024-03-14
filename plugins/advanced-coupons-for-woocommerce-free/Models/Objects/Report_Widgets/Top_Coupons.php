<?php

namespace ACFWF\Models\Objects\Report_Widgets;

use ACFWF\Abstracts\Abstract_Coupons_Report_Widget;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Top coupons report widget data.
 *
 * @since 4.3
 */
class Top_Coupons extends Abstract_Coupons_Report_Widget {
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
        $this->key         = 'top_coupons';
        $this->widget_name = __( 'Top Coupons', 'advanced-coupons-for-woocommerce-free' );
        $this->type        = 'table';
        $this->title       = __( 'Most Used Coupons', 'advanced-coupons-for-woocommerce-free' );

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
        $results    = $this->_query_coupons_table_data();
        $usage      = $this->_calculate_usage_per_coupon( $results );
        $discounted = $this->_calculate_discount_total_per_coupon( $results );

        // sort usage count descendingly.
        arsort( $usage, SORT_NUMERIC );

        // Transform result into coupons.
        $coupons = array();
        foreach ( $results as $row ) {
            $coupons[ $row['ID'] ] = $row['code'];
        }

        /**
         * Prepare data for response.
         * - The response data should unique by coupon code.
         */
        $data = array();
        foreach ( $usage as $coupon_id => $count ) {
            // Coupon data.
            $coupon_code = $coupons[ $coupon_id ];
            $coupon      = array(
                'id'             => absint( $coupon_id ),
                'coupon'         => $coupon_code,
                'usage_total'    => $count,
                'discount_total' => $discounted[ $coupon_id ] ?? 0.0,
            );

            // If coupon data exists then combine it with the coupon data.
            if ( isset( $data[ $coupon_code ] ) ) {
                $existing_coupon           = $data[ $coupon_code ];
                $coupon['usage_total']    += $existing_coupon['usage_total'];
                $coupon['discount_total'] += $existing_coupon['discount_total'];
            }

            // Merge coupon data by coupon code.
            $data[ $coupon_code ] = $coupon;
        }

        $this->raw_data = array_values( $data );
    }

    /**
     * NOTE: This method needs to be override on the child class.
     *
     * @since 4.3
     * @access public
     */
    protected function _format_report_data() {
        $this->_format_coupon_table_data();
    }
}
