<?php

namespace Woo_MP;

use YeEasyAdminNotices\V1\AdminNotice;

defined( 'ABSPATH' ) || die;

/**
 * Payment processor.
 *
 * The actual processing is delegated to the active payment gateway.
 */
class Payment_Processor extends Transaction_Processor {

    /**
     * Process a payment.
     *
     * @param  array $params The following payment parameters are required:
     *
     * [
     *     'amount'   => 0,
     *     'currency' => '',
     *     'last_4'   => ''  // Last four digits of card number.
     * ]
     *
     * See payment gateway's payment processor for gateway-specific parameters.
     *
     * @see Transaction_Processor::process() For parameters required for all transaction processors.
     *
     * @return array         Data returned by the gateway plus transaction parameters.
     */
    public function process( $params ) {
        $params = [
            'order'       => new Woo_MP_Order( wc_get_order( $params['order_id'] ) ),
            'amount'      => number_format( (float) $params['amount'], 2, '.', '' ),
            'capture'     => get_option( 'woo_mp_capture_payments', 'yes' ) === 'yes',
            'description' => get_option( 'woo_mp_transaction_description', get_option( 'blogname', '' ) ),
        ] + $params;

        $charge = $this->payment_gateway->get_payment_processor()->process( $params ) + $params;

        if ( isset( $charge['trans_id'] ) ) {
            $this->do_success( $charge );
        }

        return $charge;
    }

    /**
     * Do whatever needs to be done after a successful transaction.
     *
     * @param array $charge Charge info:
     *
     * [
     *     'order_id'        => 0,
     *     'order'           => null,
     *     'trans_id'        => '',
     *     'amount'          => 0,
     *     'currency'        => '',
     *     'last_4'          => '',
     *     'capture'         => false,
     *     'held_for_review' => false,
     * ]
     *
     * @return void
     */
    private function do_success( $charge ) {
        $this->add_charge_note( $charge );
        $this->save_charge( $charge );
        $this->update_status( $charge );
        $this->reduce_stock( $charge );

        do_action( 'woo_mp_payment_complete', $charge['order']->get_core_order() );

        $message = $charge['capture'] ? 'Payment successfully processed.' : 'Payment successfully authorized.';

        if ( $charge['held_for_review'] ) {
            $message = 'Payment held for review.';
        }

        AdminNotice::create( 'message' )
            ->dismissible()
            ->success( wp_kses_post( $message ) )
            ->showOnNextPage();
    }

    /**
     * Add a note to the order.
     *
     * @param  array $charge Charge info. See {@see Payment_Processor::do_success()} for format.
     * @return void
     */
    private function add_charge_note( $charge ) {
        $amount = wc_price( $charge['amount'], [ 'currency' => $charge['currency'] ] );

        $action = $charge['held_for_review'] ? 'held for review' : ( $charge['capture'] ? 'processed' : 'authorized' );

        $note = sprintf( 'A %s payment was %s.', $amount, $action );

        $charge['order']->add_order_note( $note, 0, true );
    }

    /**
     * Save the charge.
     *
     * @param  array $charge Charge info. See {@see Payment_Processor::do_success()} for format.
     * @return void
     */
    private function save_charge( $charge ) {
        $charge['order']->add_woo_mp_payment( [
            'id'              => $charge['trans_id'],
            'last4'           => $charge['last_4'],
            'amount'          => $charge['amount'],
            'currency'        => $charge['currency'],
            'captured'        => $charge['capture'],
            'held_for_review' => $charge['held_for_review'],
        ] );

        $should_save_wc_payment = false;

        switch ( get_option( 'woo_mp_save_wc_payment_when', 'first_payment' ) ) {
            case 'first_payment':
                $should_save_wc_payment = ! $charge['order']->get_date_paid( 'edit' );
                break;
            case 'every_payment':
                $should_save_wc_payment = true;
                break;
        }

        if ( $should_save_wc_payment ) {
            $method_title = $this->payment_gateway->get_payment_method_title();

            $charge['order']->set_payment_method( $method_title );
            $charge['order']->set_payment_method_title( $method_title );
            $charge['order']->set_transaction_id( $charge['trans_id'] );
            $charge['order']->set_date_paid( time() );
        }

        $charge['order']->save();
    }

    /**
     * Update order status.
     *
     * @param  array $charge Charge info. See {@see Payment_Processor::do_success()} for format.
     * @return void
     */
    private function update_status( $charge ) {
        $update_order_status_when = get_option( 'woo_mp_update_order_status_when' );
        $update_order_status_to   = get_option( 'woo_mp_update_order_status_to', 'wc-completed' );

        $should_update_status = false;

        if ( $update_order_status_when === 'any_transaction' ) {
            $should_update_status = true;
        } elseif ( $update_order_status_when === 'total_amount_charged' ) {
            if ( $charge['order']->get_total_amount_unpaid() <= 0 ) {
                $should_update_status = true;
            }
        }

        if ( $should_update_status ) {

            // Patch https://github.com/woocommerce/woocommerce/issues/20057.
            if ( version_compare( WC_VERSION, '3.4.0', '<' ) ) {
                if ( ! $charge['order']->get_date_created( 'edit' ) ) {
                    $charge['order']->set_date_created( time() );
                }
            }

            $charge['order']->update_status( $update_order_status_to );
        }
    }

    /**
     * Reduce order item stock levels.
     *
     * @param  array $charge Charge info. See {@see Payment_Processor::do_success()} for format.
     * @return void
     */
    private function reduce_stock( $charge ) {
        switch ( get_option( 'woo_mp_reduce_stock_levels_when', 'any_charge' ) ) {
            case 'total_amount_charged':
                if ( $charge['order']->get_total_amount_unpaid() > 0 ) {
                    return;
                }

                break;
            case 'never':
                return;
        }

        wc_maybe_reduce_stock_levels( (int) $charge['order_id'] );
    }

}
