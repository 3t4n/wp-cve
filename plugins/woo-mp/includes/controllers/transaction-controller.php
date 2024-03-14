<?php

namespace Woo_MP\Controllers;

use Woo_MP\Woo_MP;
use Woo_MP\Payment_Gateways;
use Woo_MP\Payment_Processor;
use Woo_MP\Detailed_Exception;

defined( 'ABSPATH' ) || die;

/**
 * Transaction request controller.
 */
class Transaction_Controller {

    /**
     * Process a transaction.
     *
     * @return void
     */
    public function process_transaction() {
        check_ajax_referer( 'woo_mp_process_transaction_' . ( isset( $_REQUEST['order_id'] ) ? $_REQUEST['order_id'] : 0 ) );

        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die( 'Sorry, you are not allowed to process transactions for this order.', 403 );
        }

        if ( isset( $_REQUEST['gateway_id'] ) && $_REQUEST['gateway_id'] !== Payment_Gateways::get_active_id() ) {
            $this->respond(
                'error',
                'The active payment gateway has been switched.' .
                ' Please refresh the page and try again.'
            );
        }

        try {
            $data = $this->process( $_POST );

            $this->respond( 'success', '', null, $data );
        } catch ( Detailed_Exception $e ) {
            $this->respond( 'error', $e->getMessage(), $e->getCode(), $e->get_data() );
        } catch ( \Throwable $e ) {
            $this->respond( 'error', 'An error has occured: ' . $e, $e->getCode() );
        } catch ( \Exception $e ) {
            $this->respond( 'error', 'An error has occured: ' . $e, $e->getCode() );
        }

        die;
    }

    /**
     * Process a transaction using the relevant transaction processor.
     *
     * @param  array              $params Request parameters including 'transaction_type' and any
     *                                    other parameters needed by the transaction processor.
     * @return array                      Response from the transaction processor.
     * @throws Detailed_Exception         For detailed errors.
     */
    private function process( $params ) {
        $processors = [
            'charge' => Payment_Processor::class,
        ];

        if ( Woo_MP::is_pro() ) {
            $processors += [
                'refund' => \Woo_MP_Pro\Refund_Processor::class,
            ];
        }

        if ( empty( $params['transaction_type'] ) ) {
            throw new Detailed_Exception( "The 'transaction_type' parameter is required." );
        }

        if ( empty( $processors[ $params['transaction_type'] ] ) ) {
            throw new Detailed_Exception( "Transaction type '$params[transaction_type]' was not found." );
        }

        return ( new $processors[ $params['transaction_type'] ]() )->process( $params );
    }

    /**
     * Send response to client.
     *
     * @param  string $status  The status of the operation.
     * @param  string $message Optional message.
     * @param  mixed  $code    Optional code. Useful for errors.
     * @param  mixed  $data    Optional additional data.
     * @return void
     */
    private function respond( $status, $message = '', $code = null, $data = null ) {
        wp_send_json( [
            'status'  => $status,
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
        ] );
    }

}
