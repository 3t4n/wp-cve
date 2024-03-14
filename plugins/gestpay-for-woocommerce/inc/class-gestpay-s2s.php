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

class Gestpay_S2S {

    public function __construct( $gestpay ) {

        // Get a pointer to the main class and to the helper.
        $this->Gestpay = $gestpay;
        $this->Helper = $gestpay->Helper;
        $this->can_have_cards = TRUE;

        include_once 'class-gestpay-subscriptions.php';
        $this->Subscr = new Gestpay_Subscriptions( $gestpay );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue additional Javascript
     */
    public function enqueue_scripts() {

        $fancybox_path = $this->Helper->plugin_url . 'lib/jquery.fancybox';
        wp_enqueue_style( 'gestpay-for-woocommerce-fancybox-css', $fancybox_path . '.min.css' );
        wp_enqueue_script( 'gestpay-for-woocommerce-fancybox-js', $fancybox_path . '.min.js', array( 'jquery' ), WC_VERSION, TRUE );
    }


    /**
     * Output a payment box containing your direct payment form
     */
    public function payment_fields() {

        include_once 'checkout-payment-fields.php';
    }

    /**
     * Returns true if the posted credit card fields are valid, false otherwise
     */
    function validate_payment_fields() {

        // Skip validation if reusing a token
        $cc_token = $this->Helper->get_post( 'gestpay-s2s-cc-token' );
        if ( !empty( $cc_token ) && $cc_token != 'new-card' ) {
            return TRUE;
        }

        // Allow actors to change error string values
        $errors = apply_filters( 'gestpay_s2s_payment_fields_error_strings', array(
            'cc_number' => "Si prega di valorizzare correttamente il numero di carta di credito",
            'cc_expiry' => "Si prega di valorizzare mese/anno di scadenza",
            'cc_cvv'    => "Si prega di valorizzare il codice di sicurezza",
            'cc_buyer'  => "Si prega di valorizzare il nome del proprietario della carta"
        ));

        $is_valid = TRUE;

        $buyer_name = $this->Helper->get_post( 'gestpay-cc-buyer-name' );
        $cc_number = $this->Helper->get_post( 'gestpay-cc-number' );
        $cc_month = $this->Helper->get_post( 'gestpay-cc-exp-month' );
        $cc_year = $this->Helper->get_post( 'gestpay-cc-exp-year' );
        $cc_cvv = $this->Helper->get_post( 'gestpay-cc-cvv' );

        // Validate card number
        $cc_number = str_replace( array( ' ', '-' ), '', $cc_number );
        if ( empty( $cc_number ) || strlen( $cc_number ) < 12 || strlen( $cc_number ) > 19 || !ctype_digit( $cc_number ) ) {
            $this->Helper->wc_add_error( $errors['cc_number'] );
            $is_valid = FALSE;
        }

        // These must be filled
        if ( empty( $cc_month ) || empty( $cc_year ) ) {
            $this->Helper->wc_add_error( $errors['cc_expiry'] );
            $is_valid = FALSE;
        }

        // Maybe check also the card security code
        if ( $this->Gestpay->is_cvv_required && ( empty( $cc_cvv ) || strlen( $cc_cvv ) < 3 || strlen( $cc_cvv ) > 4 || !ctype_digit( $cc_cvv ) ) ) {
            $this->Helper->wc_add_error( $errors['cc_cvv'] );
            $is_valid = FALSE;
        }

        // Maybe check the buyer name
        if ( $this->Gestpay->param_buyer_name && empty( $buyer_name ) ) {
            $this->Helper->wc_add_error( $errors['cc_buyer'] );
            $is_valid = FALSE;
        }

        // Allow actors to filter the credit card field validation.
        return apply_filters( 'gestpay_s2s_validate_payment_fields', $is_valid, $this );
    }

    /**
     * Process the payment and return the result.
     */
    public function process_payment( $order ) {

        $s2s_payment_params = array();

        if ( $this->Gestpay->save_token ) {

            $token = $this->Helper->get_post( 'gestpay-s2s-cc-token' );
            if ( ! empty( $token ) && $token != 'new-card' ) {

                $this->Helper->log_add( '[reusing token]: ' . $token );

                if ( !empty( $this->Subscr->saved_cards ) ) {
                    $card_token = array();
                    foreach ( $this->Subscr->saved_cards as $card ) {
                        if ( $card['token'] == $token ) {
                            $card_token = $card;
                            break;
                        }
                    }

                    $this->Helper->set_order_token( $order, $card_token );
                }

                // Add the token to the parameters, so that it will be used to make the first payment
                $s2s_payment_params['token'] = $token;
            }
        }

        $this->Helper->log_add( '======= S2S Payment Phase 1 =======' );

        $s2s_response = $this->Subscr->s2s_payment( $order, $s2s_payment_params );

        if ( ! empty( $s2s_response['pay_result'] ) && $s2s_response['pay_result'] == "OK" ) {

            /*
            | ----------------------------------------------------------------------------------------------------------
            | == Transactions made with non 3D-Secure cards ==
            |
            | From a functional perspective the transaction is processed as a normal authorization request,
            | because these does not requires cardholder authentication.
            | ----------------------------------------------------------------------------------------------------------
            */

            /*
                If the request returns OK the order can be set as completed.
             */

            return array(
                'result'   => 'success',
                'redirect' => $this->Helper->wc_url( 'order_received', $order ),
            );

        }
        elseif ( ! empty( $s2s_response['VbVRisp'] ) ) {

            /*
            | ----------------------------------------------------------------------------------------------------------
            | == Transactions made with 3D-Secure cards ==
            | = Phase I: authorization request =
            |
            | A standard authorization request is made. If the card is recognised as 3D, the outcome of the
            | request is a specific error code (8006) which is readable by means of the ErrorCode method.
            | The error description (Verified By Visa) will be readable by means of the ErrorDescription method.
            | In this phase the following additional information is also shown. This information is required
            | during the payment process and is specific to Verified by Visa transactions. In particular it is
            | necessary to acquire the transaction code, which can be read by means of the TransKey
            | method and an encrypted string to be used during the subsequent phase and which is
            | readable by means of the VbVRisp value, which is as well in the XML return.
            | ----------------------------------------------------------------------------------------------------------
            */

            /*
                On the receipt page there is already the main form and for the Phase II we need to
                send the response which comes from Phase I through POST, and then we have to
                redirect the user to the 3D Secure page.
                For that reasons we use the wc-action to find out from which request comes from.
            */

            return array(
                'result'   => 'success',
                'redirect' => add_query_arg(
                    array(
                        'wc-action' => '3DSauth',
                        'VbVRisp'   => $s2s_response['VbVRisp']
                    ),
                    $this->Helper->wc_url( 'pay', $order )
                )
            );

        }

        if ( ! empty( $s2s_response['error_code'] ) && ! empty( $s2s_response['error_desc'] ) ) {
            if ( function_exists( 'wcs_is_subscription' ) && ! wcs_is_subscription( $order ) ) {
                // Update to failed only if is not a card change
                $order->update_status( 'failed', 'Payment Error: ' . $s2s_response['error_code'] . ' ' . $s2s_response['error_desc'] );
            }
        }

        return FALSE;
    }

    /**
     * Generate the receipt page
     */
    public function receipt_page( $order ) {

        if ( isset( $_GET['wc-action'] ) && $_GET['wc-action'] == '3DSauth' && ! empty( $_GET['VbVRisp'] ) ) {

            /*
            | ----------------------------------------------------------------------------------------------------------
            | == Transactions made with 3D-Secure cards ==
            | = Phase II: cardholder authentication =
            |
            | In this phase it is necessary to allow the buyer to authenticate him/herself to the credit card
            | issuer. The buyer's browser must be redirected to a specific page on the Gestpay
            | website which will act as an interface for authentication and to direct the buyer to the
            | issuer's site, providing him/her with all of the information required for authentication.
            | The page must be retrieved through the following 3 parameters:
            | - a => shopLogin
            | - b => an encrypted string acquired in the previous phase through GetVbVRisp
            | - c => the URL of the merchant’s web site to which the buyer must be redirected after the authentication procedure
            | Any additional parameters will not be returned in the response to the second call.
            | At the end of the authentication process the buyer will be redirected to the merchant's site to
            | the URL specified as redirection parameter c.
            | The merchant's page for welcoming back the buyer after authentication will be retrieved
            | by means of a PARES parameter (an encrypted string containing the result of authentication)
            | which must be acquired by the merchant and forwarded to Gestpay during the following phase.
            | ----------------------------------------------------------------------------------------------------------
            */

            $input_params = array(
                'a' => $this->Gestpay->shopLogin,
                'b' => $_GET['VbVRisp'],
                'c' => add_query_arg(
                    array(
                        'wc-action' => 'checkVbV',
                        'order_id'  => $order->get_id(),
                    ),
                    $this->Gestpay->ws_S2S_resp_url
                )
            );

            $this->Helper->log_add( '======= S2S Payment Phase 2 ======= Redirect to 3D Secure auth page.' );

            echo $this->Helper->get_gw_form( $this->Gestpay->pagam3d_url, $input_params, $order );
        }
    }

    /**
     * Handle Tokenization Phase III
     */
    public function phase_III_3D_Secure() {

        if ( isset( $_GET['wc-action'] ) && $_GET['wc-action'] == 'checkVbV'
            && ! empty( $_GET['order_id'] ) && ! empty( $_REQUEST['PaRes'] ) ) {

            /*
            | ----------------------------------------------------------------------------------------------------------
            | == Transactions made with 3D-Secure cards ==
            | = Phase III: conclusion of transaction =
            |
            | At this stage the merchant is in possession of all of the information required to conclude
            | the transaction. A new authorization request must be made (by using the CallPagamS2S method).
            | However, before using again such call, it is necessary to assign to WSs2s all of the
            | information required by providing the variables:
            | - shopLogin (merchant code)
            | - uicCode (currency code)
            | - amount (amount)
            | - shopTransactionID (transaction identification code)
            | - transKey (transaction ID acquired during Phase I)
            | - PARes (encrypted string containing the result of authentication acquired during Phase II)
            | The result of the transaction displayed by Gestpay will be interpreted as depicted in the Authorization Request section.
            | ----------------------------------------------------------------------------------------------------------
            */

            $order = wc_get_order( absint( $_GET['order_id'] ) );
            if ( $order ) {

                $this->Helper->log_add( '======= S2S Payment Phase 3 =======' );

                $response = $this->Subscr->s2s_payment( $order, array( 'pares' => $_REQUEST['PaRes'] ) );

                // Fix 20191022
                if ( !empty( $response['pay_result'] ) && $response['pay_result'] == 'KO' ) {

                    if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) ) {
                        $url = $order->get_view_order_url();
                    }
                    else {
                        $url = $this->Helper->wc_url( 'order_failed', $order );
                    }
                }
                else {
                    $url = $this->Helper->wc_url( 'order_received', $order );
                }

                header( "Location: " . $url );
                die();
            }
        }

    }

}
