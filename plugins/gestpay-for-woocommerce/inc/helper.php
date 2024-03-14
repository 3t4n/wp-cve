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

if ( ! class_exists( 'WC_Gateway_GestPay_Helper' ) ) :

class WC_Gateway_GestPay_Helper {

    public $plugin_url;
    public $plugin_path;
    public $plugin_slug;
    public $plugin_textdomain;
    public $plugin_logfile;

    function __construct() {

        $this->plugin_url            = trailingslashit( plugins_url( '', $plugin = GESTPAY_MAIN_FILE ) );
        $this->plugin_dir_path       = plugin_dir_path( GESTPAY_MAIN_FILE );
        $this->plugin_path           = dirname( plugin_basename( GESTPAY_MAIN_FILE ) );
        $this->plugin_slug           = basename( GESTPAY_MAIN_FILE, '.php' );
        $this->plugin_slug_dashed    = str_replace( "-", "_", $this->plugin_slug );
        $this->plugin_textdomain     = $this->plugin_slug;
        $this->plugin_logfile_name   = $this->get_plugin_logfile_name();
    }

    /**
     * Localize, script and init the gateway
     */
    function init_gateway( &$this_gw ) {

        $this->gw = $this_gw;

        // Localize
        load_plugin_textdomain( 'gestpay-for-woocommerce', false, $this->plugin_path . "/languages" );

        // Style
        wp_enqueue_style( 'gestpay-for-woocommerce-css', $this->plugin_url . '/gestpay-for-woocommerce.css' );

        // Maybe load the strings used on this plugin
        if ( method_exists( $this_gw, 'init_strings' ) ) {
            $this_gw->init_strings();
        }

        // Load form fields and settings
        $this_gw->form_fields = require dirname( GESTPAY_MAIN_FILE ) . '/inc/init_form_fields.php';
        $this_gw->init_settings();
        $this->load_card_icons();
    }

    function get_single_card_settings_array( $name ) {

        return array(
            'title' => '',
            'type' => 'checkbox',
            'label' => $name,
            'default' => 'no'
        );
    }

    function get_cards_settings() {

        return apply_filters( 'gestpay_gateway_parameters_cards', array(
            'cards' => array(
                'title' => $this->gw->strings['gateway_overwrite_cards'],
                'type' => 'title',
                'description' => $this->gw->strings['gateway_overwrite_cards_label'],
                'class' => 'mmnomargin',
            ),
            'card_visa'       => $this->get_single_card_settings_array( 'Visa Electron' ),
            'card_mastercard' => $this->get_single_card_settings_array( 'Mastercard' ),
            'card_maestro'    => $this->get_single_card_settings_array( 'Maestro' ),
            'card_ae'         => $this->get_single_card_settings_array( 'American Express' ),
            'card_dci'        => $this->get_single_card_settings_array( 'Diners Club International' ),
            'card_paypal'     => $this->get_single_card_settings_array( 'PayPal' ),
            'card_paypal_bnpl'=> $this->get_single_card_settings_array( 'PayPal Buy Now Pay Later' ),
            'card_jcb'        => $this->get_single_card_settings_array( 'JCB Cards' ),
            'card_postepay'   => $this->get_single_card_settings_array( 'PostePay' ),
            'card_mybank'     => $this->get_single_card_settings_array( 'MyBank' ),
        ));
    }

    /**
     * Check if the order was paid with Gestpay.
     */
    function is_gestpaid( $order_id ) {

        if ( 'wc_gateway_gestpay' == get_post_meta( $order_id, '_payment_method', TRUE ) ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Load card icons.
     */
    function load_card_icons() {

        $cards = array();
        $card_path = $this->plugin_url . '/images/cards/';
        $gws = $this->gw->settings;

        if (isset($gws['card_visa'])       && $gws['card_visa'] == "yes")       $cards[] = $card_path . 'card_visa.jpg';
        if (isset($gws['card_mastercard']) && $gws['card_mastercard'] == "yes") $cards[] = $card_path . 'card_mastercard.jpg';
        if (isset($gws['card_maestro'])    && $gws['card_maestro'] == "yes")    $cards[] = $card_path . 'card_maestro.jpg';
        if (isset($gws['card_ae'])         && $gws['card_ae'] == "yes")         $cards[] = $card_path . 'card_ae.jpg';
        if (isset($gws['card_dci'])        && $gws['card_dci'] == "yes")        $cards[] = $card_path . 'card_dci.jpg';
        if (isset($gws['card_paypal'])     && $gws['card_paypal'] == "yes")     $cards[] = $card_path . 'card_paypal.jpg';
        if (isset($gws['card_paypal_bnpl'])&& $gws['card_paypal_bnpl'] == "yes")$cards[] = $card_path . 'card_paypal_bnpl.jpg';
        if (isset($gws['card_jcb'])        && $gws['card_jcb'] == "yes")        $cards[] = $card_path . 'card_jcb.jpg';
        if (isset($gws['card_postepay'])   && $gws['card_postepay'] == "yes")   $cards[] = $card_path . 'card_postepay.jpg';
        if (isset($gws['card_mybank'])     && $gws['card_mybank'] == "yes")     $cards[] = $card_path . 'card_mybank.jpg';

        if ( empty( $cards ) ) return;

        // workaround for get_icon() of WC_Payment_Gateway
        // @see abstract-wc-payment-gateway.php
        $cards_string = '';
        foreach ( $cards as $card ) {
            $cards_string .= $card . ( end( $cards ) == $card ? '' : '" /><img src="' );
        }

        $this->gw->icon = apply_filters( 'gestpay_gateway_cards_images', $cards_string );
    }

    function get_plugin_logfile_name() {

        return ( defined( 'WC_LOG_DIR' ) ? WC_LOG_DIR : '' ) . $this->plugin_slug."-".sanitize_file_name( wp_hash( $this->plugin_slug ) ).'.log';
    }

    function log_add( $message, $arr = array() ) {

        if ( $this->gw->debug ) {
            if ( ! isset( $this->log ) || empty( $this->log ) ) {
                $this->log = new WC_Logger();
            }

            if ( ! empty( $arr ) ) {

                $cloned_arr = clone $arr; // Required to prevent parameters modification.

                if ( ! empty( $cloned_arr->cardNumber ) ) {
                    $cloned_arr->cardNumber = substr_replace( $cloned_arr->cardNumber, '**********', 2, -4 );
                }

                if ( ! empty( $cloned_arr->cvv ) ) {
                    $cloned_arr->cvv = '***';
                }

                if ( ! empty( $cloned_arr->PARes ) ) {
                    $cloned_arr->PARes = substr( $cloned_arr->PARes, 0, 16 ) . '...';
                }

                if ( ! empty( $cloned_arr->apikey ) ) {
                    $cloned_arr->apikey = substr( $cloned_arr->apikey, 0, 16 ) . '...';
                }

                $message.= $this->var_export( $cloned_arr );
            }

            $this->log->add( $this->plugin_slug, $message );
        }
    }

   /**
    * This prevent to change the floating point numbers precisions with var_export
    * @see also http://stackoverflow.com/a/32149358/1992799
    *
    * @thanks to Luca Cantoreggi
    *
    * @param $expression mixed Same as var_export
    *
    * @return mixed
    */
   private function var_export( $expression ) {

        if ( empty( $expression ) ) {
            return '';
        }

       // Store the current precision
       $ini_value = ini_get( 'serialize_precision' );

       // Set the new precision and export the variable
       ini_set( 'serialize_precision', 2 );
       $value = var_export( $expression, TRUE );

       // Restore the previous value
       ini_set( 'serialize_precision', $ini_value );

       return ' ' . $value;
   }

    /**
     * Clean and validate order's prefix
     */
    function get_order_prefix( &$settings ) {

        if ( isset( $settings['order_prefix'] ) && ! empty( $settings['order_prefix'] ) ) {
            // allows only alphanumeric charactes
            $prefix = preg_replace( "/[^A-Za-z0-9]/", '', $settings['order_prefix'] );

            // max 15 char
            $prefix = substr( $prefix, 0, 15 );

            // Update the order prefix value
            $settings['order_prefix'] = $prefix;

            return $prefix;
        }

        return '';
    }

    /**
     * Construct the custom info string
     *
     * @return string
     */
    function get_custominfo( $param_custominfo ) {

        $param_custominfo = str_replace( "\r", '', $param_custominfo );

        $custom_info = array();

        // Split the textarea content by each row
        $custominfos = explode( "\n", trim( $param_custominfo ) );

        // Remove any extra \r characters left behind
        $custominfos = array_filter( $custominfos, 'trim' );

        foreach ( $custominfos as $custominfo ) {
            // max field lenght is 300 characters and unallowed chars must me removed
            $custominfo    = substr( $custominfo, 0, 300 );
            $custom_info[] = $this->get_clean_param( $custominfo );
        }

        return implode( "*P1*", $custom_info );
    }

    /**
     * Clean up the string removing unallowed parameters.
     *
     * @param string $in_string
     *
     * @return string
     */
    function get_clean_param( $in_string ) {

        return str_replace( array(
            "&"," ","§","(",")","*","<",">",",",";",":","*P1*","/","/*","[","]","?","%"
        ), "", $in_string );
    }

    /**
     * Get current language between ones available on GestPay Pro.
     * Default English
     *
     * @return int
     */
    function get_language() {

        switch ( $this->get_current_language_2dgtlwr() ) {
            case 'it' :
                return 1;
            case 'es' :
                return 3;
            case 'fr' :
                return 4;
            case 'de' :
                return 5;
        }

        return 2; // en
    }

    function get_gestpay_currencies() {

        return include 'gestpay-currencies.php';
    }

    /**
     * Mapper for the Gestpay currency codes.
     */
    function get_order_currency( $order ) {

        $gestpay_allowed_currency_codes = $this->get_gestpay_currencies();

        $the_currency = $this->get_currency( $order );

        if ( in_array( $the_currency, array_keys( $gestpay_allowed_currency_codes ) ) ) {
            $gp_currency = $gestpay_allowed_currency_codes[$the_currency]['iso'];
        }
        else {
            $gp_currency = '242'; // Set EUR as default currency code
        }

        return $gp_currency;
    }

    function get_currency( $order ) {

        if ( method_exists( $order, 'get_currency' ) ) { // wc>=3
            $the_currency = $order->get_currency();
        }
        else {
            $the_currency = get_post_meta( $order->get_id(), '_order_currency', true );
        }

        if ( empty( $the_currency ) ) {
            $the_currency = get_option( 'woocommerce_currency' );
        }

        return $the_currency;
    }


    /**
     * Fix orders with trial period and requests for card change.
     * We have to add the minimum for the currency (1 cent for EUR) so that
     * the order can be paid. This amount will be refunded after receiving the token.
     */
    function maybe_add_0_order_amount_fix( $order, $amount, $order_currency ) {

        if ( ! $this->is_subscription_order( $order ) ) {
            return;
        }

        $order_total = $order->get_total();
        $status = $order->get_status();
        $this->log_add( "[order_amount_0] tot ordine: " . $order_total . ' status: ' . $status );

        // Add the amount only if it wasn't already added.
        // If a payment fails, the cent is assigned anyway to the order, so we must not add it again.
        $maybe_amount_fix = get_post_meta( $order->get_id(), GESTPAY_ORDER_META_AMOUNT, TRUE );
        if ( empty( $maybe_amount_fix ) ) {
            $fix_message = "Addebito di ".$amount." ".$order_currency." per evitare errore per importo nullo su Gestpay. Si proverà a stornare tale importo automaticamente.";
            $this->log_add( $fix_message );
            $order->add_order_note( $fix_message );
            update_post_meta( $order->get_id(), GESTPAY_ORDER_META_AMOUNT, $amount );

            $maybe_amount_fix = $amount;
        }
        $this->log_add( "[order_amount_0] update_post_meta ".GESTPAY_ORDER_META_AMOUNT." per id ordine: " . $order->get_id() . ' -> amount: ' . $maybe_amount_fix );
    }

    /**
     * Check if the given order must be refunded for the "0 order amount" fix
     * or for the payment method changed.
     */
    function maybe_refund_0_order_amount_fix( $order ) {

        if ( ! $this->is_subscription_order( $order ) ) {
            return;
        }

        $order_id = $order->get_id();
        $order_total = $order->get_total();
        $status = $order->get_status();

        $this->log_add( "[order_amount_0] Refund per ordine n. ".$order_id." tot ordine: ".$order_total.' status: '.$status );

        $parent_order = $this->get_parent_order_id( $order_id );
        $this->log_add( "[order_amount_0] get_parent_order_id " . $parent_order );

        // Maybe refund the amount used on the first trial order.
        $gestpay_fix_amount_zero = get_post_meta( $order_id, GESTPAY_ORDER_META_AMOUNT, TRUE );
        if ( $gestpay_fix_amount_zero ) {
            $refund_res = $this->gw->Order_Actions->refund( $order_id, $gestpay_fix_amount_zero, 'Write-Off' );

            $this->log_add( "[order_amount_0] Order_Actions refund di:" . $gestpay_fix_amount_zero );

            if ( ! $refund_res ) {
                $this->log_add( "[order_amount_0] Order_Actions settle di:" . $gestpay_fix_amount_zero );

                // If the order can't be refunded, probably the merchant is using MOTO with
                // separation, so we can try to settle and then refund.
                $settle_res = $this->gw->Order_Actions->settle( $order_id, $gestpay_fix_amount_zero );
                if ( $settle_res === TRUE ) {
                    $this->log_add( "[order_amount_0] Order_Actions settle->refund di:" . $gestpay_fix_amount_zero );
                    $refund_res = $this->gw->Order_Actions->refund( $order_id, $gestpay_fix_amount_zero, 'Write-Off' );
                }
            }

            // Remove order meta so it will not be processed anymore (even if refund is failed).
            delete_post_meta( $order_id, GESTPAY_ORDER_META_AMOUNT );
            $this->log_add( "[order_amount_0] delete_post_meta per id:" . $order_id . ' meta: ' . GESTPAY_ORDER_META_AMOUNT );

            $add_order_error = !function_exists( 'wcs_is_subscription' ) || !wcs_is_subscription( $order );

            if ( $refund_res !== TRUE ) {
                $refund_err = "ERRORE: Rimborso di 1 centesimo fallito ";
                if ( $add_order_error ) {
                    $this->log_add( "[order_amount_0] add_order_note refunded" );
                    $order->add_order_note( $refund_err );
                }

                $this->log_add( $refund_err . "per Ordine #" . $order_id );
            }
            else {
                // Update status to refunded only if this is a full refund.
                // Partial refund must not be changed
                if ( $add_order_error && $order_total == $gestpay_fix_amount_zero ) {
                    $this->log_add( "[order_amount_0] update_status refunded" );
                    $order->update_status( 'refunded' );
                }

                $this->log_add( "Rimborso di 1 centesimo effettuato correttamente per Ordine #" . $order_id );
            }

        }
        else {
            $this->log_add( "[order_amount_0] skip refund valore: " . $gestpay_fix_amount_zero );
        }
    }

    /**
     * Get the right value based on currency; some of them does not allow to use decimals.
     * If the order is a payment change $override_amount will be 0 (@see s2s_payment()).
     */
    function get_order_amount( $override_amount, $uic_code, $order ) {

        $order_currency = $this->get_currency( $order );
        $gestpay_currencies = $this->get_gestpay_currencies();

        if ( ! isset( $gestpay_currencies[$order_currency] ) ) {
            return 0;
        }

        $gestpay_currency = $gestpay_currencies[$order_currency];

        if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) ) {
            $amount = 0;
        }
        else {
            $amount = $override_amount !== FALSE ? $override_amount : $order->get_total();
        }

        if ( empty( (float)$amount ) ) {
            // Fix order amount is available for WC Subscriptions
            $amount = $gestpay_currency['min'];
            $this->maybe_add_0_order_amount_fix( $order, $amount, $order_currency );
        }

        $amount = number_format( (float)$amount, 2, '.', '' );

        if ( isset( $gestpay_currency['decimal'] ) && $gestpay_currency['decimal'] != 2 ) {
            // Maybe apply a round (some currencies does not allow decimals)
            $amount = round( $amount, $gestpay_currency['decimal'], PHP_ROUND_HALF_DOWN );
        }

        return $amount;
    }

    /**
     * Retrieve the parent order_id
     */
    function get_parent_order_id( $order ) {

        if ( empty( $order ) ) {
            return FALSE;
        }

        $order_id = is_a( $order, 'WC_Order' ) ? $order->get_id() : $order;

        if ( $this->is_subscriptions_active() ) {
            if ( wcs_order_contains_renewal( $order_id ) ) {
                $order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $order_id );
            }
            elseif ( wcs_is_subscription( $order ) ) {
                $subscription = wcs_get_subscription( $order );
                $order_id = $subscription->get_parent_id();
            }
        }

        return $order_id;
    }

    /**
     * Store the token to the order meta (of the parent order if renewal).
     * The given token can be an xml object or an array
     */
    function set_order_token( $order, $tokenshiro ) {

        $order_id = $this->get_parent_order_id( $order );
        if ( empty( $order_id ) ) {
            return FALSE;
        }

        if ( empty( $tokenshiro ) ) {
            $this->log_add( "Token is empty for order: " . $order_id );
            return FALSE;
        }

        if ( is_object( $tokenshiro ) && ! empty( $tokenshiro->TOKEN ) ) {
            $token = array(
                'token' => (string) $tokenshiro->TOKEN,
                'month' => !empty( $tokenshiro->TokenExpiryMonth ) ? (int) $tokenshiro->TokenExpiryMonth : 1,
                'year'  => !empty( $tokenshiro->TokenExpiryYear ) ? (int) $tokenshiro->TokenExpiryYear : 2109
            );
        }
        else if ( ! empty( $tokenshiro['token'] ) ) { // already an array
            $token = $tokenshiro;
        }
        else {
            $this->log_add( "Token is empty for order: " . $order_id );
            return FALSE;
        }

        $res = update_post_meta( $order_id, GESTPAY_META_TOKEN, $token );

        if ( empty( $res ) ) {
            $this->log_add( "FAILED to set token for order: " . $order_id );
        }
        else {
            $this->log_add( "Set token ". $token['token'] ." for order: " . $order_id );
        }

        return $res;
    }

    /**
     * Get the token from the order meta (from the parent order if renewal and from the main if subscription).
     * If it was a token saved before version 201910xx it will be a single string.
     * All future tokens will be saved alongside the expiry date as array.
     */
    function get_order_token( $order, $just_token = true ) {

        $order_id = $this->get_parent_order_id( $order );
        if ( empty( $order_id ) ) {
            return FALSE;
        }

        $token = get_post_meta( $order_id, GESTPAY_META_TOKEN, TRUE );
        if ( empty( $token ) ) {
            return FALSE;
        }

        if ( is_array( $token ) && $just_token ) {
            return !empty( $token['token'] ) ? $token['token'] : FALSE;
        }

        return $token;
    }

    /**
     * If the merchant is using a plugin which alters the original ID of the order
     * we need to extract it, so that it can be used in normal functions, like update_post_meta
     * or wc_get_order.
     */
    function get_real_order_id( $order_id ) {

        if ( class_exists( 'WC_Seq_Order_Number_Pro' ) ) {
            $wc_son = new WC_Seq_Order_Number_Pro();
            $order_id = $wc_son->find_order_by_order_number( $order_id );
        }

        return apply_filters( 'gestpay_revert_order_id', $order_id );
    }

    /**
     * If the merchant is using a plugin which alters the original ID of the order
     * we need to retrieve it, so that can be used to save the correct transaction ID.
     */
    function get_transaction_id( $order_id ) {

        if ( class_exists( 'WC_Seq_Order_Number_Pro' ) ) {
            $wcsonp_id = get_post_meta( $order_id, '_order_number_formatted', true );

            if ( ! empty( $wcsonp_id ) ) {
                return $wcsonp_id;
            }
        }

        return apply_filters( 'gestpay_alter_order_id', $order_id );
    }

    /**
     * Backwards compatible get URL
     */
    function wc_url( $path, $order ) {

        switch ( $path ) {
            case 'view_order':
                return $order->get_view_order_url();

            case 'order_received':
                return add_query_arg( 'utm_nooverride', '1', $this->gw->get_return_url( $order ) );

            case 'order_failed':
                return add_query_arg( 'utm_nooverride', '1', wc_get_checkout_url() );

            case 'pay':
                return $order->get_checkout_payment_url( true );
        }

        return '';
    }

    function handle_transaction_details( $order, $order_id, $xml ) {

        $txn_details = array(
            'bt_id' => '',
            'auth_code' => '',
            'tr_key' => ''
        );

        $order_note = '';

        if ( ! empty( $xml->BankTransactionID ) ) {
            // This is required for order actions.
            $txn_details['bt_id'] = (string)$xml->BankTransactionID;
            $order_note = "Bank TID: " . $txn_details['bt_id'];
            update_post_meta( $order_id, GESTPAY_ORDER_META_BANK_TID, $txn_details['bt_id'] );
        }

        if ( ! empty( $xml->AuthorizationCode ) ) {
            $txn_details['auth_code'] = (string)$xml->AuthorizationCode;
            $order_note.= " / Auth code: " . $txn_details['auth_code'];
            update_post_meta( $order_id, GESTPAY_ORDER_META_AUTH_CODE, $txn_details['auth_code'] );
        }

        if ( ! empty( $xml->TransactionKey ) ) {
            $txn_details['tr_key'] = (string)$xml->TransactionKey;
            $order_note.= " / Trans Key: " . $txn_details['tr_key'];
            update_post_meta( $order_id, GESTPAY_ORDER_META_TRANS_KEY, $txn_details['tr_key'] );
        }

        $order->add_order_note( $order_note );

        return implode( '/', array_filter( $txn_details ) );
    }

    /**
     * Update order status, add admin order note and empty the cart
     */
    function wc_order_completed( $order, $message, $tx_id = '' ) {

        if ( empty( $this->gw->completed_order_status ) ) {
            $moto_status = 'completed';
        }
        else {
            $moto_status = $this->gw->completed_order_status;
        }

        if ( ! $order->has_status( array( 'processing', 'completed' ) ) ) {

            if ( $this->gw->is_moto_sep && $moto_status == 'onhold' ) {
                $order->update_status( 'on-hold', $message );
                $this->log_add( 'ORDER MOTO ON-HOLD: ' . $message );
            }
            elseif ( $this->gw->is_moto_sep && $moto_status == 'pending' ) {
                $order->update_status( 'on-hold', $message );
                $this->log_add( 'ORDER MOTO PENDING: ' . $message );
            }
            else {
                $order->payment_complete( $tx_id );
                $order->add_order_note( $message );
                $this->log_add( 'ORDER COMPLETED: ' . $message );
                $this->maybe_refund_0_order_amount_fix( $order );
            }

            WC()->cart->empty_cart();

            // Under some circustances emails seems to not be fired. This force them to be sent.
            if ( defined( 'WC_GATEWAY_GESTPAY_FORCE_SEND_EMAIL' ) && WC_GATEWAY_GESTPAY_FORCE_SEND_EMAIL ) {
                $mailer = WC()->mailer();
                $mails = $mailer->get_emails();
                if ( ! empty( $mails ) ) {
                    foreach ( $mails as $mail ) {
                        if ( ( $order->has_status( 'completed' ) && ($mail->id == 'customer_completed_order' || $mail->id == 'new_order') )
                            || ( $order->has_status( 'processing' ) && ($mail->id == 'customer_processing_order' || $mail->id == 'new_order') ) ) {
                            $mail->trigger( $order->get_id() );
                        }
                    }
                }
            }
        }
    }

    /**
     * Maybe store the token.
     */
    function maybe_save_token( $order, $xml_response, $log_prefix = '' ) {
        if ( ! $this->gw->save_token ) {
            $this->log_add( $log_prefix.'TOKEN storage is disabled.' );
            return;
        }

        $order_id = $order->get_id();

        if ( ! $this->is_subscription_order( $order ) ) {
            // With PayPal, there is no need to store the token if the order does not contains a subscription
            // because it will not be used to pay other orders as is possible with the On-Site version.
            $this->log_add( $log_prefix.'Order #'.$order_id.' does not contains a subscription and the token will not be saved.' );
            return;
        }

        $resp = $this->set_order_token( $order, $xml_response );
        if ( empty( $resp ) ) {
            $this->log_add( $log_prefix.'Failed to save the token for Order #'.$order_id );
        }
    }

    /**
     * Create the gateway form, loading the autosubmit javascript.
     */
    function get_gw_form( $action_url, $input_params, $order ) {

        $assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
        $imgloader = $assets_path . 'images/ajax-loader@2x.gif';
        $js = <<<JS
            jQuery('html').block({
                message: '<img src="$imgloader" alt="Redirecting&hellip;" style="float:left;margin-right:10px;"/>Thank you! We are redirecting you to make payment.',
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                },
                css: {
                    padding: 20,
                    textAlign: 'center',
                    color: '#555',
                    border: '3px solid #aaa',
                    backgroundColor: '#fff',
                    cursor: 'wait',
                    lineHeight: '32px'
                }
            });
            jQuery('#submit__{$this->plugin_slug_dashed}').click();
JS;

        wc_enqueue_js( $js );

        $action_url        = esc_url_raw( $action_url );
        $cancel_url        = esc_url_raw( $order->get_cancel_order_url() );
        $pay_order_str     = 'Pay via '.$this->gw->method_title;
        $cancel_order_str  = 'Cancel order &amp; restore cart';

        $input_fields = "";
        foreach ( $input_params as $key => $value ) {
            $input_fields.= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
        }

        return <<<HTML
            <form action="{$action_url}" method="POST" id="form__{$this->plugin_slug_dashed}" target="_top">
                $input_fields
                <input type="submit" class="button-alt" id="submit__{$this->plugin_slug_dashed}" value="{$pay_order_str}" />
                <a class="button cancel" href="$cancel_url">{$cancel_order_str}</a>
            </form>
HTML;
    }

    /**
     * Backwards compatible add error
     */
    function wc_add_error( $error ) {

        if ( function_exists( 'wc_add_notice' ) ) {
            wc_add_notice( $error, 'error' );
        }
    }

    /**
     * Check if qTranslate-X or mqTranslate is enabled.
     *
     * @return bool true if one of them is active, false otherwise.
     */
    function is_qtranslate_enabled() {

        return ( defined('QTX_VERSION') ||
            in_array( 'qtranslate/qtranslate.php', (array) get_option( 'active_plugins', array() ) ) ||
                in_array( 'mqtranslate/mqtranslate.php', (array) get_option( 'active_plugins', array() ) ) );
    }

    /**
     * Checks if WooCommerce Subscriptions is active
     *
     * @return bool true if WCS is active, false otherwise.
     */
    function is_subscriptions_active() {

        return in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', (array) get_option( 'active_plugins', array() ) );
    }

    /**
     * Checks if the cart contains a Subscriptions.
     *
     * @return bool
     */
    function is_a_subscription() {

        return class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription();
    }

    /**
     * Checks if WC Subscriptions is active and the order contains or is a subscription.
     *
     * @return bool
     */
    function is_subscription_order( $order ) {

        return $this->is_subscriptions_active() && ( wcs_order_contains_subscription( $order, array( 'order_type' => 'any' ) ) || wcs_is_subscription( $order ) );
    }

    /**
     * Returns current language checking for qTranslate-X or WPML
     * Fallback on get_locale() if nothing found.
     *
     * @return string
     */
    function get_current_language() {

        if ( $this->is_qtranslate_enabled() ) {
            if ( function_exists( 'qtranxf_getLanguage' ) ) {
                return qtranxf_getLanguage(); // -- qTranslate X
            }
            else if ( function_exists( 'qtrans_getLanguage' ) ) {
                return qtrans_getLanguage(); // -- qTranslate / mqTranslate
            }
        }
        elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) { // --- Wpml
            return ICL_LANGUAGE_CODE;
        }

        return get_locale();
    }

    /**
     * Returns the two characters of the language in lowercase
     *
     * @return string
     */
    function get_current_language_2dgtlwr() {

        return substr( strtolower( $this->get_current_language() ), 0, 2 );
    }

    /**
     * Generate the option list
     */
    function get_page_list_as_option() {

        $opt_pages = array( 0 => " -- Select -- " );
        foreach ( get_pages() as $page ) {
            $opt_pages[ $page->ID ] = __( $page->post_title );
        }

        return $opt_pages;
    }

    /**
     * Show an error message.
     */
    function show_error( $msg ) {

        echo '<div id="woocommerce_errors" class="error fade"><p>ERRORE: ' . $msg . '</p></div>';
    }

    /**
     * Create a SOAP client using the specified URL
     */
    function get_soap_client( $url, $retry = true ) {

        try {
            $soapClientOptions = array(
                'user_agent' => 'Wordpress/GestpayForWoocommerce',
                'cache_wsdl' => WSDL_CACHE_NONE,
                'exceptions' => true
            );

            $client = new SoapClient( $url, $soapClientOptions );
        }
        catch ( SoapFault $e ) {
            $err = sprintf( __( 'Soap Client Request Exception with error %s' ), $e->getMessage() );
            $this->log_add( '[FATAL ERROR]: ' . $err );

            if ( $retry ) {
                sleep(3);
                $this->log_add( 'Retrying for SOAP Client error' );
                return $this->get_soap_client( $url, false );
            }

            $this->wc_add_error( $err );

            return false;
        }

        return $client;
    }

    /**
     * Check if the SOAP extension is enabled.
     *
     * @return false if SOAP is not enabled.
     */
    function check_fatal_soap( $plugin_name ) {

        if ( ! extension_loaded( 'soap' ) ) {
            $this->show_error( 'Per poter utilizzare <strong>' . $plugin_name . '</strong> la libreria SOAP client di PHP deve essere abilitata!' );
            return false;
        }

        return true;
    }

    /**
     * Check if suhosin is enabled and the get.max_value_length value.
     *
     * @return false if suhosin is not well configured.
     */
    function check_fatal_suhosin( $plugin_name, $print = TRUE ) {

        if ( is_numeric( @ini_get( 'suhosin.get.max_value_length' ) ) && ( @ini_get( 'suhosin.get.max_value_length' ) < 1024 ) ) {
            if ( $print ) {
                $this->show_error( $this->get_suhosin_error_msg( $plugin_name ) );
            }

            return false;
        }
        return true;
    }

    function get_suhosin_error_msg( $plugin_name ) {

        $err_suhosin = 'Sul tuo server è presente <a href="http://www.hardened-php.net/suhosin/index.html" target="_blank">PHP Suhosin</a>.<br>Devi aumentare il valore di <a href="http://suhosin.org/stories/configuration.html#suhosin-get-max-value-length" target="_blank">suhosin.get.max_value_length</a> almeno a 1024, perché <strong>' . $plugin_name . '</strong> utilizza delle query string molto lunghe.<br>';
        $err_suhosin.= '<strong>' . $plugin_name . '</strong> non potrà essere utilizzato finché non si aumenta tale valore!';

        return $err_suhosin;
    }

    /**
     * Safely get and trim data from $_POST
     */
    function get_post( $key ) {

        return isset( $_POST[$key] ) ? trim( $_POST[$key] ) : '';
    }
}

endif; // ! class_exists( 'WC_Gateway_GestPay_Helper' )
