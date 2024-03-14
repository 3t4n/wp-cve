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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Settings_Tab_Gestpay' ) ) :

class WC_Settings_Tab_Gestpay {

    /**
     * Bootstraps the class and hooks required actions & filters.
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_settings_tab_gestpay', __CLASS__ . '::output' );
        add_action( 'woocommerce_update_options_settings_tab_gestpay', __CLASS__ . '::update_settings' );
    }

    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding this tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including this tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_gestpay'] = 'Gestpay for WooCommerce';
        return $settings_tabs;
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }

    /**
     * Get the real IP address of the current website so that it can be
     * used into the Gestpay backoffice.
     * It uses an external service to find out the IP address.
     */
    public static function get_IP_address() {
        $ip = wp_remote_retrieve_body( wp_remote_get( 'http://icanhazip.com/' ) );
        if ( preg_match( '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $ip ) ) {
            return 'Indirizzo IP da utilizzare nel backoffice di Gestpay: <b style="font-size:18px">' . $ip . '</b>';
        }

        return "Identificazione dell'indirizzo IP non riuscita. Contatta il tuo provider di hosting per conoscere l'indirizzo IP.";
    }

    private static function maybe_show_admin_errors() {
        $account = get_option( 'wc_gestpay_account_type' );
        $is_s2s = GESTPAY_PRO_TOKEN_AUTH == $account;
        $is_iframe = GESTPAY_PRO_TOKEN_IFRAME == $account;
        $is_tokenization = $is_s2s || $is_iframe;
        $save_token = $is_tokenization && "yes" == get_option( 'wc_gestpay_param_tokenization_save_token' );

        if ( ! $is_tokenization || ! class_exists( 'WC_Subscriptions' ) || ! class_exists( 'WC_Subscriptions_Admin' ) ) {
            // Not using tokenization.
            return;
        }

        if (  WC_Subscriptions::is_duplicate_site() ) {
            // @see https://docs.woocommerce.com/document/subscriptions-handles-staging-sites/
            ?>
<div class="error">
    <p>Attenzione! WooCommerce Subscriptions viene considerato come sito duplicato: i pagamenti automatici verranno considerati come rinnovi manuali e quindi falliranno.</p>
</div>
            <?php
        }

        $is_active = get_option( WC_Subscriptions_Admin::$option_prefix . '_is_active', false );
        if ( $is_active && ! $save_token ) {
            ?>
<div class="error">
    <p>Attenzione! WooCommerce Subscriptions è attivo ma GestPay è configurato per non memorizzare i Token: i pagamenti ricorrenti non potranno essere processati. Per poterli processare è necessario abilitare il salvataggio del Token.</p>
</div>
            <?php
        }
    }

    /**
     * Output the settings and add some custom JS.
     */
    public static function output() {

        self::maybe_show_admin_errors();

        WC_Admin_Settings::output_fields( self::get_settings() );

        ?>
        <script>(function($) {
            // Show/Hide the Pro and Token section
            $( '#wc_gestpay_account_type' ).change(function() {
                var selAccount = $( '#wc_gestpay_account_type option:selected' ).val();

                var $pro_table = $( '#wc_gestpay_param_buyer_email, #wc_gestpay_param_payment_types' ).closest( 'table' );
                var $pro_p = $pro_table.prev();
                var $pro_p_h2 = $pro_p.prev();
                var $token_table = $( '#wc_gestpay_param_tokenization_save_token' ).closest( 'table' );
                var $token_p = $token_table.prev();
                var $token_p_h2 = $token_p.prev();

                if ( selAccount == '0' ) { // Starter
                    $pro_table.hide();
                    $pro_p.hide();
                    $pro_p_h2.hide();
                    $token_table.hide();
                    $token_p.hide();
                    $token_p_h2.hide();
                }
                else if ( selAccount == '1' ) { // Pro
                    $pro_table.show();
                    $pro_p.show();
                    $pro_p_h2.show();
                    $token_table.hide();
                    $token_p.hide();
                    $token_p_h2.hide();
                }
                else { // On-Site and iFrame
                    $pro_table.show();
                    $pro_p.show();
                    $pro_p_h2.show();
                    $token_table.show();
                    $token_p.show();
                    $token_p_h2.show();
                }

                $( '#wc_gestpay_param_payment_types' ).trigger( 'change' );

            }).trigger( 'change' );

            // Payment types change
            $( '#wc_gestpay_param_payment_types' ).change(function() {
                var $payTypes = $( '#wc_gestpaypro_bon' ).closest( 'table' );
                var selAccount = $( '#wc_gestpay_account_type option:selected' ).val();

                if ( $(this).attr( 'checked' ) && selAccount != '0' ) {
                    $payTypes.prev().show();
                    $payTypes.show();
                }
                else {
                    $payTypes.prev().hide();
                    $payTypes.hide();
                }
            }).trigger( 'change' );

        })(jQuery);</script>
        <?php
    }

    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $url_doc = 'https://docs.gestpay.it/soap/getting-started/how-axerve-ecommerce-solutions-works/';
        $wcs = '<a href="https://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">WooCommerce Subscriptions</a>';

        $settings = array(

            // ------------------------------------------------- Main options
            array(
                'title' => 'Opzioni Gestpay - Axerve E-commerce Solutions',
                'desc' => '',
                'type' => 'title',
                'id' => 'section0',
            ),
            array(
                'title' => 'Versione account',
                'desc' => '<br>Seleziona la versione del tuo account Gestpay - Axerve E-commerce Solutions.'.
                    '<br>- La versione On-Site consente di effettuare i pagamenti nella pagina del checkout'.
                        ' e richiede che siano abilitati i servizi "Tokenization" e "Authorization". Sarà possibile utilizzare '. $wcs.
                    '<br>- La versione iFrame consente di effettuare i pagamenti nella pagina di pagamento di WooCommerce,'.
                        ' senza abbandonare il sito e richiede che sia abilitato il servizio "iFrame". Per utilizzare iFrame per gli'.
                        ' abbonamenti (con '.$wcs.') è necessario anche il servizio "Tokenization".'.
                        '<br><a href="https://www.gestpay.it/gestpay/pricing/index.html" target="_blank">Fai click qui</a> per maggiori informazioni sulle tipologie di account.',
                'default' => GESTPAY_STARTER,
                'type' => 'select',
                'options' => array(
                    GESTPAY_STARTER => "Gestpay Starter",
                    GESTPAY_PROFESSIONAL => "Gestpay Professional",
                    GESTPAY_PRO_TOKEN_AUTH => "Gestpay Professional On-Site",
                    GESTPAY_PRO_TOKEN_IFRAME => "Gestpay Professional iFrame",
                ),
                'id' => 'wc_gestpay_account_type',
            ),
            array(
                'title' => 'Gestpay Shop Login:',
                'type' => 'text',
                'desc' => "<br>Inserisci il tuo Shop Login fornito da Gestpay. Lo Shop Login è nella forma GESPAY12345 oppure 9012345, rispettivamente per l'ambiente di test e per quello di produzione.",
                'default' => '',
                'id' => 'wc_gestpay_shop_login',
            ),
            array(
                'title' => 'API Key:',
                'type' => 'password',
                'desc' => "<br>Inserisci opzionalmente l'API Key per abilitare l'autenticazione congiunta o alternativa a quella con indirizzo IP. <a href=\"".$url_doc."\" target=\"_blank\">Fai click qui per maggiori informazioni</a>",
                'default' => '',
                'id' => 'wc_gestpay_api_key',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'section0',
            ),

            // ------------------------------------------------- IP Address
            array(
                'title' => 'Indirizzo IP',
                'desc' => self::get_IP_address(),
                'type' => 'title',
                'id' => 'section1',
            ),
            array(
                'title' => 'URL per risposta positiva e negativa',
                'desc' => home_url( '/' ) . '?wc-api=WC_Gateway_Gestpay',
                'type' => 'title',
                'id' => 'section1a',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'section1',
            ),

            // ------------------------------------------------- Pro parameters
            array(
                'title' => 'Parametri opzionali di Gestpay Professional',
                'type' => 'title',
                'desc' => 'Nota: per abilitare/valorizzare tali parametri è necessario che siano stati abilitati anche nel backoffice di Gestpay, nella sezione "Campi&Parametri"',
                'id' => 'wc_gateway_gestpay_pro_parameters'
            ),
            array(
                'title' => 'Buyer E-mail:',
                'type' => 'checkbox',
                'default' => 'no',
                'id' => 'wc_gestpay_param_buyer_email',
            ),
            array(
                'title' => 'Buyer Name:',
                'type' => 'checkbox',
                'default' => 'no',
                'id' => 'wc_gestpay_param_buyer_name',
            ),
            array(
                'title' => 'Language:',
                'type' => 'checkbox',
                'default' => 'no',
                'desc' => "Permette di impostare automaticamente la lingua della pagina di pagamento di Gestpay (richiede qTranslate-X o WPML)",
                'id' => 'wc_gestpay_param_language',
            ),
            array(
                'title' => 'Paypal seller protection:',
                'type' => 'checkbox',
                'default' => 'no',
                'desc' => "Attivare solo se previsto dal contratto sottoscritto",
                'id' => 'wc_gestpay_param_seller_protection',
            ),
            array(
                'title' => 'Custom Info:',
                'type' => 'textarea',
                'desc' => "Inserisci le tue informazioni personalizzate come parametro=valore, uno per ogni riga. Lo spazio e i seguenti caratteri non sono ammessi: & § ( ) * < > , ; : *P1* / /* [ ] ? = %",
                'default' => '',
                'id' => 'wc_gestpay_param_custominfo',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_gateway_gestpay_pro_parameters',
            ),

            // ------------------------------------------------- More gateways
            array(
                'title' => 'Tipi di pagamento di Gestpay Professional',
                'type' => 'title',
                'desc' => 'È possibile aggiungere separatamente anche i pagamenti anche attraverso altri metodi di pagamento. Questi devo essere stati abilitati da Gestpay.',
                'id' => 'wc_gateway_gestpay_pro_parameters_payment_types'
            ),
            array(
                'title' => 'Payment Types:',
                'type' => 'checkbox',
                'label' => 'Abilita il parametro "Payment Types"',
                'default' => 'no',
                'desc' => 'Se si utilizza il multi-gateway questo campo deve essere selezionato',
                'id' => 'wc_gestpay_param_payment_types',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_gateway_gestpay_pro_parameters_payment_types',
            ),

            array(
                'title' => '',
                'desc' => 'Con Gestpay Professional è possibile aggiungere pagine di pagamento differenti per differenti metodi di pagamento.<br>Seleziona qui quali modilità di pagamento abilitare; poi salva e infine vai nel tab "Cassa" per vedere abilitati i tipi di pagamento selezionati.<br>Si faccia riferimento al manuale per maggiori informazioni. Nota: i metodi di pagamento selezionati devono essere abilitati anche nel Backoffice Gestpay.',
                'type' => 'title',
                'id' => 'wc_gestpaypro_moregateways_options',
            ),
            array(
                'desc' => 'Bonifico',
                'id' => 'wc_gestpaypro_bon',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'desc' => 'PayPal',
                'id' => 'wc_gestpaypro_paypal',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'desc' => 'PayPal Buy Now Pay Later',
                'id' => 'wc_gestpaypro_paypal_bnpl',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'desc' => 'MyBank',
                'id' => 'wc_gestpaypro_mybank',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'desc' => 'Consel',
                'id' => 'wc_gestpaypro_consel',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'desc' => 'MasterPass',
                'id' => 'wc_gestpaypro_masterpass',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'desc' => 'BancomatPay',
                'id' => 'wc_gestpaypro_bancomatpay',
                'class' => 'wc_gestpaypro_moregateways',
                'default' => 'no',
                'type' => 'checkbox',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_gestpaypro_moregateways_options',
            ),

            // ------------------------------------------------- Auth/iFrame options
            array(
                'title' => 'Impostazioni On-Site/iFrame',
                'type' => 'title',
                'desc' => '',
                'id' => 'wc_gateway_gestpay_pro_extra_options'
            ),
            array(
                // *** @deprecated - with 3DS 2.0 this is no longer useful, but is kept for compatibility ***
                'title' => 'Gestpay Shop Login Recurring:',
                'type' => 'text',
                'desc' => "<br>Inserisci il tuo Shop Login fornito da Gestpay per i soli pagamenti ricorrenti (3DS non abilitato).",
                'default' => '',
                'id' => 'wc_gestpay_shop_login_recurring',
            ),
            array(
                // *** @deprecated - with 3DS 2.0 this is no longer useful, but is kept for compatibility ***
                'title' => 'API Key Recurring:',
                'type' => 'password',
                'desc' => "<br>Inserisci opzionalmente l'API Key per abilitare l'autenticazione congiunta o alternativa a quella con indirizzo IP per Gestpay Shop Login Recurring. <a href=\"".$url_doc."\" target=\"_blank\">Fai click qui per maggiori informazioni</a>",
                'default' => '',
                'id' => 'wc_gestpay_api_key_recurring',
            ),
            array(
                'title' => 'Memorizza Token',
                'type' => 'checkbox',
                'desc' => 'Se selezionato memorizza il token della carta e consente all\'acquirente di riusare una carta precedentemente inserita (solo per la versione "On-site"), oltre che permettere di effettuare i pagamenti ricorrenti tramite '.$wcs.'. <strong>Se non selezionato i pagamenti ricorrenti con WooCommerce Subscriptions non potranno essere processati</strong>.<br>Verificare nel proprio account Gestpay, sezione "Campi&Parametri", che per il parametro "TOKEN" sia abiliato per la "Risposta" e per la "Risposta Web Service"',
                'id' => 'wc_gestpay_param_tokenization_save_token',
                'default' => 'no',
            ),
            array(
                'title' => "Token con Autorizzazione:",
                'type' => 'checkbox',
                'label' => "Imposta il parametro withAuth",
                'desc' => "Se selezionato il parametro withAuth sarà valorizzato con 'Y' (autorizzazione richiesta) altrimenti con 'N' (autorizzazione non richiesta); in sandbox è sempre valorizzato con 'N'.",
                'default' => 'yes',
                'id' => 'wc_gestpay_token_auth',
            ),
            array(
                'title' => 'CVV',
                'type' => 'checkbox',
                'desc' => 'Invia anche il campo CVV (Card Verification Value) quando viene effettuata la richiesta del token. ATTENZIONE: se il campo è impostato come <i>Input</i> nel Back Office di Gestpay, questa opzione deve essere selezionata altrimenti si otterrà un errore.',
                'id' => 'wc_gestpay_param_tokenization_send_cvv',
                'default' => 'no',
            ),
            array( // 2020-07
                'title' => 'Modalità separazione attiva',
                'type' => 'checkbox',
                'desc' => 'Seleziona se l\'account è impostato in modalità "separazione tra autorizzazione e conferma finanziaria".<br><b>Nel caso di contestualità tra autorizzazione e conferma finanziaria lasciare deselezionato in modo che lo stato dell\'ordine sia gestito in modo corretto</b>.',
                'id' => 'wc_gateway_gestpay_moto_sep',
                'default' => 'no',
            ),
            array( // 2020-07
                'title' => 'Stato ordine in modalità separazione',
                'type' => 'select',
                'desc' => 'Seleziona lo stato che verrà impostato quando viene completato un ordine: utilizza questa opzione <b>SOLO</b> se hai impostato la <b>separazione tra autorizzazione e conferma finanziaria</b> nel backoffice Axerve.<br>Di default è impostato su "<b>Completato/In Lavorazione</b>" (se il prodotto è virtuale andrà direttamente su Completato, altrimenti su In Lavorazione); scegli "<b>In attesa di pagamento</b>" (Pending) oppure "<b>In sospeso</b>" (On-Hold) a seconda di come preferisci gestire lo stato di un ordine in caso di separazione tra autorizzazione e movimentazione.<br>Se imposti manualmente lo stato su "In Lavorazione" (Processing) oppure su "Completato" (Completed) verrà effettuata una chiamata Server-to-Server per movimentare (Settle) automaticamente la transazione precedentemente autorizzata; in modo analogo, se imposti lo stato su "Cancellato" o "Fallito" la transazione verrà annullata (Cancelled).',
                'default' => 'completed',
                'options' => array(
                    'completed' => "Completato/In Lavorazione (Completed/Processing)",
                    'pending' => "In attesa di pagamento (Pending)",
                    'onhold' => "In sospeso (On-Hold)",
                ),
                'id' => 'wc_gateway_gestpay_order_status',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_gateway_gestpay_pro_extra_options',
            ),

            // ------------------------------------------------- Test
            array(
                'title' => "Test del Gateway",
                'type' => 'title',
                'desc' => '',
                'id' => 'wc_gestpay_testing',
            ),
            array(
                'title' => "Modalità sandbox/test:",
                'type' => 'checkbox',
                'label' => "Abilita la modalità di test quando selezionato.",
                'desc' => "Se selezionato (default), il checkout verrà processato con l'indirizzo di test, altrimenti con quello reale.",
                'default' => 'yes',
                'id' => 'wc_gestpay_test_url',
            ),
            array(
                'title' => 'Debug Log:',
                'type' => 'checkbox',
                'label' => "Abilita la registrazione degli eventi",
                'default' => 'yes',
                'desc' => 'Memorizza alcuni eventi di Gestpay nel file di log.',
                'id' => 'wc_gestpay_debug',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_gestpay_testing',
            ),

            // ------------------------------------------------- Experimental
            array(
                'title' => "Funzionalità sperimentali",
                'type' => 'title',
                'desc' => '',
                'id' => 'wc_gestpay_experimental',
            ),
            array(
                'title' => 'Forza Crypt HTTP',
                'type' => 'checkbox',
                'label' => ' ',
                'desc' => '<strong>Attenzione!</strong> Se selezionato, verrà forzata la richiesta di crittografia e decrittografia verso le URL non sicure. <strong>Utilizza questa opzione solo in caso di problemi con l\'aggiornamento del sistema per supportare TLS 1.2</strong>. Per maggiori informazioni visitare <a href="https://www.gestpay.it/dismissione-tls/" target="_blank">https://www.gestpay.it/dismissione-tls</a>.',
                'default' => 'no',
                'id' => 'wc_gestpay_force_crypt_http',
            ),
            array(
                'title' => 'Forza verifica risposta',
                'type' => 'checkbox',
                'label' => ' ',
                'desc' => 'Se selezionato, verrà forzata la verifica della risposta restituita da Gestpay. <strong>Si consiglia di utilizzare questa opzione solo in caso di problemi con l\'aggiornamento dello stato dell\'ordine</strong>.',
                'default' => 'no',
                'id' => 'wc_gestpay_force_check_gateway_response',
            ),
            array(
                'title' => "Forza ri-cifratura",
                'type' => 'checkbox',
                'label' => ' ',
                'default' => 'no',
                'desc' => "Se selezionato, verrà forzata la ri-cifratura: in alcuni casi può essere utile forzare la ri-cifratura della stringa inviata al server Gestpay. <strong>Attenzione: questa è una funzionalità sperimentale! Attivare questa funzione solo se si è consci di cosa si sta facendo.</strong>",
                'id' => 'wc_gestpay_force_recrypt',
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_gestpay_experimental',
            ),
        );

        return apply_filters( 'gestpay_settings_tab', $settings );
    }

}


endif; // class exists


WC_Settings_Tab_Gestpay::init();