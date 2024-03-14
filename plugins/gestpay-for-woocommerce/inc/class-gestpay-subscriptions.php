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

class Gestpay_Subscriptions {

    public function __construct( $gestpay ) {

        // Get a pointer to the main class and to the helper.
        $this->Gestpay    = $gestpay;
        $this->Helper     = $this->Gestpay->Helper;
        $this->textdomain = $this->Gestpay->textdomain;

        // Used when scheduling a payment: some stuff must not be executed.
        $this->is_scheduled_payment = FALSE;

        $this->Cards = new Gestpay_Cards( $gestpay );
        $this->saved_cards = $this->Cards->get_cards();

        if ( $this->Helper->is_subscriptions_active() ) {
            /*
                NOTES

                Recurring payments can only be processed if the account has the 3D Secure disabled
                because the user can't insert the secure code when payments runs on background.
             */

            // process scheduled subscription payments
            add_action( 'woocommerce_scheduled_subscription_payment_wc_gateway_gestpay', array( $this, 'process_subscription_renewal_payment' ), 10, 2 );

            // display the current payment method used for a subscription in the "My Subscriptions" table
            add_filter( 'woocommerce_my_subscriptions_payment_method', array( $this, 'maybe_render_subscription_payment_method' ), 10, 2 );

            // action when cancelling a subscription
            // add_action( 'woocommerce_subscription_cancelled_wc_gateway_gestpay', array( $this, 'cancelled_subscription' ) );
            // add_action( 'woocommerce_subscription_failing_payment_method_updated_wc_gateway_gestpay', array( $this, 'update_failing_payment_method' ), 10, 2 );
        }
    }

    /**
     * Add card parameters from the card form.
     *
     * @param $params - Array of parameters on which append card informations
     */
    public function append_card_params( &$params ) {

        $params->cardNumber = $this->Helper->get_post( 'gestpay-cc-number' );
        $params->expiryMonth = $this->Helper->get_post( 'gestpay-cc-exp-month' );
        $params->expiryYear = $this->Helper->get_post( 'gestpay-cc-exp-year' );  // 2 digits

        if ( $this->Gestpay->is_cvv_required ) {
            $params->cvv = $this->Helper->get_post( 'gestpay-cc-cvv' );
        }
    }

    /**
     * Maybe use buyerName from the cardholder info
     *
     * @param $params - Array of parameters on which change buyer informations
     */
    public function maybe_use_buyer( &$params ) {

        if ( $this->Gestpay->param_buyer_name ) {
            $bn = $this->Helper->get_post( 'gestpay-cc-buyer-name' );
            if ( !empty( $bn ) ) {
                $params->buyerName = $bn;
            }
        }
    }

    /**
     * Maybe add a token request
     *
     * @param $params - Array of parameters
     */
    public function maybe_request_token( &$params, $order_id ) {

        if ( ! $this->Gestpay->save_token || empty( $order_id ) ) {
            return;
        }

        if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order_id ) ) {
            $subscription = wcs_get_subscription( $order_id );
            $order_id = $subscription->get_parent_id();
            $token = false;
        }
        else {
            $token = $this->Helper->get_order_token( $order_id );
            if ( ! empty( $token ) ) {
                // This is the case of the first payment made with a token already saved, so we don't need to request it again.
                return;
            }
        }

        $params->requestToken = "MASKEDPAN";
        $params->withAuth = $this->Gestpay->token_with_auth;
    }

    /**
     * Maybe save the card's token
     *
     * @param $xml_response - Array of response informations
     */
    public function maybe_save_token( $xml_response, $order_id ) {

        if ( ! $this->Gestpay->save_token ) {
            $this->Helper->log_add( '[OnSite maybe_save_token] TOKEN storage is disabled.' );
            return FALSE;
        }

        $response = array(
            'token' => (string) $xml_response->TOKEN,
            'month' => (int) $xml_response->TokenExpiryMonth,
            'year'  => (int) $xml_response->TokenExpiryYear
        );

        if ( empty( $response['token'] ) ) {
            $this->Helper->log_add( '[OnSite maybe_save_token] Token was not provided in the xml_response.' );
            return FALSE;
        }

        $this->Helper->set_order_token( $order_id, $response );

        // Maybe store also the card to the users cards
        $this->Cards->save_card( $response, $order_id );
    }

    /**
     * Check with if the transaction has been already paid.
     * This can be useful to prevent multiple payment for the same transaction.
     * @see also needs_payment()
     */
    private function has_been_already_paid( $client, $p ) {

        if ( defined( 'GESTPAY_S2S_ALREADY_PAID_API' ) && GESTPAY_S2S_ALREADY_PAID_API ) {
            $params = new stdClass();
            $params->shopLogin = $p->shopLogin;
            $params->shopTransactionId = $p->shopTransactionId;
            if ( !empty( $p->apikey ) ) $params->apikey = $p->apikey;

            try {
                $response = $client->CallReadTrxS2S( $params );
                $xml_response = simplexml_load_string( $response->callReadTrxS2SResult->any );

                if ( $xml_response->TransactionResult == "OK" ) {
                    return TRUE;
                }
            }
            catch ( Exception $e ) {
                $this->Helper->log_add( '[ERROR CallReadTrxS2S]: ' . $e->getMessage() );
            }
        }

        return FALSE;
    }


    /**
     * Do the payment through S2S
     */
    public function s2s_payment( $order, $args = array() ) {

        $order_id = $order->get_id();

        $client = $this->Helper->get_soap_client( $this->Gestpay->ws_S2S_url );
        if ( empty( $client ) ) {
            return FALSE;
        }

        // Maybe overwrite amount (for subscription)
        $override_amount = FALSE;
        if ( ! empty( $args['amount'] ) ) {
            $override_amount = $args['amount'];
        }

        // Set required parameters and add the Token to them
        $params = $this->Gestpay->get_base_params( $order, $override_amount, FALSE );

        // Maybe use another account without 3DS to process recurring payments
        // *** @deprecated - with 3DS 2.0 this is no longer useful, but is kept for compatibility ***
        if ( ! empty( $args['shopLoginRec'] ) ) {
            $params->shopLogin = $args['shopLoginRec'];

            if ( ! empty( $args['apikeyRec'] ) ) {
                $params->apikey = $args['apikeyRec'];
            }
        }

        if ( ! empty( $args['token'] ) ) {
            // S2S Payment Phase 1 with Token
            $params->tokenValue = $args['token'];

            // Maybe use buyerName from the card info
            $this->maybe_use_buyer( $params );
        }
        else {
            if ( ! empty( $args['pares'] ) ) {
                // S2S Payment Phase 3
                $params->transKey = get_post_meta( $order_id, GESTPAY_ORDER_META_TRANS_KEY, TRUE );
                $params->PARes = $args['pares'];
            }
            else {
                // S2S Payment Phase 1 without Token
                $this->append_card_params( $params );
                $this->maybe_use_buyer( $params );
            }

            $this->maybe_request_token( $params, $order_id );
        }

        // Maybe overwrite shopTransactionId (for subscription)
        if ( ! empty( $args['shopTransactionId'] ) ) {
            $params->shopTransactionId = $this->Helper->get_transaction_id( $args['shopTransactionId'] );
        }

        // Add another layer of check to prevent multiple payments.
        $has_been_already_paid = $this->has_been_already_paid( $client, $params );
        if ( $has_been_already_paid ) {
            $this->Helper->log_add( '[WARNING] Il pagamento per l\'ordine '. $order_id .' è stato interrotto perché è già stato pagato!' );
            return FALSE;
        }

        // Do the request to retrieve the pay
        try {
            Gestpay_3DS2::add_3ds2_params( $params, $order_id, 'WsS2S', $this->is_scheduled_payment );
            $this->Helper->log_add( '[s2s_payment]: Parameters:', $params );

            $response = $client->callPagamS2S( $params );
        }
        catch ( Exception $e ) {
            $err = sprintf( $this->Gestpay->strings['soap_req_error'], $e->getMessage() );
            if ( ! $this->is_scheduled_payment ) {
                $this->Helper->wc_add_error( $err );
            }
            $this->Helper->log_add( '[ERROR]: ' . $err );

            return FALSE;
        }

        $this->Helper->log_add( '[S2S RESPONSE]:', $response );

        $xml_response = simplexml_load_string( $response->callPagamS2SResult->any );

        Gestpay_3DS2::maybe_set_3DS2_metas( $xml_response, $order_id );

        if ( $xml_response->TransactionType == "PAGAM" && $xml_response->TransactionResult == "OK" ) {

            // --- Transactions made with non 3DS account (Phase 1) or with 3DS account (Phase 3) after pares confirmation
            $txn = $this->Helper->handle_transaction_details( $order, $order_id, $xml_response );

            if ( ! $this->is_scheduled_payment ) {

                $msg = sprintf( $this->Gestpay->strings['transaction_ok'], $order_id );

                $this->Helper->wc_order_completed( $order, $msg, $txn );

                // This will not save the token here if using 3DS account
                $this->maybe_save_token( $xml_response, $order_id );

                add_action( 'the_content', array( &$this, 'show_message' ) );
            }

            return array(
                'pay_result' => 'OK'
            );
        }
        elseif ( $xml_response->TransactionType == "PAGAM" && $xml_response->TransactionResult == "KO" ) {

            // --- Transactions made with 3D-Secure cards
            if ( $xml_response->ErrorCode == '8006' ) {

                // -- Phase I: authorization request OK
                if ( ! empty( $xml_response->TransactionKey ) ) {
                    // Store the Transaction Key, which will be used in the Phase 3
                    update_post_meta( $order_id, GESTPAY_ORDER_META_TRANS_KEY, (string)$xml_response->TransactionKey );
                }
                else {
                    $this->Helper->log_add( '[ATTENZIONE]: Impossibile ricevere la TransactionKey in fase di autorizzazione. Verificare che il parametro sia abilitato nella risposta' );
                }

                // This will save the token here if using 3DS account
                $this->maybe_save_token( $xml_response, $order_id );

                // -- Send to Phase 2
                return array(
                    'VbVRisp' => (string)$xml_response->VbV->VbVRisp,
                );
            }
            else {

                // -- Error
                $err = sprintf( $this->Gestpay->strings['payment_error'], $xml_response->ErrorCode, $xml_response->ErrorDescription );
                if ( ! $this->is_scheduled_payment ) {
                    $this->Helper->wc_add_error( $err );

                    // We are here because something get wrong in the first payment.
                    // If we saved the token let's cancel it.
                    delete_post_meta( $order_id, GESTPAY_META_TOKEN );
                }

                // Allow actors to add additional code after order is failed
                do_action( 'gestpay_after_s2s_order_failed', $order, $xml_response );

                $this->Helper->log_add( '[ERROR]: ' . $err );

                return array(
                    'pay_result' => 'KO',
                    'error'      => TRUE,
                    'error_code' => $xml_response->ErrorCode,
                    'error_desc' => $xml_response->ErrorDescription
                );
            }
        }
    }


    /**
     * Process subscription renewal
     *
     * Gestpay Pro with Tokenization have no support for recurring payments, but it allows to charge
     * a stored credit card (using a masked token) if and only if 3D-Secure is NOT enabled.
     *
     * For each subscription, a `woocommerce_scheduled_subscription_payment_{payment_gateway_id}`
     * hook is fired whenever a payment is due, so we can hook on it to charge the next payment.
     *
     * @see
     * - http://docs.woothemes.com/document/subscriptions/develop/payment-gateway-integration/
     * - https://docs.woocommerce.com/document/testing-subscription-renewal-payments/
     *
     * @param float $amount_to_charge
     *      subscription amount to charge: could include multiple renewals if they've previously failed and the admin has enabled it
     * @param \WC_Order $renewal_order
     *      original order containing the subscription
     */
    public function process_subscription_renewal_payment( $amount_to_charge, $renewal_order ) {

        // WARNING! Be sure the class invoker is the same payment gateway set on the renewal order!
        if ( $renewal_order->get_payment_method() != $this->Gestpay->id ) {
            return FALSE;
        }

        $renewal_order_id = $renewal_order->get_id();

        // Be sure to process a renewal order.
        if ( ! wcs_order_contains_renewal( $renewal_order_id ) ) {
            $err = 'Not a renewal order.';
            $this->renewal_payment_failure( $renewal_order, $err );
        }

        // NEVER process an already paid order!
        if ( ! $renewal_order->needs_payment() ) {
            // This should be enough but we also added another check @see has_been_already_paid()
            return FALSE;
        }

        if ( ! $this->Gestpay->save_token ) {
            $err = 'Tokens are disabled but they must be enabled to process subscriptions.';
            $this->renewal_payment_failure( $renewal_order, $err );
        }

        if ( wcs_order_contains_renewal( $renewal_order_id ) ) {
            $parent_order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $renewal_order_id );
        }

        if ( empty( $parent_order_id ) ) {
            $err = 'Failed to retrieve parent order ID while processing a subscription renewal payment.';
            $this->renewal_payment_failure( $renewal_order, $err );
        }
        else {
            $this->Helper->log_add( '=========== processing subscription renewal payment (parent order #' . $parent_order_id . ')' );
        }

        // Get the token from the parent order (if any, else fail)
        $token = $this->Helper->get_order_token( $parent_order_id, true );
        if ( empty( $token ) ) {
            $err = 'Token not provided.';
            $this->renewal_payment_failure( $renewal_order, $err );

            return FALSE;
        }

        $this->is_scheduled_payment = TRUE;

        // --- Test a failed recurring payment
        if ( defined( 'WC_GATEWAY_GESTPAY_FORCE_FAILED_RECURRING_PAYMENT' ) && WC_GATEWAY_GESTPAY_FORCE_FAILED_RECURRING_PAYMENT ) {
            $this->Helper->log_add( "WC_GATEWAY_GESTPAY_FORCE_FAILED_RECURRING_PAYMENT è abilitata e tutti i pagamenti ricorrenti falliranno!" );
            $token = 'TokenERR';
        }

        // Do the payment through S2S
        $response = $this->s2s_payment( $renewal_order,
            array(
                'shopLoginRec' => $this->Gestpay->shopLoginRec, // will be used if not empty
                'apikeyRec' => $this->Gestpay->apikeyRec, // will be used if not empty
                'token' => $token,
                'amount' => number_format( (float)$amount_to_charge, 2, '.', '' ),

                // Renewal order ID already contains the prefix (if any), so DO NOT add it again!
                //'shopTransactionId' => $this->Helper->get_transaction_id( $renewal_order_id ) // <-- ERROR
                'shopTransactionId' => $renewal_order_id
            )
        );

        if ( ! empty( $response['pay_result'] ) && $response['pay_result'] == "OK" ) {
            // Add order note and update the subscription.
            $renewal_order->add_order_note( $this->Gestpay->strings['subscr_approved'] );
            $this->Helper->log_add( $this->Gestpay->strings['subscr_approved'] );
            WC_Subscriptions_Manager::process_subscription_payments_on_order( $parent_order_id );

            return TRUE;
        }
        else {
            $err = 'An error occours on s2s_payment.';

            if ( ! empty( $response['error_code'] ) && ! empty( $response['error_desc'] ) ) {
                $err.= ' '. $response['error_code'].': '.$response['error_desc'];
            }
            elseif ( ! empty( $response['VbVRisp'] ) ) {
                $err.= " You are trying to force a recurring payment but the 3D Secure protocol is enabled.";
            }

            $this->renewal_payment_failure( $renewal_order, $err );
        }
    }

    /**
     * Mark the given order as failed, add an order note and throw an exception.
     *
     * @param object $order the \WC_Order object
     * @param string $message a message to display inside the "Payment Failed" order note
     */
    public function renewal_payment_failure( $renewal_order, $message = '' ) {

        $renewal_order_err = 'Gestpay S2S Error: ' . __( $message, $this->textdomain );

        if ( wcs_order_contains_renewal( $renewal_order->get_id() ) ) {
            $parent_order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $renewal_order->get_id() );
            WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $parent_order_id );
        }

        $this->Helper->log_add( $renewal_order_err );
        $renewal_order->add_order_note( $renewal_order_err );

        // Allow actors to hook into renewal_payment_failure
        do_action( 'gestpay_on_renewal_payment_failure', $renewal_order, $message, $this );

        throw new Exception( $renewal_order_err );
    }

    /**
     * Render the payment method used for a subscription in the "My Subscriptions" table
     *
     * @param string $payment_method_to_display the default payment method text to display
     * @param WC_Subscription $subscription the subscription details
     * @return string the subscription payment method
     */
    public function maybe_render_subscription_payment_method( $payment_method_to_display, $subscription ) {

        if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $subscription ) ) {
            $order_id = $subscription->get_parent_id();
            $token = $this->Helper->get_order_token( $order_id );

            if ( is_array( $token ) ) {
                // Current token version is saved as array and contains expiry date.
                return sprintf( __( 'Via %s %s/%s', $this->textdomain ),
                    $this->show_token( $token['token'] ),
                    $token['month'],
                    $token['year']
                );
            }
            else {
                // deprecated token as string and without expiry date values
                // except for PayPal, which doesn't have an expiry date.

                if ( ! empty( $this->saved_cards ) ) {
                    // user is logged in, try to match the saved token with the ones in the card section
                    foreach ( $this->saved_cards as $card ) {
                        if ( $card['token'] == $token ) {
                            return sprintf( __( 'Via %s %s/%s', $this->textdomain ),
                                $this->show_token( $card['token'] ),
                                $card['month'],
                                $card['year']
                            );
                        }
                    }
                }

                $token_shown = $this->show_token( $token );

                if ( $token_shown != '**********' ) {
                    if ( $subscription->get_payment_method() == 'wc_gateway_gestpay_paypal' ) {
                        $str_token = 'PayPal (' . $token_shown . ')';
                    }
                    else {
                        $str_token = $token_shown;
                    }

                    // we don't know expiry date. Print just the token
                    return sprintf( __( 'Via %s', $this->textdomain ), $str_token );
                }
                else {
                    return 'N/A';
                }
            }
        }

        return $payment_method_to_display;
    }

    private function show_token( $token ) {
        return substr_replace( $token, '**********', 2, -4 );
    }

    /**
     * Here we can disallow future authorizations after cancelling a subscription.
     *
     * @param WC_Order $order Order object.
     */
    public function cancelled_subscription( $order ) {
        // @todo Not available now
    }

	/**
	 * Copy over the billing reference id and billing/shipping address info from
	 * a successful manual payment for a failed renewal.
	 *
	 * @param WC_Subscription $subscription  The subscription for which the failing payment method relates.
	 * @param WC_Order        $renewal_order The order which recorded the successful payment (to make up for the failed automatic payment).
	 */
	public function update_failing_payment_method( $subscription, $renewal_order ) {
        // @todo Not available now
    }
}
