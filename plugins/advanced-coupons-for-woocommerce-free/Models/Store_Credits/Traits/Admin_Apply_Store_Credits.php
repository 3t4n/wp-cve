<?php
namespace ACFWF\Models\Store_Credits\Traits;

use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Models\Objects\Store_Credit_Entry;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Trait that houses the logic of admin apply store credits.
 *
 * @since 4.5.9
 */
trait Admin_Apply_Store_Credits {
    /*
    |--------------------------------------------------------------------------
    | Apply Store Credits in pending order
    |--------------------------------------------------------------------------
     */

    /**
     * Display the apply store credits discount in the edit order page.
     *
     * @since 4.5.4
     * @access public
     *
     * @param \WC_Order $order Order object.
     */
    public function display_apply_store_credits_discount_in_edit_order_page( $order ) {

        $allowed_statuses = apply_filters( 'acfwf_apply_store_credits_order_allowed_statuses', array( 'pending', 'on-hold', 'checkout-draft' ) );

        if ( ! in_array( $order->get_status(), $allowed_statuses, true ) || // Skip if order status is not allowed.
            ! $order->get_customer_id() ) { // Skip if order has no customer.
            return;
        }

        $btn_text = __( 'Apply Store Credits', 'advanced-coupons-for-woocommerce-free' );

        if ( $order->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_PAID, true ) ||
            $this->_helper_functions->get_order_applied_coupon_item_by_code( \ACFWF()->Store_Credits_Checkout->get_store_credit_coupon_code(), $order ) ) {
            $btn_text = __( 'Update Store Credits', 'advanced-coupons-for-woocommerce-free' );
        }

        echo wp_kses_post(
            sprintf(
                '<button type="button" class="button acfw-apply-store-credits">%s</button>',
                $btn_text
            )
        );
    }

    /**
     * Apply store credits to order.
     *
     * @since 4.5.4
     * @access public
     *
     * @param \WC_Order $order Order object.
     * @param float     $amount Store credits amount.
     * @throws \Exception When store credits amount is invalid.
     */
    public function apply_store_credits_to_order( $order, $amount ) {

        $balance = \ACFWF()->Store_Credits_Calculate->get_customer_balance( $order->get_customer_id(), true );

        // skip when either of the following is true: amount is zero, order total is zero or when customer has insufficient store credits balance.
        if ( 0 >= $amount || 0 >= $order->get_total() || $amount > $balance ) {
            throw new \Exception( wp_kses_post( __( 'Store credits amount is invalid.', 'advanced-coupons-for-woocommerce-free' ) ) );
        }

        $amount  = min( $amount, $order->get_total( 'edit' ) );
        $sc_data = array(
            'amount'     => $amount, // user currency based amount.
            'raw_amount' => apply_filters( 'acfw_filter_amount', $amount, true, array( 'user_currency' => $order->get_currency() ) ), // site currency based amount.
            'cart_total' => $order->get_total( 'edit' ),
            'currency'   => $order->get_currency(),
        );

        if ( 'coupon' === get_option( Plugin_Constants::STORE_CREDIT_APPLY_TYPE, 'coupon' ) ) { // Apply store credit discount as coupon.

            // set the store credit discount data to our override function.
            \ACFWF()->Store_Credits_Checkout->set_coupon_override_data( $sc_data );

            $coupon_code = \ACFWF()->Store_Credits_Checkout->get_store_credit_coupon_code();

            /**
             * The coupon object instance created here will be overrided so its data will now have the amount set
             * in the window prompt. This is done via the `woocommerce_get_shop_coupon_data` filter.
             *
             * For more info, see the function `\ACFWF\Models\Store_Credits\Checkout::override_store_credit_coupon_data`.
             */
            $coupon = new \WC_Coupon( $coupon_code );

            // apply the store credit coupon to the order.
            $order->apply_coupon( $coupon );

        } else { // Apply store credit discount after tax.

            // save store credit metadata to order.
            $order->update_meta_data( Plugin_Constants::STORE_CREDITS_ORDER_PAID, $sc_data );
            $order->save_meta_data();
        }

        $order->save();

        // recalculate order totals so the store credit payment is deducted from the order total.
        $order->calculate_totals( true );

        // Create the store credit entry.
        $store_credit_entry = \ACFWF()->Store_Credits_Checkout->create_discount_store_credit_entry( $amount, $order );

        // update users cached balance value.
        $new_balance = \ACFWF()->Store_Credits_Calculate->get_customer_balance( $order->get_customer_id(), true );

        do_action( 'acfw_after_apply_store_credits_to_order', $amount, $new_balance, $order, $store_credit_entry );
    }

    /**
     * Update the store credits amount applied in the order.
     *
     * @since 4.5.5
     * @access public
     *
     * @param \WC_Order $order       Order object.
     * @param float     $amount      Store credits amount.
     * @param float     $sc_discount Store credits discount applied in the order.
     * @throws \Exception When store credits amount is invalid.
     */
    public function update_store_credits_in_order( $order, $amount, $sc_discount ) {

        $balance = \ACFWF()->Store_Credits_Calculate->get_customer_balance( $order->get_customer_id(), true );

        // skip when either of the following is true: amount is zero, order total is zero or when customer has insufficient store credits balance.
        if ( 0 >= ( $order->get_total( 'edit' ) + $sc_discount ) || $amount > ( $balance + $sc_discount ) ) {
            throw new \Exception( wp_kses_post( __( 'Store credits amount is invalid.', 'advanced-coupons-for-woocommerce-free' ) ) );
        }

        $store_credit_entry = $this->_queries->query_single_store_credit_entry(
            array(
                'type'      => 'decrease',
                'action'    => 'discount',
                'object_id' => $order->get_id(),
            )
        );

        if ( ! $store_credit_entry instanceof Store_Credit_Entry ) {
            throw new \Exception( wp_kses_post( __( "The order doesn't have a valid store credit discount applied.", 'advanced-coupons-for-woocommerce-free' ) ) );
        }

        if ( 0 >= $amount ) {
            $this->_delete_store_credits_from_order( $order, $store_credit_entry );
            return;
        }

        $amount  = min( $amount, $order->get_total( 'edit' ) + $sc_discount );
        $sc_data = array(
            'amount'     => $amount, // user currency based amount.
            'raw_amount' => apply_filters( 'acfw_filter_amount', $amount, true, array( 'user_currency' => $order->get_currency() ) ), // site currency based amount.
            'cart_total' => $order->get_total( 'edit' ),
            'currency'   => $order->get_currency(),
        );

        // check if order has coupon applied called 'store credits'.
        $store_credit_coupon = $this->_helper_functions->get_order_applied_coupon_item_by_code( \ACFWF()->Store_Credits_Checkout->get_store_credit_coupon_code(), $order );

        // update store credit coupon amount (before tax type).
        if ( $store_credit_coupon instanceof \WC_Order_Item_Coupon ) {

            // update store credit coupon data.
            $coupon_data           = $store_credit_coupon->get_meta( 'coupon_data', true );
            $coupon_data['amount'] = $amount;

            // update coupon item amount.
            $store_credit_coupon->update_meta_data( 'coupon_data', $coupon_data );
            $store_credit_coupon->set_discount( $amount );
            $store_credit_coupon->save();

            $order->add_item( $store_credit_coupon );
            $order->save();

            // recalculate discounts for the order.
            $order->recalculate_coupons();

        } else { // update store credits payment amount (after tax type).

            // save store credit metadata to order.
            $order->update_meta_data( Plugin_Constants::STORE_CREDITS_ORDER_PAID, $sc_data );
            $order->save_meta_data();

            // recalculate order totals so the store credit payment is deducted from the order total.
            $order->calculate_totals( true );
        }

        // Update store credit entry amount.
        $store_credit_entry->set_prop( 'amount', $amount );
        $store_credit_entry->save();

        // update users cached balance value.
        $new_balance = \ACFWF()->Store_Credits_Calculate->get_customer_balance( $order->get_customer_id(), true );

        do_action( 'acfw_after_update_store_credits_to_order', $amount, $new_balance, $order, $store_credit_entry );
    }

    /**
     * Delete the store credits amount applied in the order.
     *
     * @since 4.5.5
     * @access private
     *
     * @param \WC_Order          $order              Order object.
     * @param Store_Credit_Entry $store_credit_entry Store credit entry object.
     */
    private function _delete_store_credits_from_order( $order, $store_credit_entry ) {

        // check if order has coupon applied called 'store credits'.
        $store_credit_coupon = $this->_helper_functions->get_order_applied_coupon_item_by_code( \ACFWF()->Store_Credits_Checkout->get_store_credit_coupon_code(), $order );

        // delete store credit discount data from order.
        if ( $store_credit_coupon instanceof \WC_Order_Item_Coupon ) {
            $order->remove_item( $store_credit_coupon->get_id() );
            $order->recalculate_coupons();
            $store_credit_coupon->delete();
        } else {
            $order->delete_meta_data( Plugin_Constants::STORE_CREDITS_ORDER_PAID );
        }

        $order->calculate_totals( true );

        // delete store credit entry.
        $store_credit_entry->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Functions
    |--------------------------------------------------------------------------
     */

    /**
     * AJAX Apply store credits to order.
     *
     * @since 4.5.4
     * @access public
     *
     * @throws \Exception When invalid request.
     */
    public function ajax_apply_store_credits_to_order() {
        check_ajax_referer( 'order-item', 'security' );

        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die( -1 );
        }

        $response = array();

        try {

            if ( ! isset( $_POST['order_id'] ) || ! isset( $_POST['amount'] ) ) {
                throw new \Exception( __( 'Invalid request.', 'advanced-coupons-for-woocommerce-free' ) );
            }

            $order_id    = absint( $_POST['order_id'] );
            $amount      = (float) wc_format_decimal( wp_unslash( $_POST['amount'] ) );
            $order       = \wc_get_order( $order_id );
            $sc_discount = \ACFWF()->Store_Credits_Calculate->get_total_store_credits_discount_for_order( $order->get_id() );

            if ( 0 < $sc_discount ) {
                $this->update_store_credits_in_order( $order, $amount, $sc_discount );
            } else {
                $this->apply_store_credits_to_order( $order, $amount );
            }

            ob_start();
            include WC_ABSPATH . 'includes/admin/meta-boxes/views/html-order-items.php';
            $response['html'] = ob_get_clean();

        } catch ( \Exception $e ) {
            wp_send_json_error( array( 'error' => $e->getMessage() ) );
        }

        wp_send_json_success( $response );
    }
}
