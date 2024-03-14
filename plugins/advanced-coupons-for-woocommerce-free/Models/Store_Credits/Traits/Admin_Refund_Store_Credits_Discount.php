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
trait Admin_Refund_Store_Credits_Discount {
    /*
    |--------------------------------------------------------------------------
    | Refund store credits discount/payment
    |--------------------------------------------------------------------------
     */

    /**
     * Display the "Refund store credits discount" button in the order actions row.
     *
     * @since 4.5.9
     * @access public
     *
     * @param \WC_Order $order Order object.
     */
    public function display_refund_store_credits_discount_button( $order ) {
        $allowed_statuses = apply_filters( 'acfwf_refund_store_credits_allowed_statuses', array( 'refunded' ) );

        if ( ! in_array( $order->get_status(), $allowed_statuses, true ) || // Skip if order status is not allowed.
            ! $order->get_customer_id() ) { // Skip if order has no customer.
            return;
        }

        // Skip if the refunded order doesn't have any store credits discount/payment applied.
        if ( ! $order->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_PAID, true ) &&
            ! $this->_helper_functions->get_order_applied_coupon_item_by_code( \ACFWF()->Store_Credits_Checkout->get_store_credit_coupon_code(), $order ) ) {
            return;
        }

        $sc_discount  = \ACFWF()->Store_Credits_Calculate->get_total_store_credits_discount_for_order( $order->get_id() );
        $sc_discount -= \ACFWF()->Store_Credits_Calculate->get_total_refunded_store_credits_discount_for_order( $order ); // Deduct the discount amount that has already been refunded.

        // Skip if the store credits discount is already fully refunded.
        if ( 0 >= $sc_discount ) {
            return;
        }

        // Translators: %s: Available applied store credits amount to be refunded.
        $prompt_message = __( 'Enter the amount of store credits discount to refund. Available amount: %s', 'advanced-coupons-for-woocommerce-free' );

        echo wp_kses_post(
            sprintf(
                '<button type="button" class="button acfw-refund-store-credits" data-prompt="%s">%s</button>',
                sprintf( $prompt_message, $this->_helper_functions->api_wc_price( $sc_discount ) ),
                __( 'Refund store credits discount', 'advanced-coupons-for-woocommerce-free' )
            )
        );
    }

    /**
     * Display the refunded store credits discount summary.
     *
     * @since 4.5.9
     * @access public
     *
     * @param int $order_id Order ID.
     */
    public function display_refunded_store_credits_discount_summary( $order_id ) {
        $order    = wc_get_order( $order_id );
        $refunded = \ACFWF()->Store_Credits_Calculate->get_total_refunded_store_credits_discount_for_order( $order );

        // Skip if there is no refunded store credits discount amount.
        if ( 0 >= $refunded ) {
            return;
        }

        $sc_discount = \ACFWF()->Store_Credits_Calculate->get_total_store_credits_discount_for_order( $order->get_id() );
        $total       = $sc_discount - $refunded;

        include $this->_constants->VIEWS_ROOT_PATH . 'store-credits' . DIRECTORY_SEPARATOR . 'view-edit-refunded-store-credit-discount.php';
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Functions
    |--------------------------------------------------------------------------
     */

    /**
     * AJAX Refund store credits to order.
     *
     * @since 4.5.9
     * @access public
     *
     * @throws \Exception When invalid request.
     */
    public function ajax_refund_store_credits_discount_from_order() {
        check_ajax_referer( 'order-item', 'security' );

        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die( -1 );
        }

        $response = array();

        try {

            if ( ! isset( $_POST['order_id'] ) || ! isset( $_POST['amount'] ) ) {
                throw new \Exception( __( 'Invalid request.', 'advanced-coupons-for-woocommerce-free' ) );
            }

            $order_id     = absint( $_POST['order_id'] );
            $amount       = (float) wc_format_decimal( wp_unslash( $_POST['amount'] ) );
            $order        = \wc_get_order( $order_id );
            $sc_discount  = \ACFWF()->Store_Credits_Calculate->get_total_store_credits_discount_for_order( $order->get_id() );
            $sc_discount -= \ACFWF()->Store_Credits_Calculate->get_total_refunded_store_credits_discount_for_order( $order ); // Deduct the discount amount that has already been refunded.

            if ( ! $sc_discount || ! $amount || $amount > $sc_discount ) {
                throw new \Exception( __( 'The amount provided is invalid.', 'advanced-coupons-for-woocommerce-free' ) );
            }

            $store_credit_entry = new Store_Credit_Entry();

            $store_credit_entry->set_prop( 'amount', $amount );
            $store_credit_entry->set_prop( 'user_id', $order->get_customer_id() );
            $store_credit_entry->set_prop( 'object_id', $order->get_id() );
            $store_credit_entry->set_prop( 'type', 'increase' );
            $store_credit_entry->set_prop( 'action', 'refund' );

            $check = $store_credit_entry->save();

            if ( is_wp_error( $check ) ) {
                throw new \Exception( $check->get_error_message() );
            }

            $order->add_meta_data( Plugin_Constants::REFUND_STORE_CREDIT_DISCOUNT_ENTRY, $store_credit_entry->get_id() );
            $order->save_meta_data();

        } catch ( \Exception $e ) {
            wp_send_json_error( array( 'error' => $e->getMessage() ) );
        }

        wp_send_json_success( $response );
    }
}
