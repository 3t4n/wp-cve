<?php

/**
 * Plugin Name: Gestpay for WooCommerce
 * Plugin URI: http://wordpress.org/plugins/gestpay-for-woocommerce/
 * Description: Abilita il sistema di pagamento GestPay by Axerve (Gruppo Banca Sella) in WooCommerce.
 * Version: 20240307
 * Author: Axerve (Gruppo Banca Sella)
 * Author URI: https://www.axerve.com
 *
 * WC requires at least: 3.0
 * WC tested up to: 7.1.0
 *
 * Copyright: © 2013-2016 Mauro Mascia (info@mauromascia.com)
 * Copyright: © 2017-2022 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/
 */

// Gestpay account types
define( 'GESTPAY_STARTER', 0 );
define( 'GESTPAY_PROFESSIONAL', 1 );
define( 'GESTPAY_PRO_TOKEN_AUTH', 2 );
define( 'GESTPAY_PRO_TOKEN_IFRAME', 3 );

// Easily find out the main file into subfolders.
define( 'GESTPAY_MAIN_FILE', __FILE__ );

// Define the slug used for the endpoint which handles the saved tokens.
define( 'GESTPAY_ACCOUNT_TOKENS_ENDPOINT', 'saved-cards' );

// Used to store the amount used to prevent errors with orders of 0€.
define( 'GESTPAY_ORDER_META_AMOUNT', '_wc_gestpay_fix_amount_zero' );

// Used to store the token.
define( 'GESTPAY_META_TOKEN', '_wc_gestpay_cc_token' );

// Used to store the transaction key, bank transaction id and auth code.
define( 'GESTPAY_ORDER_META_TRANS_KEY', '_wc_gestpay_s2s_transaction_key' );
define( 'GESTPAY_ORDER_META_BANK_TID', '_wc_gestpay_banktid' );
define( 'GESTPAY_ORDER_META_AUTH_CODE', '_wc_gestpay_authcode' );

define( 'GESTPAY_WC_API', 'WC_Gateway_Gestpay' );

// Immediately require these files
require_once 'inc/class-gestpay-endpoint.php';
require_once 'inc/class-wc-settings-tab-gestpay.php';
require_once 'inc/class-gestpay-cards.php';
require_once 'inc/class-gestpay-3DS2.php';

add_action( 'plugins_loaded', 'init_wc_gateway_gestpay' );
function init_wc_gateway_gestpay() {

    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {

        ?><div id="message" class="error">
            <p>Attenzione: è necessario installare e attivare <strong>WooCommerce</strong> per poter utilizzare <strong>Gestpay for WooCommerce</strong></p>
        </div><?php

        return;
    }

    /**
     * Add the gateway(s) to WooCommerce.
     */
    class WC_Gateway_Gestpay extends WC_Payment_Gateway {

        function __construct() {

            $this->set_this_gateway_params( 'Gestpay' );
            $this->paymentType = 'CREDITCARD';
            $this->Helper->init_gateway( $this );
            $this->set_this_gateway();
            $this->add_actions();
        }

        /**
         * Check compatibility requirements.
         *
         * @return boolean|array - TRUE if ok, array if error.
         */
        function is_valid_for_use() {

            if ( ! class_exists( 'WC_Payment_Gateways' ) ) {
                return array( 'error' => 'GestPay for WooCommerce richiede WooCommerce' );
            }

            if ( ! $this->Helper->check_fatal_soap( 'GestPay' ) ) {
                return array( 'error' => 'La libreria SOAP client di PHP deve essere abilitata' );
            }

            if ( ! $this->Helper->check_fatal_suhosin( 'GestPay', FALSE ) ) {
                return array( 'error' => $this->Helper->get_suhosin_error_msg( 'GestPay for WooCommerce' ) );
            }

            if ( ! version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
                return array( 'error' => 'GestPay for WooCommerce richiede WooCommerce versione >= 3.0.x' );
            }

            return TRUE;
        }

        /**
         * Checks if we are working with a certain payment type.
         */
        function is_payment_type_ok( $params ) {
            if ( $this->enabled == 'yes'
                && ! empty( $params->paymentTypes['paymentType'] )
                    && $params->paymentTypes['paymentType'] == $this->paymentType
            ) {
                return TRUE;
            }

            return FALSE;
        }

        /**
         * Set the gateway parameters.
         *
         * @param string $title
         */
        function set_this_gateway_params( $title = '' ) {

            // Add the helper class.
            include_once( 'inc/helper.php' );
            $this->Helper = new WC_Gateway_GestPay_Helper();

            $this->plugin_url   = $this->Helper->plugin_url;
            $this->plugin_path  = $this->Helper->plugin_path;
            $this->id           = strtolower( get_class( $this ) );
            $this->textdomain   = $this->Helper->plugin_slug;
            $this->logfile      = $this->id;
            $this->logo         = $this->plugin_url . '/images/gestpay-logo.png';
            $this->method_title = $title;
        }

        /**
         * Set user defined options.
         */
        function set_this_gateway() {

            $this->title          = $this->get_option( 'title' );
            $this->description    = $this->get_option( 'description' );

            $this->shopLogin      = get_option( 'wc_gestpay_shop_login' );
            $this->account        = get_option( 'wc_gestpay_account_type' );
            $this->apikey         = get_option( 'wc_gestpay_api_key' );

            $this->is_sandbox     = "yes" === get_option( 'wc_gestpay_test_url' );
            $this->force_recrypt  = "yes" === get_option( 'wc_gestpay_force_recrypt' );
            $this->force_check    = "yes" === get_option( 'wc_gestpay_force_check_gateway_response' );
            $this->debug          = "yes" === get_option( 'wc_gestpay_debug' );

            // 2020-07
            $this->is_moto_sep = "yes" === get_option( 'wc_gateway_gestpay_moto_sep' );
            $this->completed_order_status = get_option( 'wc_gateway_gestpay_order_status', 'completed' );

            // Check compatibility
            if ( $this->is_valid_for_use() !== TRUE ) {
                $this->enabled = 'no';
            }

            // Register base support for products
            $this->supports = array( 'products' );

            $this->is_s2s          = GESTPAY_PRO_TOKEN_AUTH == $this->account;
            $this->is_iframe       = GESTPAY_PRO_TOKEN_IFRAME == $this->account;
            $this->is_tokenization = $this->is_s2s || $this->is_iframe;

            // For token+auth output a payment_box containing the direct payment form
            $this->has_fields      = $this->is_s2s;

            $this->is_cvv_required = $this->is_tokenization && "yes" == get_option( 'wc_gestpay_param_tokenization_send_cvv' );
            $this->save_token      = $this->is_tokenization && "yes" == get_option( 'wc_gestpay_param_tokenization_save_token' );

            // Allow merchants to require or not the authorization of the cards (in prod).
            $this->token_with_auth = $this->is_sandbox || "no" === get_option( 'wc_gestpay_token_auth' ) ? 'N' : 'Y';

            // Acquire Pro parameters
            if ( $this->account != GESTPAY_STARTER ) {
                $this->param_buyer_email   = "yes" === get_option( 'wc_gestpay_param_buyer_email' );
                $this->param_buyer_name    = "yes" === get_option( 'wc_gestpay_param_buyer_name' );
                $this->param_language      = "yes" === get_option( 'wc_gestpay_param_language' );
                $this->param_payment_types = "yes" === get_option( 'wc_gestpay_param_payment_types' );
                $this->param_seller_protection = "yes" === get_option( 'wc_gestpay_param_seller_protection' );
                $this->param_custominfo    = get_option( 'wc_gestpay_param_custominfo' );

                // Add support for refunds
                array_push( $this->supports, 'refunds' );
            }

            if ( $this->save_token && $this->is_tokenization && $this->Helper->is_subscriptions_active() ) {

                $this->shopLoginRec = get_option( 'wc_gestpay_shop_login_recurring' );
                $this->apikeyRec    = get_option( 'wc_gestpay_api_key_recurring' );

                // Add support for subscriptions and subscription management functions
                $this->supports = array_merge( $this->supports,
                    array(
                        'subscriptions',
                        'subscription_cancellation',
                        'subscription_reactivation',
                        'subscription_suspension',
                        'subscription_amount_changes',
                        'subscription_payment_method_change', // Subscriptions 1.n compatibility
                        'subscription_payment_method_change_customer',
                        //'subscription_payment_method_change_admin', // Admin CAN'T change it.
                        'subscription_date_changes',
                        'multiple_subscriptions',
                        'default_credit_card_form',
                        //'tokenization',
                        'pre-orders'
                    )
                );
            }

            // Set process URLs to test or production.
            if ( $this->is_sandbox ) {
                // ------------------------------ Test
                $this->ws_url      = "https://sandbox.gestpay.net/gestpay/GestPayWS/WsCryptDecrypt.asmx?WSDL";
                $this->ws_S2S_url  = "https://sandbox.gestpay.net/gestpay/GestPayWS/WSs2s.asmx?WSDL";
                $this->payment_url = "https://sandbox.gestpay.net/pagam/pagam.aspx";
                $this->pagam3d_url = "https://sandbox.gestpay.net/pagam/pagam3d.aspx";
                $this->iframe_url  = "https://sandbox.gestpay.net/pagam/JavaScript/js_GestPay.js";
            }
            else {
                // ------------------------------ Production
                $this->ws_url      = "https://ecomms2s.sella.it/gestpay/GestPayWS/WSCryptDecrypt.asmx?WSDL";
                $this->ws_S2S_url  = "https://ecomms2s.sella.it/gestpay/GestPayWS/WSs2s.asmx?WSDL";
                $this->payment_url = "https://ecomm.sella.it/pagam/pagam.aspx";
                $this->pagam3d_url = "https://ecomm.sella.it/pagam/pagam3d.aspx";
                $this->iframe_url  = "https://ecomm.sella.it/pagam/JavaScript/js_GestPay.js";
            }

            // Use the old HTTP crypt if the merchant server is not TLS 1.2 compatible.
            if ( "yes" == get_option( 'wc_gestpay_force_crypt_http' ) ) {
                if ( $this->is_sandbox ) {
                    $this->ws_url = "http://sandbox.gestpay.net/crypthttp/WSCryptDecrypt.asmx?wsdl";
                }
                else {
                    $this->ws_url = "http://ecomms2s.sella.it/crypthttp/WSCryptDecrypt.asmx?wsdl";
                }
            }

            $this->ws_S2S_resp_url = rtrim(get_bloginfo( 'url' ), '/') . '/?wc-api=' . GESTPAY_WC_API;

            // Load the S2S actions for the order.
            include_once 'inc/class-gestpay-order-actions.php';
            $this->Order_Actions = new Gestpay_Order_Actions( $this );

            if ( $this->is_s2s ) {
                include_once 'inc/class-gestpay-s2s.php';
                $this->S2S = new Gestpay_S2S( $this );
            }
            elseif ( $this->is_iframe ) {
                include_once 'inc/class-gestpay-iframe.php';
                $this->IFrame = new Gestpay_Iframe( $this );
            }

            if ( ! $this->save_token ) {
                // No need the "Stored Cards" tab into "My Account".
                remove_filter( 'woocommerce_account_menu_items', array( 'Gestpay_Endpoint', 'new_menu_items' ) );
            }
        }


        /**
         * Add gateway actions.
         */
        function add_actions() {

            if ( $this->force_check ) {
                // This can be used to force the check of the response. Some website's may need that.
                $this->check_gateway_response();
            }

            add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
            add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_gateway_response' ) );
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

            if ( function_exists( 'is_checkout' ) && is_checkout() ) {
                // Include TLS js by Gestpay
                wp_enqueue_script( 'gestpay-TLSCHK_TE', 'https://sandbox.gestpay.net/pagam/javascript/TLSCHK_TE.js', array(), '201804', true );
                wp_enqueue_script( 'gestpay-TLSCHK_PRO', 'https://ecomm.sella.it/pagam/javascript/TLSCHK_PRO.js', array(), '201804', true );
                wp_enqueue_script( 'gestpay-checkBrowser', 'https://www.gestpay.it/checkbrowser/checkBrowser.js', array(), '201804', true );
            }

            add_action( 'woocommerce_review_order_before_payment', array( $this, 'check_tls12' ) );

            // Do not allow subscriptions payments with other payment types.
            add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), 99, 1 );
        }

        /**
         * Checks if the browser of the user is enabled to use TLS 1.2 and if not,
         * disables the place order button and shows the error message.
         * Be sure that the checked payment method is Gestpay, else we don't care
         * to check TLS for others (which may not be interessed to check for it).
         *
         * We need to directly print the JS becuase it depends on the method name.
         * Passing parameters to an external JS file does not seems to work.
         */
        function check_tls12() {

            if ( $this->is_s2s && $this->id === 'wc_gateway_gestpay' ) {
                // Don't do that with the S2S payment box
                return;
            }

?><script type="text/javascript">
jQuery( document.body ).on( 'updated_checkout payment_method_selected', function() {
    if ( typeof GestPay !== 'undefined' && typeof GestPay.ChkTLS !== 'undefined' && ! GestPay.ChkTLS.enabled ) {
        var method = "payment_method_" + '<?php echo $this->id; ?>';
        var tls_err_str = '<?php echo $this->strings['tls_text_error']; ?>';
        var button = jQuery( '#place_order[name="woocommerce_checkout_place_order"]' );
        var el = document.getElementsByClassName( 'payment_box ' + method );
        var buttonChecked = jQuery( 'input#' + method + ':checked' ).val();

        if ( el.length > 0 && typeof el[0] !== 'undefined' && buttonChecked ) {
            el[0].innerHTML = '<div class="gestpay-tls-error">' +
                tls_err_str +
                '<span id="UpdateLinks"><span><a target="_blank" href="https:\/\/windows.microsoft.com\/it-it\/internet-explorer\/download-ie"><img src="https:\/\/www.gestpay.it\/gestpay\/static\/checkbrowser\/IE10_white.png" alt="Internet Explorer"\/><\/a><\/span><span><a target="_blank" href="https:\/\/www.mozilla.org\/it\/firefox\/new\/"><img src="https:\/\/www.gestpay.it\/gestpay\/static\/checkbrowser\/firefox-icon_white.png" alt="Firefox"\/><\/a><\/span><span><a target="_blank" href="https:\/\/www.google.com\/chrome\/"><img src="https:\/\/www.gestpay.it\/gestpay\/static\/checkbrowser\/Chrome-icon_white.png" alt="Chrome"\/><\/a><\/span><\/span>' +
                "<\/div>";
            button.attr( 'disabled', true ).addClass( 'gestpay-disabled' ).unbind( 'mouseenter mouseleave' );
        }
        else {
            button.removeAttr( 'disabled' ).removeClass( 'gestpay-disabled' );
        }
    }
});
</script><?php

        }

        /**
         * Initialise other translatable strings.
         */
        function init_strings() {

            $this->strings = include 'inc/translatable-strings.php';
        }

        /**
         * Disable extra Gestpay payment types which doesn't support WC Subscriptions, because we can't get a Token.
         */
        function available_payment_gateways( $available_gateways ) {

            if ( $this->Helper->is_a_subscription() ) {
                if (is_array($available_gateways)) {
                    foreach ( $available_gateways as $gateway_id => $gateway ) {
                        if ( $gateway_id != 'wc_gateway_gestpay_paypal' && strpos( $gateway_id, 'wc_gateway_gestpay_' ) !== false ) {
                            unset( $available_gateways[ $gateway_id ] );
                        }
                    }
                }
            }

            return $available_gateways;
        }

        /**
         * Admin Panel Options
         */
        function admin_options() {

            echo '<h2>' . esc_html( $this->get_method_title() );
            wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
            echo '</h2>';

            $err = $this->is_valid_for_use();

            if ( is_array( $err ) && ! empty( $err['error'] ) ) : ?>

            <div class="inline error">
                <p><strong><?php _e( 'Gateway Disabled', 'woocommerce' ); ?></strong>: <?php echo $err['error']; ?></p>
            </div>

            <?php else : ?>

            <div class="gestpay-admin-main">
                <div class="gestpay-message">
                    <img src="<?php echo $this->logo; ?>" id="gestpay-logo"/>
                    <h3>
                        <a href="https://www.gestpay.it/" target="_blank">Gestpay</a> by <a href="https://www.axerve.com/" target="_blank">Axerve S.p.A. - Gruppo Banca Sella</a>
                    </h3>
                </div>
                <div class="gestpay-message gestpay-form">
                    <table class="form-table">
                        <?php
                        // Generate the HTML for the fields on the "settings" screen.
                        // This comes from class-wc-settings-api.php
                        $this->generate_settings_html();
                        ?>
                    </table>
                </div>
            </div>
            <?php

            endif;
        }

        /**
         * Output a payment box, maybe containing your direct payment form.
         */
        function payment_fields() {

            if ( $this->description ) {
                echo wpautop( wptexturize( wp_kses_post( __( $this->description ) ) ) );
            }

            if ( $this->is_s2s && $this->paymentType == 'CREDITCARD' ) {
                $this->S2S->payment_fields();
            }
        }

        public function validate_fields() {
            if ( $this->is_s2s && $this->paymentType == 'CREDITCARD' ) {
                return $this->S2S->validate_payment_fields();
            }
        }

        /**
         * Process the payment and return the result.
         *
         * @param int $order_id
         *
         * @return array
         */
        function process_payment( $order_id ) {

            $this->Helper->log_add( "################### [INFO][{$this->id}] Processing payment..." );

            $order = wc_get_order( $order_id );

            if ( $this->is_s2s && 'wc_gateway_gestpay' == $this->id ) {
                // ----------------------------------------- S2S
                $ret = $this->S2S->process_payment( $order );
            }
            elseif ( $this->is_iframe && 'wc_gateway_gestpay' == $this->id ) {
                // ----------------------------------------- IFRAME
                $ret = array(
                  'result'   => 'success',
                  'redirect' => $this->Helper->wc_url( 'pay', $order )
                );
            }
            else {
                // ----------------------------------------- REDIRECT

                $params = $this->get_ab_params( $order );

                if ( ! is_wp_error( $params ) && !empty( $params['b'] ) ) {
                    $ret = array(
                        'result'   => 'success',
                        'redirect' => $this->payment_url . '?a=' . $params['a'] . '&b=' . $params['b'], // don't use add_query_args!
                    );
                }
            }

            if ( ! is_wp_error( $ret ) && ! empty( $ret ) ) {
                return $ret;
            }

            // Failed
            return array(
                'result'   => 'failed',
                'redirect' => $this->Helper->wc_url( 'pay', $order )
            );
        }

        /**
         * Process a refund if supported.
         *
         * @param  int    $order_id
         * @param  float  $amount
         * @param  string $reason
         *
         * @return bool True or false based on success, or a WP_Error object
         */
        public function process_refund( $order_id, $amount = null, $reason = '' ) {

            return $this->Order_Actions->refund( $order_id, $amount, $reason );
        }

        /**
         * Generate the receipt page
         */
        function receipt_page( $order ) {

            $order = wc_get_order( $order );

            if ( $this->is_s2s && 'wc_gateway_gestpay' == $this->id ) {
                // ----------------------------------------- S2S
                $this->S2S->receipt_page( $order );
            }
            elseif ( $this->is_iframe && 'wc_gateway_gestpay' == $this->id ) {
                // ----------------------------------------- IFRAME
                $this->IFrame->receipt_page( $order );
            }
            else {
                // ----------------------------------------- REDIRECT
                $input_params = $this->get_ab_params( $order );

                if ( ! empty( $input_params ) ) {
                    $ret = $this->Helper->get_gw_form( $this->payment_url, $input_params, $order );

                    if ( $ret == FALSE ) {
                        $this->Helper->log_add( "[ERROR] Check the GestPay configuration." );
                    }
                    else {
                        echo $ret;
                    }
                }
            }
        }

        /**
         * Encrypt parameters using the GestPay Web Service
         *
         * @param array $params
         * @param int $order_id
         *
         * @return string
         */
        function gestpay_encrypt( $params, $order_id ) {

            // Create a SOAP client which uses the GestPay webservice and then encrypt values.
            try {
                $client = $this->Helper->get_soap_client( $this->ws_url );
                if ( empty( $client ) ) {
                    $this->Helper->log_add( $order, "Failed to load SOAP Client" );
                    return FALSE;
                }

                Gestpay_3DS2::add_3ds2_params( $params, $order_id, 'WSCryptDecrypt' );
                $this->Helper->log_add( '[GESTPAY ENCRYPT PARAMETERS]', $params );

                $objectresult = $client->Encrypt( $params );
                $xml = simplexml_load_string( $objectresult->EncryptResult->any );

                // Check if the encryption call can't be accepted.
                if ( $xml->TransactionResult == "KO" ) {
                    $err = sprintf( $this->strings['transaction_error'], $order_id, ' (' . $xml->ErrorCode . ') ' . $xml->ErrorDescription );
                    $this->Helper->wc_add_error( $err );
                    $this->Helper->log_add( '[ERROR] ' . $err );

                    return FALSE;
                }

                return $xml->CryptDecryptString;
            }
            catch ( Exception $e ) {
                $err = 'Fatal Error: Soap Client Request Exception with error ' . $e->getMessage();
                $this->Helper->wc_add_error( $err );
                $this->Helper->log_add( '[ERROR] ' . $err );

                return FALSE;
            }
        }

        /**
         * Check the Gestpay response, using the "a" and "b" parameters returned to the client.
         * The crypted string will be decrypted and the resulting parameters will be used to retrieve
         * the order so that it can be updated with the right status (completed/failed).
         */
        function check_gateway_response() {

            if ( $this->is_s2s ) {
                // On S2S with Card, we need to go to the phase 3.
                $this->S2S->phase_III_3D_Secure();

                /*
                We don't know if we are here because is a Card or PayPal payment,
                so the phase_III_3D_Secure() method will be called anyway but with
                a non-card-payment-type this will not be executed.
                With 3D secure cards will handle the last payment phase, ending with a die(),
                so that the following code will no be executed.
                */
            }

            if ( empty( $_GET['a'] ) || empty( $_GET['b'] ) ) {
                return;
            }

            // Check if the call comes from "URL Server to Server" or from a redirect.
            // In the first case we can skip some code.
            // @thanks to Fabrizio Gianneschi
            $is_gestpay_s2s_call = empty( $_SERVER['HTTP_USER_AGENT'] );

            if ( ! $is_gestpay_s2s_call ) {
                $this->Helper->log_add( "[INFO] Checking GestPay response..." );
            }
            else {
                $this->Helper->log_add( "[INFO] Checking S2S GestPay response..." );
            }

            $params = new stdClass();
            $params->shopLogin = $_GET['a'];
            $params->CryptedString = $_GET['b'];

            if ( ! empty( $this->apikey ) ) {
                $params->apikey = $this->apikey;
            }

            // Create a SOAP client using the GestPay webservice
            try {
                $this->Helper->log_add( "[INFO] Using WS URL: " . $this->ws_url );
                $client = $this->Helper->get_soap_client( $this->ws_url );
                if ( empty( $client ) ) {
                    $this->Helper->log_add( "Failed to load SOAP Client" );
                    die();
                }

                $objectresult = $client->Decrypt( $params );
            }
            catch ( Exception $e ) {
                $err = 'Fatal Error: Soap Client Exception with error ' . $e->getMessage();
                $this->Helper->log_add( "[ERROR] " . $err );
                die();
            }

            // Read the response and find the order_id.
            $xml = simplexml_load_string( $objectresult->DecryptResult->any );
            $raw_order_id = (string)$xml->ShopTransactionID;

            // Check if the order ID is correct.
            if ( empty( $raw_order_id ) ) {
                $err = "[ERROR] check_gateway_response - Order id is empty." . var_export( $xml, true );
                echo $err;
                $this->Helper->log_add( $err );
                die();
            }

            $err = "INFO " . var_export( $xml, true );
            $this->Helper->log_add( $err );

            // Retrieve the order id (if different) and the order object
            $order_id = $this->Helper->get_real_order_id( $raw_order_id );
            $order = wc_get_order( $order_id );

            if ( empty( $order ) ) {
                $err = "[ERROR] check_gateway_response - Order is empty." . var_export( $xml, true );
                echo $err;
                $this->Helper->log_add( $err );
                die();
            }

            if ( $order_id != $raw_order_id ) {
                // Makes a backup of the raw order id. This can be useful if the merchant
                // uses a plugin like WC Sequential Order Number Pro to change the order id
                // and after some time he disable it: this action will restore the original
                // Woocommerce ordering, loosing all the references between an order and the id
                // of a transaction in the Gestpay backoffice. At least we'll have the ability
                // to inspect the order meta to identify the original order id.
                update_post_meta( $order_id, '_gestpay_raw_order_id', $raw_order_id );
            }

            $order_status = $order->get_status();
            $not_already_completed = $order_status != 'completed' && $order_status != 'processing';

            do_action( 'gestpay_before_processing_order', $order );

            if ( (string)$xml->TransactionResult != "OK" ) {
                // ------ Transaction is failed (or is pending - XX - when using for example MyBank)

                if ( ! $is_gestpay_s2s_call ) {
                    $this->Helper->log_add( "[PAYMENT ERROR] Decrypted response:", $xml );
                }

                $err_str  = sprintf( $this->strings['transaction_error'], $order_id, ' (' . $xml->ErrorCode . ') ' . $xml->ErrorDescription );

                // #2020-07
                $is_abandoned = $xml->ErrorCode == '1143' && $xml->ErrorDescription == 'Transazione abbandonata dal compratore';
                $is_subscription_active = function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) && $order->has_status( 'active' );
                if ( $is_abandoned && $is_subscription_active ) {
                    // This is an abandoned change payment or also a manual renewal abandoned
                    // of an active subscription: we must not set the subscription to failed.
                    $this->Helper->log_add( "> SKIP > Cambio metodo pagamento abbandonato per una subscription attiva" );
                }
                elseif ( $not_already_completed && (string)$xml->TransactionResult == "XX" ) {
                    $mess = "XX Response";
                    $order->update_status( 'on-hold', $mess );
                    $this->Helper->log_add( "[INFO] " . $mess );
                    do_action( 'gestpay_after_order_pending', $order, $xml );
                }
                else {
                    $order->update_status( 'failed', $err_str );
                    $this->Helper->log_add( "[ERROR] " . $err_str );
                    do_action( 'gestpay_after_order_failed', $order, $xml );
                }
            }
            elseif ( $not_already_completed ) {

                // ------ Transaction OK! But do not process an already completed order.

                if ( $this->is_iframe ) {
                    $this->Helper->maybe_save_token( $order, $xml, '[iFrame] ' );
                }
                elseif ( $order->get_payment_method() == 'wc_gateway_gestpay_paypal' ) {
                    $this->Helper->maybe_save_token( $order, $xml, '[PayPal] ' );
                }

                $txn = $this->Helper->handle_transaction_details( $order, $order_id, $xml );

                $msg = sprintf( $this->strings['transaction_ok'], $order_id );

                $this->Helper->wc_order_completed( $order, $msg, $txn );

                do_action( 'gestpay_after_order_completed', $order, $xml );
            }

            if ( ! $is_gestpay_s2s_call ) {

                if ( $this->is_iframe ) {
                    $this->IFrame->delete_cookies();
                }

                $this->Helper->log_add( "[INFO] User has been redirected to the order received page, for order n. $order_id" );

                // Make a redirect to the right page.
                header( "Location: " . $this->Helper->wc_url( 'order_received', $order ) );
            }

            // Prevent multiple executions when invoking the return URL through the S2S URL.
            // This is also used after the header redirect.
            die();
        } // end check_gateway_response()

        /**
         * Output for the order received page.
         */
        function thankyou_page() {

            if ( $description = $this->get_description() ) {
                echo wpautop( wptexturize( wp_kses_post( $description ) ) );
            }

        }

        /**
         * Get parameters used when processing the payment.
         *
         * @param WC_Order $order
         *  The order.
         * @param bool|float $override_amount
         *  Used to allow the override of the amount, when subtracting the amount used to fix the 0€ payments.
         * @param bool $maybe_token
         *  Used to disable this parameter when there is a recurring payment.
         *  In fact, during recurring payments we don't want (and we can't get) a new token.
         *
         * @return array
         */
        function get_base_params( $order, $override_amount = FALSE, $maybe_token = TRUE ) {

            $order_id = $order->get_id();

            $this->Helper->log_add( "[INFO] Retrieving args for the order " . $order_id );

            // Define GestPay parameters
            $params = new stdClass();

            $params->shopLogin         = $this->shopLogin;
            $params->uicCode           = $this->Helper->get_order_currency( $order );
            $params->shopTransactionId = $this->Helper->get_transaction_id( $order_id );
            $params->amount            = $this->Helper->get_order_amount( $override_amount, $params->uicCode, $order );

            $this->Helper->log_add( "[INFO] Order amount is " . $params->amount );

            if ( ! empty( $this->apikey ) ) {
                $params->apikey = $this->apikey;
            }

            // Maybe add the PRO parameters.
            if ( $this->account != GESTPAY_STARTER ) {

                if ( $this->param_payment_types ) {
                    $params->paymentTypes = array(
                        'paymentType' => $this->paymentType
                    );
                }

                if ( $this->param_buyer_email ) {
                    // MAX 50 chars
                    $email = substr( $order->get_billing_email(), 0, 50 );
                    $params->buyerEmail = $this->Helper->get_clean_param( $email );
                }

                if ( $this->param_buyer_name ) {
                    // MAX 50 chars
                    $name = $this->Helper->get_clean_param( $order->get_billing_first_name() ) . ' ';
                    $name.= $this->Helper->get_clean_param( $order->get_billing_last_name() );
                    $params->buyerName = substr( $name, 0, 50 );
                }

                if ( $this->param_language ) {
                    $params->languageId = $this->Helper->get_language();
                }

                if ( ! empty( $this->param_custominfo ) ) {
                    $params->customInfo = $this->Helper->get_custominfo( $this->param_custominfo );
                }

                if ( $maybe_token && $this->save_token ) {
                    $params->requestToken = "MASKEDPAN";
                }

                // Allow altering parameters (Consel uses this)
                $params = apply_filters( 'gestpay_encrypt_parameters', $params, $order );
            }

            return $params;
        }

        /**
         * Get parameters used when processing the payment.
         *
         * @param WC_Order $order
         *
         * @return array
         */
        function get_ab_params( $order ) {

            $params = $this->get_base_params( $order );

            if ( is_wp_error( $params ) ) {
                return FALSE;
            }

            $order_id = $order->get_id();
            $crypted_string = $this->gestpay_encrypt( $params, $order_id );
            if ( empty( $crypted_string ) && !$this->force_recrypt ) {
                return FALSE;
            }

            $this->Helper->log_add( "[INFO] crypted string: {$this->strings['crypted_string']} $crypted_string" );

            // Experimental feature to prevent encrypt errors.
            if ( $this->force_recrypt ) {
                $this->Helper->log_add( "[WARNING] " . $this->strings['crypted_string_info'] );

                if ( $crypted_string ) {
                    /* Check if the string contains asterisks: sometimes, for a not well identified case,
                    and not on all sites, the webservice returns a string which is identified as invalid
                    from the webservice itself. After N retries (limited to 50, to avoid a potential infinite
                    loop) a valid string is created. For that reason this is an optional experimental feature. */
                    $i = 1;
                    while ( strpos( $crypted_string, '*' ) !== FALSE && $i < 50 ) {
                        $crypted_string = $this->gestpay_encrypt( $params, $order_id );
                        $this->Helper->log_add( "[INFO] crypted string: {$this->strings['crypted_string']} [$i] $crypted_string" );
                        $i ++;
                    }
                }
            }

            return array(
                "a" => $this->shopLogin,
                "b" => $crypted_string
            );
        }

    } // end class WC_Gateway_Gestpay


    // Add GestPay and other payment types.
    include_once 'inc/gestpay-pro-payment-types.php';

} // end init_wc_gateway_gestpay()


/**
 * Check the gateway response.
 *
 * All active payment types will go through this function, using the first available
 * GestPay class.
 */
//add_action( 'init', 'wc_gateway_gestpay_check_gateway_response', 999 );
//function wc_gateway_gestpay_check_gateway_response() {
//
//    if ( ! empty( $_GET['wc-api'] ) && $_GET['wc-api'] == GESTPAY_WC_API ) {
//        $Gestpay = new WC_Gateway_Gestpay();
//        $Gestpay->check_gateway_response();
//    }
//}


/**
 * Add this ajax action to listen for the settle call
 */
add_action( 'wp_ajax_gestpay_settle_s2s', 'wc_gateway_gestpay_ajax_settle_s2s' );
function wc_gateway_gestpay_ajax_settle_s2s() {

    $Gestpay = new WC_Gateway_Gestpay();
    $Gestpay->Order_Actions->ajax_settle();
}


/**
 * Add this ajax action to listen for the delete call
 */
add_action( 'wp_ajax_gestpay_delete_s2s', 'wc_gateway_gestpay_ajax_delete_s2s' );
function wc_gateway_gestpay_ajax_delete_s2s() {

    $Gestpay = new WC_Gateway_Gestpay();
    $Gestpay->Order_Actions->ajax_delete();
}

/**
 * Add this action to listen for the order status manually changed
 */
add_action( 'woocommerce_order_edit_status', 'wc_gateway_gestpay_woocommerce_order_edit_status', 10, 2 );
function wc_gateway_gestpay_woocommerce_order_edit_status( $order_id, $new_status ) {

    $Gestpay = new WC_Gateway_Gestpay();
    $Gestpay->Order_Actions->wc_order_edit_status( $order_id, $new_status );
}

