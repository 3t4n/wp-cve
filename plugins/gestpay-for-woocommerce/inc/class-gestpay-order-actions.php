<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2013-2016 Mauro Mascia (info@mauromascia.com)
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Handles S2S actions on orders.
 */
class Gestpay_Order_Actions {

    /**
     * Plugin actions.
     */
    public function __construct( $gestpay ) {

        $this->Gestpay = $gestpay;
        $this->Helper = $this->Gestpay->Helper;
    }

    /**
     * Perform a partial or complete transaction amount refund.
     *
     * @see http://api.gestpay.it/#callrefunds2s
     *
     * @param  int    $order_id
     * @param  float  $amount
     * @param  string $reason
     *
     * @return bool True or false based on success, or a WP_Error object
     */
    public function refund( $order_id, $amount = null, $reason = '' ) {

        if ( empty( $amount ) ) {
            return FALSE;
        }

        $order = wc_get_order( $order_id );

        $banktid = get_post_meta( $order_id, GESTPAY_ORDER_META_BANK_TID, TRUE );

        if ( ! $order || empty( $banktid ) ) {
            $this->log( $order, $this->Gestpay->strings['refund_err_1'] );
            return FALSE;
        }

        $client = $this->Helper->get_soap_client( $this->Gestpay->ws_S2S_url );
        if ( empty( $client ) ) {
            $this->log( $order, "Failed to load SOAP Client" );
            return FALSE;
        }

        // Define parameters for Refund
        $params = new stdClass();

        $params->shopLogin         = $this->Gestpay->shopLogin;
        $params->bankTransactionId = trim( $banktid );
        $params->shopTransactionId = $this->Helper->get_transaction_id( $order_id );
        $params->amount            = number_format( (float)$amount, 2, '.', '' );
        $params->uicCode           = $this->Helper->get_order_currency( $order );
        $params->RefundReason      = substr( $reason, 0, 50 );
        $params->chargeBackFraud   = 'N'; // can also be 'Y' but for now can't be specified on UI

        if ( ! empty( $this->Gestpay->apikey ) ) {
            $params->apikey = $this->Gestpay->apikey;
        }

        $this->Helper->log_add( '[CallRefundS2S REQUEST]: ', $params );

        // Do the request to refund the order
        try {
            $response = $client->CallRefundS2S( $params );

            $xml = simplexml_load_string( $response->callRefundS2SResult->any );

            do_action( 'gestpay_before_order_refund', $order, $xml );

            $this->Helper->log_add( '[CallRefundS2S RESPONSE]: ', $response );

            if ( (string)$xml->TransactionResult == "OK" ) {

                do_action( 'gestpay_order_refund_success', $order, $xml );

                $this->log( $order, sprintf( $this->Gestpay->strings['refund_ok'], $amount, $xml->BankTransactionID ) );
                return TRUE;
            }

            do_action( 'gestpay_order_refund_fail', $order, $xml );

            $resp_err = '[Error ' . $xml->ErrorCode . '] ' . $xml->ErrorDescription;
            $this->log( $order, $resp_err );
            return FALSE;
        }
        catch ( Exception $e ) {
            $this->log( $order, '[REFUND ERROR]: ' . $e->getMessage() );
            return FALSE;
        }
    }

    private function log( $order, $mess ) {

        $this->Helper->log_add( $mess );
        $order->add_order_note( $mess );
    }

    /**
     * Ajax Settle
     */
    public function ajax_settle() {

        ob_start();

        check_ajax_referer( 'order-item', 'security' );

        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die();
        }

        $order_id = absint( $_POST['order_id'] );

        $res = $this->settle( $order_id );

        if ( $res === TRUE ) {
            wp_send_json_success( array( 'status' => 'OK' ) );
        }
        else {
            wp_send_json_error( array( 'error' => $res ) );
        }

        // Clear transients
        wc_delete_shop_order_transients( $order_id );
    }

    /**
     * Financial confirmation of an authorized transaction.
     *
     * @see http://api.gestpay.it/#callsettles2s
     */
    public function settle( $order_id, $amount = false ) {

        $order = wc_get_order( $order_id );

        $client = $this->Helper->get_soap_client( $this->Gestpay->ws_S2S_url );
        if ( empty( $client ) ) {
            $this->log( $order, "Failed to load SOAP Client" );
            return FALSE;
        }

        $this->Helper->log_add( '[S2S Settle order_id]: ' . $order_id );

        try {
            $params = $this->get_CallSettleS2S_params( $order_id, $order, $amount );

            $this->Helper->log_add( '[CallSettleS2S REQUEST]: ', $params );

            $response = $client->CallSettleS2S( $params );

            $xml = simplexml_load_string( $response->callSettleS2SResult->any );

            $this->Helper->log_add( '[CallSettleS2S RESPONSE]: ', $xml );

            do_action( 'gestpay_before_order_settle', $order, $xml );

            if ( (string)$xml->TransactionResult == "OK" ) {
                $this->log( $order, 'Settle OK [BankTransactionID: ' . $xml->BankTransactionID . ']' );

                do_action( 'gestpay_order_settle_success', $order, $xml );

                return TRUE;
            }
            else {
                $resp_err = '[Error ' . $xml->ErrorCode . '] ' . $xml->ErrorDescription;

                do_action( 'gestpay_order_settle_fail', $order, $xml );

                return $resp_err;
            }
        }
        catch ( Exception $e ) {
            return $e->getMessage();
        }
    }

    /**
     * Deletes an authorized transaction.
     *
     * @see http://api.gestpay.it/#calldeletes2s
     */
    public function ajax_delete() {

        ob_start();

        check_ajax_referer( 'order-item', 'security' );

        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die();
        }

        $order_id = absint( $_POST['order_id'] );

        $client = $this->Helper->get_soap_client( $this->Gestpay->ws_S2S_url );
        if ( empty( $client ) ) {
            $this->log( $order, "Failed to load SOAP Client" );
            return FALSE;
        }

        $this->Helper->log_add( '[S2S Delete order_id]: ' . $order_id );

        try {
            // Validate that the settle can occur
            $order = wc_get_order( $order_id );

            $params = $this->get_CallDeleteS2S_params( $order_id, 'Transaction withdrawn manually.' );

            $this->Helper->log_add( '[CallDeleteS2S REQUEST]: ', $params );

            $response = $client->CallDeleteS2S( $params );

            $xml = simplexml_load_string( $response->callDeleteS2SResult->any );

            $this->Helper->log_add( '[CallDeleteS2S RESPONSE]: ', $xml );

            do_action( 'gestpay_before_order_delete', $order, $xml );

            if ( (string)$xml->TransactionResult == "OK" ) {

                $this->log( $order, sprintf( $this->Gestpay->strings['delete_ok'], $xml->BankTransactionID ) );

                do_action( 'gestpay_order_delete_success', $order, $xml );

                $order->update_status( 'cancelled', '' );

                wp_send_json_success( array( 'status' => 'OK' ) );
            }
            else {
                $resp_err = '[Error ' . $xml->ErrorCode . '] ' . $xml->ErrorDescription;

                do_action( 'gestpay_order_delete_fail', $order, $xml );

                wp_send_json_error( array( 'error' => $resp_err ) );
            }

            // Clear transients
            wc_delete_shop_order_transients( $order_id );
        }
        catch ( Exception $e ) {
            wp_send_json_error( array( 'error' => $e->getMessage() ) );
        }
    }

    /**
     * Prepare parameters for the CallReadTrxS2S
     */
    function get_CallReadTrxS2S_params( $order_id ) {

        $banktid = get_post_meta( $order_id, GESTPAY_ORDER_META_BANK_TID, TRUE );

        $params = new stdClass();
        $params->shopLogin = $this->Gestpay->shopLogin;

        // The transaction ID is unique so, lets check for that
        $params->shopTransactionId = $this->Helper->get_transaction_id( $order_id );
        $params->bankTransactionId = (int)trim( $banktid );

        if ( ! empty( $this->Gestpay->apikey ) ) {
            $params->apikey = $this->Gestpay->apikey;
        }

        return $params;
    }

    /**
     * Prepare parameters for the CallSettleS2S
     */
    function get_CallSettleS2S_params( $order_id, $order, $amount = false ) {

        $banktid = get_post_meta( $order_id, GESTPAY_ORDER_META_BANK_TID, TRUE );

        $params = new stdClass();

        if ( ! $amount ) {
            $amount = wc_format_decimal( $order->get_total(), wc_get_price_decimals() );
        }

        $params->shopLogin   = $this->Gestpay->shopLogin;
        $params->amount      = $amount;
        $params->uicCode     = $this->Helper->get_order_currency( $order );
        $params->bankTransID = (int)trim( $banktid );
        $params->shopTransID = $this->Helper->get_transaction_id( $order_id );
        //$params->FullFillment = ''; // Not used now.

        if ( ! empty( $this->Gestpay->apikey ) ) {
            $params->apikey = $this->Gestpay->apikey;
        }

        return $params;
    }

    /**
     * Prepare parameters for the CallDeleteS2S
     */
    function get_CallDeleteS2S_params( $order_id, $msg ) {

        $banktid = get_post_meta( $order_id, GESTPAY_ORDER_META_BANK_TID, TRUE );

        $params = new stdClass();

        $params->shopLogin         = $this->Gestpay->shopLogin;
        $params->bankTransactionId = (int)trim( $banktid );
        $params->shopTransactionId = $this->Helper->get_transaction_id( $order_id );
        $params->CancelReason      = $msg;

        if ( ! empty( $this->Gestpay->apikey ) ) {
            $params->apikey = $this->Gestpay->apikey;
        }

        return $params;
    }

    /**
     * #2020-07
     * This hook is fired when the status of an order is changed manually or with a bulk edit.
     * If the Gestpay plugin is configured to handle the separation between authorization and financial transaction
     * we need to handle the financial transaction if the payment was authorized and the order status is manually changed.
     */
    function wc_order_edit_status( $order_id, $new_status ) {

        if ( ! $this->Gestpay->is_moto_sep ) {
            // This happens when the merchant is using the MOTO option as "Unify transaction".
            // The order is completed and there is a financial transaction when it gets paid
            // so there aren't more actions to be performed.
            return;
        }

        $pre = '[wc_order_edit_status - Order ID ' . $order_id . ' new status ' . $new_status . '] ';

        $this->Helper->log_add( $pre."Start checks..." );

        // If we are here, there is separation between authorization and financial transaction
        // so we need to handle the order status changed manually.
        // We first need to check if the transaction is authorized, see:
        // https://docs.gestpay.it/soap/s2s/query-transaction-status/

        $order = wc_get_order( $order_id );
        $banktid = get_post_meta( $order_id, GESTPAY_ORDER_META_BANK_TID, TRUE );

        // Get the Axerve S2S SOAP Client
        $client = $this->Helper->get_soap_client( $this->Gestpay->ws_S2S_url );
        if ( empty( $client ) ) {
            $this->log( $order, "Failed to load SOAP Client" );
            return FALSE;
        }

        try {
            $params = $this->get_CallReadTrxS2S_params( $order_id );
            $response = $client->CallReadTrxS2S( $params );
            $xml_response = simplexml_load_string( $response->callReadTrxS2SResult->any );

            if ( $xml_response->TransactionResult != "OK" ) {
                $resp_err = '[Error ' . $xml_response->ErrorCode . '] ' . $xml_response->ErrorDescription;
                $this->Helper->log_add( $pre.$resp_err );
                $order->add_order_note( $resp_err );
                return; // Nothing to do
            }

            if ( empty( $xml_response->TransactionState ) ) {
                $resp_err = '[Error CallReadTrxS2S] TransactionState non disponibile.';
                $this->Helper->log_add( $pre.$resp_err );
                $this->Helper->log_add( '[CallReadTrxS2S RESPONSE]: ', $xml_response );
                $order->add_order_note( $resp_err );
                return; // Nothing to do
            }
            else {
                $tx_state = $xml_response->TransactionState;
            }

            $bank_tx_id = $xml_response->BankTransactionID;

            if ( $bank_tx_id != $banktid ) {
                $msg = "FAILED to check BankTransactionID: was saved as " . $banktid . " in the order, got: " . $bank_tx_id . " from CallReadTrxS2S";
                $this->Helper->log_add( $pre.$msg );
                $this->Helper->log_add( '[CallReadTrxS2S RESPONSE]: ', $xml_response );
                $order->add_order_note( $msg );
                return; // Nothing to do
            }

            $this->Helper->log_add( $pre."TX State: ".$tx_state );

            if ( $tx_state == 'AUT' && in_array( $new_status, array( 'processing', 'completed' ) ) ) {
                // Transaction was authorized, the order has been manually completed
                // so we can do a financial transaction (MOV)

                $params = $this->get_CallSettleS2S_params( $order_id, $order );

                if ( empty( $params->amount ) ) {
                    $msg = "FAILED: amount is empty";
                    $this->Helper->log_add( $pre.$msg );
                    $this->Helper->log_add( '[CallReadTrxS2S RESPONSE]: ', $xml_response );
                    $order->add_order_note( $msg );
                    return;
                }

                $settle_response = $client->CallSettleS2S( $params );
                $s_xml = simplexml_load_string( $settle_response->callSettleS2SResult->any );

                if ( (string)$s_xml->TransactionResult == "OK" ) {
                    $msg = 'Movimentazione manuale OK [BankTransactionID: ' . $s_xml->BankTransactionID . ']';
                    $this->Helper->log_add( $pre.$msg );
                    $order->add_order_note( $msg );
                    do_action( 'gestpay_order_settle_success', $order, $s_xml );
                }
                else {
                    $resp_err = '[Error ' . $s_xml->ErrorCode . '] ' . $s_xml->ErrorDescription;
                    $this->Helper->log_add( $pre.$resp_err );
                    $this->Helper->log_add( '[CallReadTrxS2S RESPONSE]: ', $xml_response );
                    $this->Helper->log_add( '[CallSettleS2S RESPONSE]: ', $s_xml );
                    $order->add_order_note( $resp_err );
                    do_action( 'gestpay_order_settle_fail', $order, $s_xml );
                }
            }
            elseif ( $tx_state == 'AUT' && in_array( $new_status, array( 'cancelled', 'failed' ) ) ) {
                // Transaction was authorized, the order has been manually set to cancelled or failed
                // so we can Delete the transaction

                $params = $this->get_CallDeleteS2S_params( $order_id, 'Transazione annullata manualmente' );
                $del_response = $client->CallDeleteS2S( $params );
                $d_xml = simplexml_load_string( $del_response->callDeleteS2SResult->any );

                if ( (string)$d_xml->TransactionResult == "OK" ) {
                    $msg = 'Cancellazione manuale OK [BankTransactionID: ' . $d_xml->BankTransactionID . ']';
                    $this->Helper->log_add( $pre.$msg );
                    $order->add_order_note( $msg );
                    do_action( 'gestpay_order_delete_success', $order, $d_xml );
                }
                else {
                    $resp_err = '[Error ' . $d_xml->ErrorCode . '] ' . $d_xml->ErrorDescription;
                    $resp_err.= ' - Verifica la transazione nel backoffice Gestpay e gestisci manualmente questo caso.';
                    $this->Helper->log_add( $pre.$resp_err );
                    $this->Helper->log_add( '[CallReadTrxS2S RESPONSE]: ', $xml_response );
                    $this->Helper->log_add( '[CallDeleteS2S RESPONSE]: ', $d_xml );
                    $order->add_order_note( $resp_err );
                    do_action( 'gestpay_order_delete_fail', $order, $d_xml );
                }
            }
            else {
                // If $tx_state == 'MOV' the transaction can't be deleted and can't be settled again (if,
                // for example, the merchant changes a completed order into pending and into completed again).
                // The only way to change an already completed transaction is the refund
                // but it is better that the merchant handle these cases manually.
                // If $tx_state == 'STO' and the remaning amount is 0, there are no more actions that can be done;
                // only for a partial refund it is possibile to change the state to MOV (if it wasn't MOV again)
                // but, again, it is better to handle these cases manually.

                if ( $tx_state == 'MOV' && in_array( $new_status, array( 'processing', 'completed' ) ) ) {
                    // do nothing.
                    return;
                }

                $resp_err = "[".$tx_state." -> ".$new_status."] Operazione attualmente non supportata: è necessario gestire manualmente questo caso.";
                $this->Helper->log_add( $pre.$resp_err );
                $order->add_order_note( $resp_err );
            }
        }
        catch ( Exception $e ) {
            $this->Helper->log_add( '[ERROR CallReadTrxS2S]: ' . $e->getMessage() );
        }
    }
}


/**
 * Adds the Gestpay buttons in the order actions secions, after the Refund button.
 * Also adds the javascript necessary to invoke the ajax actions.
 */
function gestpay_order_actions_add_action_buttons( $order ) {

    // Check if the order is paid and is paid with Gestpay, otherwise we don't need these buttons.
    $pm = get_post_meta( $order->get_id(), '_payment_method', TRUE );
    $is_moto_sep = "yes" === get_option( 'wc_gateway_gestpay_moto_sep' );
    if ( ! in_array( $pm, array( 'wc_gateway_gestpay', 'wc_gateway_gestpay_paypal' ) ) || ! $is_moto_sep ) {
        return;
    }

    $gp_strings = include 'translatable-strings.php';
    ?>

    <button type="button" class="button gestpay-settle-items"><?php echo $gp_strings['button_settle']; ?>
        <?php echo wc_help_tip( $gp_strings['tip_settle'] ); ?>
    </button>

    <button type="button" class="button gestpay-delete-items"><?php echo $gp_strings['button_delete']; ?>
        <?php echo wc_help_tip( $gp_strings['tip_delete'] ); ?>
    </button>

    <script>
    (function($) {

        function gestpay_ajax_call( action, data ) {

            var data = {
                action:   action,
                order_id: woocommerce_admin_meta_boxes.post_id,
                security: woocommerce_admin_meta_boxes.order_item_nonce
            };

            $.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {

                if ( typeof response.data == 'undefined' ) {
                    window.alert( 'An error occours' );
                }
                else if ( true === response.success && 'OK' === response.data.status ) {
                    // Redirect to same page for show the refunded status
                    window.location.href = window.location.href;
                    return;
                }
                else {
                    window.alert( response.data.error );
                }
            });

        }

        $( '#woocommerce-order-items' )
            .on( 'click', 'button.gestpay-settle-items', function() {
                if ( window.confirm( "<?php echo $gp_strings['confirm_settle']; ?>" ) ) {
                    gestpay_ajax_call( 'gestpay_settle_s2s' );
                }
            })
            .on( 'click', 'button.gestpay-delete-items', function() {
                if ( window.confirm( "<?php echo $gp_strings['confirm_delete']; ?>" ) ) {
                    gestpay_ajax_call( 'gestpay_delete_s2s' );
                }
            });

    })(jQuery)
    </script>

    <?php
}
// Add externally to the class to prevent multiple loadings.
add_action( 'woocommerce_order_item_add_action_buttons', 'gestpay_order_actions_add_action_buttons' );

