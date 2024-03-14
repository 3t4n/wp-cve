<?php

namespace Woo_MP\Controllers;

use Woo_MP\Woo_MP_Order;

defined( 'ABSPATH' ) || die;

/**
 * Controller for the charge amount autofill feature.
 */
class Charge_Amount_Autofill_Controller {

    /**
     * Get the amount of the order total that is unpaid.
     *
     * @return void
     */
    public function get_unpaid_order_balance() {
        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die( 'Sorry, you are not allowed to view the unpaid balance of this order.', 403 );
        }

        if ( empty( $_REQUEST['order_id'] ) ) {
            wp_send_json_error( "Field 'order_id' is required." );
        }

        if ( empty( $_REQUEST['currency'] ) ) {
            wp_send_json_error( "Field 'currency' is required." );
        }

        $wc_order = wc_get_order( $_REQUEST['order_id'] );

        if ( ! $wc_order ) {
            wp_send_json_error( "Order with ID '$_REQUEST[order_id]' not found." );
        }

        $order = new Woo_MP_Order( $wc_order );

        if ( $_REQUEST['currency'] !== $order->get_currency() ) {
            wp_send_json_success( [
                'title'  => 'We are unable to suggest a charge amount because the payment currency is not the same as the order currency.',
                'amount' => null,
            ] );
        }

        if ( $order->is_multicurrency() ) {
            wp_send_json_success( [
                'title'  => 'We are unable to suggest a charge amount because there have been multiple payments in different currencies.',
                'amount' => null,
            ] );
        }

        if ( (int) round( $order->get_total(), 2 ) === 0 ) {
            wp_send_json_success( [
                'title'  => 'We are unable to suggest a charge amount because there is no order total.',
                'amount' => null,
            ] );
        }

        $unpaid_balance = $order->get_total_amount_unpaid();

        if ( $unpaid_balance <= 0 ) {
            wp_send_json_success( [
                'title'  => 'The order is fully paid for.',
                'amount' => null,
            ] );
        }

        $title = 'Autofill order total (%amount%)';

        if ( $order->get_total_amount_paid() > 0 ) {
            $title = 'Autofill unpaid balance (%amount%)';
        }

        if ( $order->get_total_refunded() ) {
            $title .= ' – refunds are not factored into this calculation';
        }

        wp_send_json_success( [
            'title'  => $title,
            'amount' => number_format( $unpaid_balance, 2, '.', '' ),
        ] );
    }

}
