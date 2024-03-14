<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Gateway_Gestpay_MYBANK extends WC_Gateway_Gestpay {

    public function __construct() {

        $this->set_this_gateway_params( 'Gestpay MyBank' );
        $this->paymentType = 'MYBANK';
        $this->Helper->init_gateway( $this );
        $this->set_this_gateway();

        // These MUST be fixed as MyBank requirements.
        $this->title = 'MyBank';
        $this->description = '';
        $this->has_fields = true; // required to display the content of payment fields.
        $this->icon = $this->plugin_url . '/images/MyBank_logo_positive.jpg';

        // Bank selection is required on mobile. Can be also required on desktop if configured.
        $this->required_selection = wp_is_mobile() || "yes" == $this->get_option( 'param_mybank_select_required_on_desktop' );

        $this->add_actions();

        if ( $this->required_selection ) {
            add_filter( 'gestpay_encrypt_parameters', array( $this, 'add_mybank_encrypt_parameters' ), 10, 2 );
        }

        add_action( 'woocommerce_order_details_before_order_table_items', array( $this, 'show_mybank_logo_after_payment' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue additional Javascript
     */
    public function enqueue_scripts() {

        $js_mybank = $this->Helper->plugin_url . 'lib/gestpay-mybank.js';
        wp_enqueue_script( 'selectWoo' );
        wp_enqueue_style( 'select2' );

        /*
        Da "Manuale di convenzioni redazionali per gli esercenti v20190228"
        = 5.3.6 Regole relative alla logica di ricerca =
        L'elenco delle banche DEVE essere filtrato in base alle parole inserite
        dall'Acquirente. Le parole vengono separate dall'Acquirente utilizzando uno spazio.
        L'elenco delle banche DEVE prevedere tutte le parole inserite dall'Acquirente
        con tutti gli alias presenti nell'elenco in base alla logica di ricerca AND, in altre
        parole: tutti i testi di ricerca DEVONO essere presenti nei risultati di ricerca.
        La ricerca NON SARÀ eseguita rispettando maiuscole/minuscole (NDR: sarà quindi case insensitive).
        Il filtro DEVE prevedere le porzioni di parola e non solo le parole complete.
        Ad es., l'inserimento di "BAN" DEVE dare come risultato le voci contenenti "BANCA", "BANK", ecc.
        */
        wp_enqueue_script( 'gestpay-for-woocommerce-mybank-js', $js_mybank, array( 'jquery', 'selectWoo' ), '201904', true );
    }

    /**
     * Retrieve the list of available banks.
     *
     * @see https://api.gestpay.it/#callmybanklists2s
     */
    public function get_mybanks() {

        $lang = $this->Helper->get_language();
        $banks = get_site_transient( 'gestpay_mybank_list_' . $lang );

        if ( ! empty( $banks ) ) {
            return $banks;
        }

        try {
            $client = $this->Helper->get_soap_client( $this->ws_S2S_url );
            if ( empty( $client ) ) {
                $this->Helper->log_add( "Failed to load SOAP Client" );
                return FALSE;
            }

            // Set up the parameters
            $params = new stdClass();
            $params->shopLogin = $this->shopLogin;
            $params->languageId = $lang;

            if ( ! empty( $this->apikey ) ) {
                $params->apikey = $this->apikey;
            }

            $objectresult = $client->callMyBankListS2S( $params );
            $xml = simplexml_load_string( $objectresult->CallMyBankListS2SResult->any );
            $xml = $xml->callMyBankS2SResult->GestPayS2S;

            // Check for errors
            if ( $xml->TransactionResult == "KO" ) {
                $err = '[callMyBankListS2S] Error (' . $xml->ErrorCode . ') ' . $xml->ErrorDescription;
                $this->Helper->wc_add_error( $err );
                $this->Helper->log_add( '[ERROR] ' . $err );

                return array( 'error_message' => $err, 'is_error' => true );
            }

            $b = (array)$xml->BankList;
            $banks = array();
            foreach ( (array)$b['Bank'] as $bank ) {
                $banks[(string)$bank->BankCode] = (string)$bank->BankName;
            }

            // Store data for 12 hours to prevent too many calls
            set_site_transient( 'gestpay_mybank_list_' . $lang, $banks, 60*60*12 );

            return $banks;
        }
        catch ( Exception $e ) {
            $err = 'Fatal Error: callMyBankListS2S Request Exception with error ' . $e->getMessage();
            $this->Helper->wc_add_error( $err );
            $this->Helper->log_add( '[ERROR] ' . $err );

            return array( 'error_message' => $err, 'is_error' => true );
        }
    }

    /**
     * Rewrite the payment_fields() to apply MyBank requirements.
     */
    function payment_fields() {

        echo '<div id="mybank-container">';

        $mybank_url = '<a href="https://mybank.eu" target="_blank" title="MyBank"><strong>mybank.eu</strong></a>';
        $mybank_banner = '<div id="mybank-container-img"><a href="https://mybank.eu" target="_blank"><img src=""https://www.mybank.eu/brand/mybank-tagline-positive-it.png" style="background-color:#fff!important"/></a></div>';
        echo $mybank_banner . '<p>' . $this->strings['mybank_payoff'] . ' ' . $mybank_url . '</p>';

        if ( $this->required_selection ) {
            $banks = $this->get_mybanks();

            if ( is_array( $banks ) ) {
                if ( empty( $banks['is_error'] ) ) {
                    $this->show_banks_as_select( $banks );
                }
                else {
                    echo $banks['error_message'];
                }
            }
        }

        echo '</div>';
    }

    /**
     * Returns the select-options HTML for the bank list conforming to MyBank requirements.
     */
    public function show_banks_as_select( $banks ) {

        echo '<p><i>'. $this->strings['gestpay_mybank_list_intro'] . '</i>:</p>';
        echo '<p><select name="gestpay_mybank_bank" class="woocommerce-select" id="gestpay-mybank-banklist">';
        echo '<option value="">--- ' . __( 'Choose an option', 'woocommerce' ) . ' ---</option>';
        foreach ( $banks as $bank_code => $bank_name ) {
            echo '<option value="' . $bank_code . '">' . $bank_name . '</option>';
        }
        echo '</select> <span class="required">*</span></p>';
        echo '<p><a href="https://mybank.eu/faq/" target="_blank">' . $this->strings['gestpay_mybank_list_notfound'] . '</a></p>';
    }

    /**
     * Add parameters for MyBank.
     * @see https://api.gestpay.it/#encrypt-example-ideal-and-mybank
     */
    public function add_mybank_encrypt_parameters( $params, $order ) {

        if ( $this->is_payment_type_ok( $params ) ) {

            if ( empty( $_POST['gestpay_mybank_bank'] ) ) {
                $err = $this->strings['gestpay_mybank_list_must'];

                if ( function_exists( 'wc_add_notice' ) ) {
                    wc_add_notice( $err, 'error' );
                }

                return new WP_Error( 'gestpay-mybank-error', $err );
            }

            $params->paymentTypeDetail = array(
                'MyBankBankCode' => $_POST['gestpay_mybank_bank']
            );
        }

        return $params;
    }


    /**
     * If the order is paid with MyBank show the logo, as MyBank requirements.
     */
    public function show_mybank_logo_after_payment( $order ) {

        if ( $order->get_payment_method() != 'wc_gateway_gestpay_mybank' ) {
            return;
        }

        echo '<a href="https://mybank.eu/" target="_blank"><img src=""https://www.mybank.eu/brand/mybank-tagline-positive-it.png" /></a>';
    }

}
