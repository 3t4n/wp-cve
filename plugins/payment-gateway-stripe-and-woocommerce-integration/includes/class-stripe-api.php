<?php

if (!defined('ABSPATH')) {
    exit;
}

use Automattic\WooCommerce\Utilities\OrderUtil;
#[\AllowDynamicProperties]
class EH_Stripe_Payment extends WC_Payment_Gateway {

    public function __construct() {
        $this->id = 'eh_stripe_pay';
        $this->method_title = __('Credit/Debit Cards', 'payment-gateway-stripe-and-woocommerce-integration');
        $this->has_fields = true;
        $this->supports = array(
            'products',
           'tokenization',
           'add_payment_method',              
            'refunds',
        );
        $this->init_form_fields();
        $this->init_settings();
        $this->enabled = $this->get_option('enabled');
        $this->title = __($this->get_option('title'), 'payment-gateway-stripe-and-woocommerce-integration');
        $this->description = __($this->get_option('description'), 'payment-gateway-stripe-and-woocommerce-integration');
        $this->eh_stripe_order_button = $this->get_option('eh_stripe_order_button');
        $this->eh_stripe_mode = $this->get_option('eh_stripe_mode');
        $this->eh_stripe_test_secret_key = $this->get_option('eh_stripe_test_secret_key');
        $this->eh_stripe_test_publishable_key = $this->get_option('eh_stripe_test_publishable_key');
        $this->eh_stripe_live_secret_key = $this->get_option('eh_stripe_live_secret_key');
        $this->eh_stripe_live_publishable_key = $this->get_option('eh_stripe_live_publishable_key');
        $this->eh_stripe_capture = 'yes' === $this->get_option('eh_stripe_capture', 'yes');
        $this->eh_stripe_checkout_cards = $this->get_option('eh_stripe_checkout_cards') ? $this->get_option('eh_stripe_checkout_cards') : array();
        $this->eh_stripe_enforce_cards = 'yes' === $this->get_option('eh_stripe_enforce_cards', 'yes');
        $this->eh_stripe_email_receipt = 'yes' === $this->get_option('eh_stripe_email_receipt', 'yes');
        $this->eh_stripe_apple_pay = 'yes' === $this->get_option('eh_stripe_apple_pay', 'yes');
        $this->eh_stripe_apple_color = $this->get_option('eh_stripe_apple_color');
        $this->eh_stripe_form_description = $this->get_option('eh_stripe_form_description');
        $this->order_button_text = __($this->eh_stripe_order_button, 'payment-gateway-stripe-and-woocommerce-integration');
        $this->eh_stripe_inline_form = 'yes' === $this->get_option('eh_stripe_inline_form', 'yes');
        $this->eh_stripe_enable_inline_form  = true;
        $this->eh_stripe_save_cards = $this->get_option('eh_stripe_save_cards');

        $this->method_description = sprintf(__("Accepts Stripe payments via credit or debit card.", 'payment-gateway-stripe-and-woocommerce-integration')." <p><a target='_blank' href='https://www.webtoffee.com/woocommerce-stripe-payment-gateway-plugin-user-guide/#credit_debit'>  ".__('Read documentation', 'payment-gateway-stripe-and-woocommerce-integration')." </a> </p> ");

        if ('test' === $this->eh_stripe_mode) {
            $this->description = $this->description . sprintf('<br>' . '<strong>' . __('Stripe TEST MODE Enabled: ', 'payment-gateway-stripe-and-woocommerce-integration') . '</strong>' . __(' Use these ', 'payment-gateway-stripe-and-woocommerce-integration') . '<a href="https://stripe.com/docs/testing" target="_blank">' . __(' Test Card Details ', 'payment-gateway-stripe-and-woocommerce-integration') . '</a>' . __(' for Testing.', 'payment-gateway-stripe-and-woocommerce-integration'));
            $this->description = trim($this->description);
        }

         // Set stripe API key.
        \Stripe\Stripe::setApiKey( self::get_stripe_api_key() );
        \Stripe\Stripe::setApiVersion( self::wt_get_api_version() );
        \Stripe\Stripe::setAppInfo( 'WordPress payment-gateway-stripe-and-woocommerce-integration', EH_STRIPE_VERSION, 'https://wordpress.org/plugins/payment-gateway-stripe-and-woocommerce-integration/', 'pp_partner_KHip9dhhenLx0S' );

        if (is_admin()) {
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }
        
        // Hooks
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

        // update the redirection URL for payment intent verififcation
        add_filter( 'woocommerce_payment_successful_result', array( $this, 'modify_successful_payment_result' ), 99999, 2 );
        
        add_action('before_woocommerce_pay',array( $this, 'verify_payment_intent_verification_in_order_pay' ));
        add_action( 'set_logged_in_cookie', array( $this, 'eh_set_cookie_on_current_request' ) );

        add_action( 'admin_enqueue_scripts', array($this,'wp_enqueue_media_seprate_loader' ));

    }

    /**
     * function to make sure that the function wp_enqueue_media() is called only after loading all the necessary files.
     * 
     */
    public function wp_enqueue_media_seprate_loader(){

        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        $tab = (isset($_GET['tab'])) ? esc_attr($_GET['tab']) : false;
        $section = (isset($_GET['section'])) ? esc_attr($_GET['section']) : false;
        if ('wc-settings' != $page && 'checkout' != $tab && 'eh_stripe_pay' != $section)
            return;

        wp_enqueue_media();

    }
   
    /**
     * function to get stripe api key.
     */
    public static function get_stripe_api_key(){

        $stripe_settings  = get_option( 'woocommerce_eh_stripe_pay_settings' );
        if(isset($stripe_settings['eh_stripe_mode'])){
            $mode = $stripe_settings['eh_stripe_mode'];

            if ('test' === $mode) {
                
                return $stripe_settings['eh_stripe_test_secret_key'];
            } else {
                return $stripe_settings['eh_stripe_live_secret_key'];
            }
        }
    }

    /**
     * Processes and saves options.
     * @since 3.4.2
     */
    public function process_admin_options(){

        parent::process_admin_options();

        $eh_stripe = get_option("woocommerce_eh_stripe_pay_settings");
       
        if(isset($eh_stripe['eh_stripe_enforce_cards']) && ($eh_stripe['eh_stripe_enforce_cards'] === 'no')){

           $eh_stripe['eh_stripe_checkout_cards'] = array( 'mastercard','visa','diners','discover','amex', 'jcb');
           $eh_stripe['eh_stripe_enforce_cards'] = '';

        }
        update_option('woocommerce_eh_stripe_pay_settings',$eh_stripe);
    }
    
    /**
     * function to add Licence activation window.
     */
    public function admin_options() {
      
        $plugin_name = 'stripepaymentgateway';
        parent::admin_options();
    }
    
    /**
     * Get stripe activated payment cards icon.
     */
    public function get_icon() {
        $ext = version_compare(WC()->version, '2.6', '>=') ? '.svg' : '.png';
        $style = version_compare(WC()->version, '2.6', '>=') ? 'style="margin-left: 0.3em"' : '';
        $icon = '';

        if ((in_array('visa', $this->eh_stripe_checkout_cards)) || (in_array('Visa', $this->eh_stripe_checkout_cards))) {
            $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/visa' . $ext) . '" alt="Visa" width="32" title="VISA" ' . $style . ' />';
        }
        if ((in_array('mastercard', $this->eh_stripe_checkout_cards)) || (in_array('MasterCard', $this->eh_stripe_checkout_cards))){
            $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard' . $ext) . '" alt="Mastercard" width="32" title="Master Card" ' . $style . ' />';
        }
        if ((in_array('amex', $this->eh_stripe_checkout_cards)) || (in_array('American Express', $this->eh_stripe_checkout_cards))){
            $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/amex' . $ext) . '" alt="Amex" width="32" title="American Express" ' . $style . ' />';
        }
        if ('USD' === get_woocommerce_currency()) {
            if ((in_array('discover', $this->eh_stripe_checkout_cards)) || (in_array('Discover', $this->eh_stripe_checkout_cards))){
                $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/discover' . $ext) . '" alt="Discover" width="32" title="Discover" ' . $style . ' />';
            }
            if ((in_array('jcb', $this->eh_stripe_checkout_cards)) || (in_array('JCB', $this->eh_stripe_checkout_cards))){
                $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/jcb' . $ext) . '" alt="JCB" width="32" title="JCB" ' . $style . ' />';
            }
            if ((in_array('diners', $this->eh_stripe_checkout_cards)) || (in_array('Diners Club', $this->eh_stripe_checkout_cards))){
                $icon .= '<img src="' . WC_HTTPS::force_https_url(WC()->plugin_url() . '/assets/images/icons/credit-cards/diners' . $ext) . '" alt="Diners" width="32" title="Diners Club" ' . $style . ' />';
            }
        }
        if ($this->eh_stripe_apple_pay) {
            $icon .= '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/apple-pay.png') . '" alt="Apple Pay" width="32" title="Apple Pay" ' . $style . ' />';
        }
        return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
    }
    
    /**
     * Checks if gateway should be available to use.
     */
    public function is_available() {
        $enable = true;
        if ('yes' === $this->enabled) {
            if (!$this->eh_stripe_mode && is_checkout()) {
                $enable = false;
            }
            if ('test' === $this->eh_stripe_mode) {
                if (!isset($this->eh_stripe_test_secret_key) || !isset($this->eh_stripe_test_publishable_key) || !$this->eh_stripe_test_secret_key || !$this->eh_stripe_test_publishable_key) {
                    $enable =  false;
                }
            } else {
                if (!isset($this->eh_stripe_live_secret_key) || !isset($this->eh_stripe_live_publishable_key) || !$this->eh_stripe_live_secret_key || !$this->eh_stripe_live_publishable_key) {
                    $enable =  false;
                }
            }
        }
        else{
            $enable = false;
        }
        return apply_filters('wt_stripe_gateway_available', $enable);
    }
    
    /**
     * Initialize form fields.
     */
    public function init_form_fields() {
        $this->form_fields = include( 'eh-stripe-settings-page.php' );
       
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        $tab = (isset($_GET['tab'])) ? esc_attr($_GET['tab']) : false;
        $section = (isset($_GET['section'])) ? esc_attr($_GET['section']) : false;
        if ('wc-settings' != $page && 'checkout' != $tab && 'eh_stripe_pay' != $section)
            return;

        
        
        wc_enqueue_js("
                    $('.description').css({'font-style':'normal'});
                    $('.eh-css-class').css({'border-top': 'dashed 1px #ccc','padding-top': '5px','width': '65%'}); 
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_test_secret_key, #woocommerce_eh_stripe_pay_eh_stripe_live_secret_key ' ).attr('autocomplete','new-password');
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_statement_descriptor, #woocommerce_eh_stripe_pay_eh_stripe_live_secret_key ' ).attr('maxlength','22');
                    jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_mode' ).on( 'change', function() {
                                    var test    = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_test_publishable_key, #woocommerce_eh_stripe_pay_eh_stripe_test_secret_key' ).closest( 'tr' ),
                                    live = jQuery( '#woocommerce_eh_stripe_pay_eh_stripe_live_publishable_key, #woocommerce_eh_stripe_pay_eh_stripe_live_secret_key' ).closest( 'tr' );

                                    if ('test' === jQuery( this ).val()) {
                                            test.show();
                                            live.hide();
                                    } else {
                                            test.hide();
                                            live.show();
                                    }
                            }).change();
                    
            ");
    }

    /**
     * Outputs scripts used for stripe payment.
     */
    public function payment_scripts() { 

        $load_script_on_product = $this->is_load_script('product');
        $load_script_on_product = apply_filters('wt_load_script_on_product', $load_script_on_product);
        if (is_product()  && ! $load_script_on_product ) {
            return;
        }

        $load_script_on_cart = $this->is_load_script('cart');
        $load_script_on_cart = apply_filters('wt_load_script_on_cart', $load_script_on_cart);
        if ( is_cart() && !$load_script_on_cart) {
            return;
        }
        
        if(!$this->is_available() && ! $load_script_on_product && !$load_script_on_cart && !$this->is_load_script('checkout')){
            return false;
        }

        if ( (is_checkout() || is_product() || is_cart() || is_add_payment_method_page() || apply_filters('wt_stripe_load_script', false))  && !is_order_received_page()) {
            wp_register_script('stripe_v3_js', 'https://js.stripe.com/v3/');

            $this->tokenization_script();
            wp_enqueue_script('eh_stripe_checkout', plugins_url('assets/js/eh_stripe_checkout.js', EH_STRIPE_MAIN_FILE), array('stripe_v3_js','jquery'),EH_STRIPE_VERSION, true);
            if ('test' == $this->eh_stripe_mode) {
                $public_key = $this->eh_stripe_test_publishable_key;
            } else {
                $public_key = $this->eh_stripe_live_publishable_key;
            }

            $show_zip_code = apply_filters('eh_stripe_ccshow_zipcode',true);
            $stripe_params = array(
                'key' => $public_key,
                'show_zip_code' => $show_zip_code,
                'i18n_terms' => __('Please accept the terms and conditions first', 'payment-gateway-stripe-and-woocommerce-integration'),
                'i18n_required_fields' => __('Please fill in required checkout fields first', 'payment-gateway-stripe-and-woocommerce-integration'),
            );
            $stripe_params['card_elements_options']                   = apply_filters(
                'eh_stripe_sepa_elements_options',
                array(
                    'supportedCountries' => array( 'SEPA' ),
                    'placeholderCountry' => WC()->countries->get_base_country(),
                    'style'              => array( 'base' => array( 'fontSize' => '15px' ) ),
                    'card_number_placeholder' =>  __('1234 1234 1234 1234', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'card_expiry_placeholder' =>  __('MM / YY', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'card_cvc_placeholder' =>  __('CVC', 'payment-gateway-stripe-and-woocommerce-integration'),
                )
            );

            $stripe_params['elements_options']                        = apply_filters( 'eh_stripe_elements_options', array() );
            $stripe_params['stripe_enable_inline_form']               = $this->eh_stripe_enable_inline_form; 
            $stripe_params['is_checkout']                             = ( (is_checkout() || apply_filters('wt_stripe_load_script', false)) && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no';
            $stripe_params['enabled_inline_form']                     = $this->eh_stripe_inline_form ? 'yes' : 'no';
            $stripe_params['inline_postalcode']                       = apply_filters('hide_inline_postal_code', true);

            // If we're on the pay page we need to pass stripe.js the address of the order.
            if ( isset( $_GET['pay_for_order'] ) && 'true' === $_GET['pay_for_order'] ) {

                $order     = wc_get_order( absint( get_query_var( 'order-pay' ) ) );
                $order_id  = method_exists($order, 'get_id') ? $order->get_id() : $order->id;

                if ( is_a( $order, 'WC_Order' ) ) {
                    $stripe_params['billing_first_name'] = method_exists($order, 'get_billing_first_name') ? $order->get_billing_first_name() : $order->billing_first_name;
                    $stripe_params['billing_last_name']  = method_exists($order, 'get_billing_last_name')  ? $order->get_billing_last_name()  : $order->billing_last_name;
                    $stripe_params['billing_address_1']  = method_exists($order, 'get_billing_address_1')  ? $order->get_billing_address_1()  : $order->billing_address_1;
                    $stripe_params['billing_address_2']  = method_exists($order, 'get_billing_address_2')  ? $order->get_billing_address_2()  : $order->billing_address_2;
                    $stripe_params['billing_state']      = method_exists($order, 'get_billing_state')      ? $order->get_billing_state()      : $order->billing_state;
                    $stripe_params['billing_city']       = method_exists($order, 'get_billing_city')       ? $order->get_billing_city()       : $order->billing_city;
                    $stripe_params['billing_postcode']   = method_exists($order, 'get_billing_postcode')   ? $order->get_billing_postcode()   : $order->billing_postcode;
                    $stripe_params['billing_country']    = method_exists($order, 'get_billing_country')    ? $order->get_billing_country()    : $order->billing_country;
                }                       
            }
            $stripe_params['version'] = self::wt_get_api_version(); 
            wp_localize_script('eh_stripe_checkout', 'eh_stripe_val', apply_filters('eh_stripe_val', $stripe_params));
        }
    }

    /**
     *Payment form on checkout page.
     */
    public function payment_fields() {
        //check it support saved card feature
        $is_support_saved_cards = $this->supports( 'tokenization' ) && is_checkout() && $this->eh_stripe_save_cards == 'yes';

        $user = wp_get_current_user();
        if ($user->ID) {
            $user_email = get_user_meta($user->ID, 'billing_email', true);
            $user_email = $user_email ? $user_email : $user->user_email;
        } else {
            $user_email = '';
        }
        echo '<div class="status-box">';

        if ($this->description) {
            echo apply_filters('eh_stripe_desc', wpautop(wp_kses_post("<span>" . $this->description . "</span>")));
        }
        echo "</div>";
        $pay_button_text = __('Pay', 'payment-gateway-stripe-and-woocommerce-integration');
        if (is_checkout_pay_page()) {
            $order_id = get_query_var('order-pay');
            $order = wc_get_order($order_id);
            $email = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
            $order_total = apply_filters("wt_stripe_order_total_before_payment", ((WC()->version < '2.7.0') ? $order->order_total : $order->get_total()));

            echo '<div
                id="eh-stripe-pay-data"
                data-panel-label="' . esc_attr($pay_button_text) . '"
                data-description="' . esc_attr($this->eh_stripe_form_description) . '"
                data-email="' . esc_attr(($email !== '') ? $email : get_bloginfo('name', 'display')) . '"
                data-amount="' . esc_attr($this->get_stripe_amount($order_total)) . '"
                data-name="' . esc_attr(sprintf(get_bloginfo('name', 'display'))) . '"
                data-currency="' . esc_attr(((WC()->version < '2.7.0') ? $order->order_currency : $order->get_currency())) . '">';

            echo $this->elements_form();
            echo '</div>';

        } else {


            $order_total = apply_filters("wt_stripe_cart_total_before_payment", WC()->cart->total);

            echo '<div
                id="eh-stripe-pay-data"
                data-panel-label="' . esc_attr($pay_button_text) . '"
                data-description="' . esc_attr($this->eh_stripe_form_description) . '"
                data-email="' . esc_attr($user_email) . '"
                data-amount="' . esc_attr($this->get_stripe_amount($order_total)) . '"
                data-name="' . esc_attr(sprintf(get_bloginfo('name', 'display'))) . '"
                data-currency="' . esc_attr(strtolower(get_woocommerce_currency())) . '">';

             if($is_support_saved_cards){
                $this->tokenization_script();
                $this->saved_payment_methods();
            }
           echo $this->elements_form();
            if (  ($is_support_saved_cards ) && ! is_add_payment_method_page()) { // wpcs: csrf ok.

                 $this->save_payment_method_checkbox();
            }           
            echo '</div>';
        }
    }

    /**
     *Renders stripe elements on payment form.
     */
    public function elements_form() {
        ?>
        <fieldset id="eh-<?php echo esc_attr( $this->id ); ?>-cc-form" class="eh-credit-card-form eh-payment-form wc-payment-form" style="background:transparent;">
            <?php do_action( 'eh_woocommerce_credit_card_form_start', $this->id ); ?>

            <?php if($this->eh_stripe_inline_form){ ?>

                <div id="eh-stripe-card-element" class="eh-stripe-elements-field" style="padding:5px 10px;">
                    <!-- a Stripe Element will be inserted here. -->
                </div>

            <?php }else{ ?>
            
                <div class="form-row form-row-wide">
                    <label for="eh-stripe-card-element"><?php esc_html_e( 'Card Number', 'payment-gateway-stripe-and-woocommerce-integration' ); ?> <span class="required">*</span></label>
                    <div class="stripe-card-group">
                        <div id="eh-stripe-card-element" class="eh-stripe-elements-field">
                        <!-- a Stripe Element will be inserted here. -->
                        </div>
                    </div>
                </div>

                <div class="form-row form-row-first">
                    <label for="eh-stripe-exp-element"><?php esc_html_e( 'Expiry Date', 'payment-gateway-stripe-and-woocommerce-integration' ); ?> <span class="required">*</span></label>

                    <div id="eh-stripe-exp-element" class="eh-stripe-elements-field">
                    <!-- a Stripe Element will be inserted here. -->
                    </div>
                </div>

                <div class="form-row form-row-last">
                    <label for="eh-stripe-cvc-element"><?php esc_html_e( 'Card Code (CVC)','payment-gateway-stripe-and-woocommerce-integration' ); ?> <span class="required">*</span></label>
                <div id="eh-stripe-cvc-element" class="eh-stripe-elements-field">
                <!-- a Stripe Element will be inserted here. -->
                </div>
                </div>
                <?php } ?>
                <div class="clear"></div>

            <!-- Used to display form errors -->
            <div class="stripe-source-errors" role="alert" style="color:#ff0000"></div>
            <br />
            <?php do_action( 'eh_woocommerce_credit_card_form_end', $this->id ); ?>
            <div class="clear"></div>
        </fieldset>
        <?php
    }

    /**
     *List of zero decimal currencies supported by stripe.
     */
    public static function zerocurrency()  {
        return array("BIF", "CLP", "DJF", "GNF", "JPY", "KMF", "KRW", "MGA", "PYG", "RWF", "VUV", "XAF", "XOF", "XPF", "VND");
    }
    
    /**
     *Gets stripe amount.
     */
    public  static function get_stripe_amount($total, $currency = '') {
        if (!$currency) {
            $currency = get_woocommerce_currency();
        }
        if (in_array(strtoupper($currency), self::zerocurrency())) {
            // Zero decimal currencies
            $total = absint($total);
        } else {
            $total = round($total, 2) * 100; // In cents
        }
        return $total;
    }

    /**
     *Reset stripe amount after charge response.
     */
    public function reset_stripe_amount($total, $currency = '') {
        if (!$currency) {
            $currency = get_woocommerce_currency();
        }
        if (in_array(strtoupper($currency), self::zerocurrency())) {
            // Zero decimal currencies
            $total = absint($total);
        } else {
            $total = round($total, 2) / 100; // In cents
        }
        return $total;
    }
    
     /**
     *Gets client details.
     */
    public function get_clients_details() {
        return array(
            'IP' => $_SERVER['REMOTE_ADDR'],
            'Agent' => $_SERVER['HTTP_USER_AGENT'],
            'Referer' => $_SERVER['HTTP_REFERER']
        );
    }
    
   
     /**
     *Gets details for stripe charge creation.
     */
    public function get_charge_details( $wc_order, $token, $order_type, $client, $card_brand, $currency, $amount,$customer = '' ) {
        $product_name = array();
        foreach ($wc_order->get_items() as $item) {
            array_push($product_name, $item['name']);
        }

        $charge = array(
            'payment_method' => $token,
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => array(
                'order_id' => $wc_order->get_id(),
                'Total Tax' => $wc_order->get_total_tax(),
                'Total Shipping' => $wc_order->get_total_shipping(),
                'Customer IP' => $client['IP'],
                'Agent' => $client['Agent'],
                'Referer' => $client['Referer'],
                'WP customer #' => (WC()->version < '2.7.0') ? $wc_order->user_id : $wc_order->get_user_id(),
                'Billing Email' => (WC()->version < '2.7.0') ? $wc_order->billing_email : $wc_order->get_billing_email()
            ),
            'description' => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' Order #' . $wc_order->get_order_number(),
        );
        
        if( $this->get_option('eh_stripe_statement_descriptor') ) {
            
            $statement_descriptor = $this->get_option('eh_stripe_statement_descriptor');
            $statement_descriptor =  ( strlen( $statement_descriptor < 22 ) ) ? $statement_descriptor : substr( $statement_descriptor ,0,22);            
            $charge['statement_descriptor'] = $statement_descriptor;
        }

        if ((isset($_REQUEST['wc-' . $this->id . '-new-payment-method']) && $_REQUEST['wc-' . $this->id . '-new-payment-method'] == true)) {
            $charge['setup_future_usage'] = 'off_session';
        }

        $charge['customer'] = $customer;

        $product_list = implode(' | ', $product_name);

        $charge['metadata']['Products'] = substr($product_list, 0, 499);
        
        
        $show_items_details = apply_filters('eh_stripe_show_items_in_payment_description', false);
                
        if($show_items_details){
            
            $charge['description']=$charge['metadata']['Products'] .' '.wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' Order #' . $wc_order->get_order_number();
        }
        $charge['confirm'] =  true ;

        if ('other' != $card_brand) { 
            $charge['capture_method'] = $this->eh_stripe_capture ? 'automatic' : 'manual'; 
        }
        if ($this->eh_stripe_email_receipt) {
            $charge['receipt_email'] = (WC()->version < '2.7.0') ? $wc_order->billing_email : $wc_order->get_billing_email();
        }
        if (!is_checkout_pay_page()) {
            $charge['shipping'] = array(
                'address' => array(
                    'line1' => (WC()->version < '2.7.0') ? $wc_order->shipping_address_1 : $wc_order->get_shipping_address_1(),
                    'line2' => (WC()->version < '2.7.0') ? $wc_order->shipping_address_2 : $wc_order->get_shipping_address_2(),
                    'city' => (WC()->version < '2.7.0') ? $wc_order->shipping_city : $wc_order->get_shipping_city(),
                    'state' => (WC()->version < '2.7.0') ? $wc_order->shipping_state : $wc_order->get_shipping_state(),
                    'country' => (WC()->version < '2.7.0') ? $wc_order->shipping_country : $wc_order->get_shipping_country(),
                    'postal_code' => (WC()->version < '2.7.0') ? $wc_order->shipping_postcode : $wc_order->get_shipping_postcode()
                ),
                'name' => ((WC()->version < '2.7.0') ? $wc_order->shipping_first_name : $wc_order->get_shipping_first_name()) . ' ' . ((WC()->version < '2.7.0') ? $wc_order->shipping_last_name : $wc_order->get_shipping_last_name()),
                'phone' => (WC()->version < '2.7.0') ? $wc_order->billing_phone : $wc_order->get_billing_phone(),
            );
        }
       return apply_filters('eh_stripe_payment_intent_args', $charge);
    }
    
     /**
     *Creates charge parameters from charge response.
     */
    public function make_charge_params($charge_value, $order_id) {
        $wc_order = wc_get_order($order_id);
        $charge_data = json_decode(json_encode($charge_value));
        $origin_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
        $charge_parsed = array(
            "id" => $charge_data->id,
            "amount" => $this->reset_stripe_amount($charge_data->amount, $charge_data->currency),
            "amount_refunded" => $this->reset_stripe_amount($charge_data->amount_refunded, $charge_data->currency),
            "currency" => strtoupper($charge_data->currency),
            "order_amount" => (WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total(),
            "order_currency" => (WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency(),
            "captured" => $charge_data->captured ? "Captured" : "Uncaptured",
            "transaction_id" => $charge_data->balance_transaction,
            "mode" => (false == $charge_data->livemode) ? 'Test' : 'Live',
            "metadata" => $charge_data->metadata,
            "created" => date('Y-m-d H:i:s', $charge_data->created),
            "paid" => $charge_data->paid ? 'Paid' : 'Not Paid',
            "receiptemail" => (null == $charge_data->receipt_email) ? 'Receipt not send' : $charge_data->receipt_email,
            "receiptnumber" => (null == $charge_data->receipt_number) ? 'No Receipt Number' : $charge_data->receipt_number,
            "source_type" => ('card' == $charge_data->payment_method_details->type ) ? ($charge_data->payment_method_details->card->brand . "( " . $charge_data->payment_method_details->card->funding . " )") : (( 'alipay' == $charge_data->payment_method_details->type ) ? 'Alipay' : (( 'sepa_debit' == $charge_data->payment_method_details->type ) ? 'Sepa Debit' : (('klarna' == $charge_data->payment_method_details->type ) ? 'Klarna' : (('afterpay_clearpay' == $charge_data->payment_method_details->type ) ? 'Afterpay' : (('wechat_pay' == $charge_data->payment_method_details->type ) ? 'WeChat' : ('bacs_debit' == $charge_data->payment_method_details->type ? 'Bacs Debit' : ('sofort' == $charge_data->payment_method_details->type ? 'Sofort' : ('ideal' == $charge_data->payment_method_details->type ? 'iDEAL' : ('bancontact' == $charge_data->payment_method_details->type ? 'Bancontact' : ('eps' == $charge_data->payment_method_details->type ? 'EPS' : ('p24' == $charge_data->payment_method_details->type ? 'P24' : ('becs' == $charge_data->payment_method_details->type ? 'BECS' :  ('boleto' == $charge_data->payment_method_details->type ? 'Boleto' : ('oxxo' == $charge_data->payment_method_details->type ? 'OXXO' : ('grabpay' == $charge_data->payment_method_details->type ? 'Grabpay' : ('multibanco' == $charge_data->payment_method_details->type ? 'Multibanco' : ('giropay' == $charge_data->payment_method_details->type ? 'Giropay' : ('affirm' == $charge_data->payment_method_details->type ? 'Affirm' : 'Undefined') ) ) ))))))))))))))),
            "status" => $charge_data->status,
            "origin_time" => $origin_time
        );
        $trans_time = date('Y-m-d H:i:s', time() + ((get_option('gmt_offset') * 3600) + 10));
        $tranaction_data = array(
            "id" => $charge_data->id,
            "total_amount" => $charge_parsed['amount'],
            "currency" => $charge_parsed['currency'],
            "balance_amount" => 0,
            "origin_time" => $trans_time
        );
        if (0 === count(EH_Helper_Class::wt_stripe_order_db_operations($order_id, null, 'get', '_eh_stripe_payment_balance', null, false))) {
            if ($charge_parsed['captured'] === 'Captured') {
                $tranaction_data['balance_amount'] = $charge_parsed['amount'];
            }
            EH_Helper_Class::wt_stripe_order_db_operations($order_id, $wc_order, 'add', '_eh_stripe_payment_balance', $tranaction_data, false);
        } else {
            $tranaction_data['balance_amount'] = $charge_parsed['amount'];
            EH_Helper_Class::wt_stripe_order_db_operations($order_id, $wc_order, 'update', '_eh_stripe_payment_balance', $tranaction_data, false);
        }
        return $charge_parsed;
    }
    
     /**
     *Creates refund parameters from refund response .
     */
    public function make_refund_params($refund_value, $amount, $currency, $order_id) {
        $refund_data = json_decode(json_encode($refund_value));
        $origin_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
        $refund_parsed = array(
            "id" => $refund_data->id,
            "object" => $refund_data->object,
            "amount" => $this->reset_stripe_amount($refund_data->amount, $refund_data->currency),
            "transaction_id" => $refund_data->balance_transaction,
            "currency" => strtoupper($refund_data->currency),
            "order_amount" => $amount,
            "order_currency" => $currency,
            "metadata" => $refund_data->metadata,
            "created" => date('Y-m-d H:i:s', $refund_data->created + get_option('gmt_offset') * 3600),
            "charge_id" => $refund_data->charge,
            "receiptnumber" => (null == $refund_data->receipt_number) ? 'No Receipt Number' : $refund_data->receipt_number,
            "reason" => $refund_data->reason,
            "status" => $refund_data->status,
            "origin_time" => $origin_time
        );
        $trans_time = date('Y-m-d H:i:s', time() + ((get_option('gmt_offset') * 3600) + 10));
        $transaction_data = EH_Helper_Class::wt_stripe_order_db_operations($order_id, null, 'get', '_eh_stripe_payment_balance', null, true);
        $balance = floatval($transaction_data['balance_amount']) - floatval($refund_parsed['amount']);
        $transaction_data['balance_amount'] = $balance;
        $transaction_data['origin_time'] = $trans_time;
        EH_Helper_Class::wt_stripe_order_db_operations($order_id, null, 'update', '_eh_stripe_payment_balance', $transaction_data, false);
        return $refund_parsed;
    }
    

    public function create_stripe_customer($token, $order, $user_email = false, $user_obj = null) {
        if(!empty($order)){
            $order_no = $order->get_order_number();
            $params = array(
                'description' => "Customer for Order #" . $order_no,
                "email" => $user_email,
                "payment_method" => $token,
                "address" => array(
                    'city' => method_exists($order, 'get_billing_city') ? $order->get_billing_city() : $order->billing_city,
                    'country' => method_exists($order, 'get_billing_country') ? $order->get_billing_country() : $order->billing_country,
                    'line1' => method_exists($order, 'get_billing_address_1') ? $order->get_billing_address_1() : $order->billing_address_1,
                    'line2' => method_exists($order, 'get_billing_address_2') ? $order->get_billing_address_2() : $order->billing_address_2,
                    'postal_code' => method_exists($order, 'get_billing_postcode') ? $order->get_billing_postcode() : $order->billing_postcode,
                    'state' => method_exists($order, 'get_billing_state') ? $order->get_billing_state() : $order->billing_state,
                ),
                'name' => (method_exists($order, 'get_billing_first_name') ? $order->get_billing_first_name() : $order->billing_first_name) . ' '. (method_exists($order, 'get_billing_last_name')  ? $order->get_billing_last_name()  : $order->billing_last_name),
            );
        
        }
        else{
            $fname = (isset($user_obj->user_firstname) ?  $user_obj->user_firstname : '');
            $lname = (isset($user_obj->user_lastname) ?  $user_obj->user_lastname : '');
            $params = array(
                'description' => 'Added manually',
                'name' => $fname . " " . $lname,
                "email" => $user_email,
                "payment_method" => $token,
            );
        
        }
        $params = apply_filters("wt_stripe_alter_customer_request", $params);
        $response = \Stripe\Customer::create($params);

        if (empty($response->id)) {
            return false;
        }

        return $response;
    }

    /**
	 * Proceed with current request using new login session (to ensure consistent nonce).
	 */
	public function eh_set_cookie_on_current_request( $cookie ) {
		$_COOKIE[ LOGGED_IN_COOKIE ] = $cookie;
	}
    
    /**
     *Process stripe payment.
     */
    public function process_payment($order_id) {
        global $wp;
        $wc_order = wc_get_order($order_id);
        try {

            $card_brand =  isset( $_POST['eh_stripe_card_type'] )? sanitize_text_field($_POST['eh_stripe_card_type']) : 'other';
            $currency = isset($_POST['eh_stripe_pay_currency']) ? sanitize_text_field($_POST['eh_stripe_pay_currency']) : get_woocommerce_currency();
            $amount = isset( $_POST['eh_stripe_pay_amount'] )? sanitize_text_field($_POST['eh_stripe_pay_amount']) : self::get_stripe_amount(((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total())) ;
            $client = $this->get_clients_details();

            $process_auth = true;

            /**
             * 
             */

            $this->eh_stripe_checkout_cards = apply_filters('wt_stripe_add_new_card_brand_filter', $this->eh_stripe_checkout_cards);

             /**
             * 
             */


            
            if (!in_array($card_brand, $this->eh_stripe_checkout_cards)) {

                $process_auth = false; 

                if('visa' === $card_brand){
                    $card_brand = 'Visa';
                }
                if('mastercard' === $card_brand){
                    $card_brand = 'MasterCard';
                }
                if('amex' === $card_brand){
                    $card_brand = 'American Express';
                }
                if('jcb' === $card_brand){
                    $card_brand = 'JCB';
                }
                if('diners' === $card_brand){
                    $card_brand = 'Diners Club';
                }
                if('discover' === $card_brand){
                    $card_brand = 'Discover';
                }
                if(in_array($card_brand, $this->eh_stripe_checkout_cards)){
                $process_auth = true; 
                }
            }

            //checks if restrict cards option was unchecked before, if uncheked allows all cards to process.
            $eh_stripe = get_option("woocommerce_eh_stripe_pay_settings");
            if(isset($eh_stripe['eh_stripe_enforce_cards']) && ($eh_stripe['eh_stripe_enforce_cards'] === 'no')){
                $process_auth = true; 
            }
                   
            $user = wp_get_current_user();
            $logged_in_userid = $user->ID;

            //if payment using restriced card and not a saved card
            if (!$this->payment_via_saved_card() && !$process_auth) {
                $user = wp_get_current_user();
                $enforce_detail = array (
                    'name' => get_user_meta($user->ID, 'first_name', true),
                    'email' => $user->user_email,
                    'phone' => get_user_meta($user->ID, 'billing_phone', true),
                    'type' => "card_error",
                    'card' => $card_brand,
                    'token' => $token,
                    'message' => __("Admin declined the payment due to Card Restriction.", 'payment-gateway-stripe-and-woocommerce-integration')
                );
              
                $eh_brands = implode(",",$this->eh_stripe_checkout_cards);
                wc_add_notice(__('Card brand', 'payment-gateway-stripe-and-woocommerce-integration').' (' . $card_brand . ") " . __('has been restricted for payment by the seller. Please reload and try with another card from the accepted list', 'payment-gateway-stripe-and-woocommerce-integration').' ('.__($eh_brands,'payment-gateway-stripe-and-woocommerce-integration').')', $notice_type = 'error');
                EH_Stripe_Log::log_update('dead', $enforce_detail, get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
                return array (
                    'result' => 'failure'
                );
            }
            //check whether payment via saved card
            elseif ($this->payment_via_saved_card()) {
               
                $wc_token_id = isset( $_POST[ 'wc-' . $this->id . '-payment-token' ] ) ? wc_clean( wp_unslash( $_POST[ 'wc-' . $this->id  . '-payment-token' ] ) ) : '';

                //get wc token obejct using id
                $wc_token    = WC_Payment_Tokens::get( $wc_token_id );

                //check whether card details belongs to the logged in user
                if (empty($wc_token) || $wc_token->get_user_id() !== $logged_in_userid) {
                    throw new Exception(__("Invalid card. Please select another card or input a new card number", 'payment-gateway-stripe-and-woocommerce-integration'));
                    
                }

                //get customer token of saved card
                $token = $wc_token->get_token();
                $card_brand = $wc_token->get_card_type();
                $payment_method = $token;
                 $customer = get_user_meta($logged_in_userid, '_stripe_customer_id', true);
                if (!$payment_method || !$customer) {
                    throw new Exception(__("Invalid card. Please select another card or input a new card number", 'payment-gateway-stripe-and-woocommerce-integration'));
                 } 

            } 
             //if payment using a new card
            else{     
                $token = sanitize_text_field($_POST['eh_stripe_pay_token']);
                $payment_method = sanitize_text_field($_POST['eh_stripe_pay_token']);
                $customer = false;

                 //if saved card check is enabled, check for existing stripe customer
                if ($this->should_save_this_card()) { 
                
                   //check customer token is exist for the logged in user
                    $customer = get_user_meta($logged_in_userid, '_stripe_customer_id', true);
                }

                 //create stripe customer 
                if (empty($customer)) { 
                    $customer = $this->create_stripe_customer( $payment_method, $wc_order, ((WC()->version < '2.7.0') ? $wc_order->billing_email : $wc_order->get_billing_email()) );

                    //if error occured
                    if(isset($customer->error) && !empty($customer->error)){
                        throw new Exception($customer->error);
                        
                    }  
                    
                    //if saved card check is enabled, check for existing stripe customer
                    if ($this->should_save_this_card()) { 
                        //saved stripe customer for charging saved cards later
                        update_user_meta($logged_in_userid, "_stripe_customer_id", $customer->id);
                        
                    }
                    $customer = $customer->id;
                }                 
            }

            $order_type = 'card';
            try {
                $payment_intent_args  = $this->get_charge_details($wc_order, $token, $order_type, $client, $card_brand, $currency, $amount,$customer);
                
                $intent = $this->get_payment_intent_from_order( $wc_order );
                if(! empty($intent)){

                    if ( $intent->status === 'succeeded' ) {
                        wc_add_notice(__('An error has occurred internally, due to which you are not redirected to the order received page. Please contact support for more assistance.', 'payment-gateway-stripe-and-woocommerce-integration'), $notice_type = 'error');
                        wp_redirect(wc_get_checkout_url());
                    }else{
                        $intent = \Stripe\PaymentIntent::create( $payment_intent_args , array(
                            'idempotency_key' => $wc_order->get_order_key().'-'.$payment_method
                        ));
                    }
                }else{
                    $intent = \Stripe\PaymentIntent::create( $payment_intent_args , array(
                        'idempotency_key' => $wc_order->get_order_key().'-'.$payment_method
                    ));
                }
                    
                //if save card option enabled save the customer token
                if($this->should_save_this_card()){
                     $this->save_cards_for_later($intent->charges->data[0]);
                }              

                 $this->save_payment_intent_to_order( $wc_order, $intent );
                
                 EH_Helper_Class::wt_stripe_order_db_operations($order_id, $wc_order, 'add', '_eh_stripe_payment_intent', $intent->id, false);  

                if ($intent->status == 'requires_action' &&
                    $intent->next_action->type == 'use_stripe_sdk') {
                    # Tell the client to handle the action
                    $this->unlock_order_payment( $wc_order );
                    if ( is_wc_endpoint_url( 'order-pay' ) ) {
                        $redirect_url = add_query_arg( 'eh-stripe-confirmation', 1, $wc_order->get_checkout_payment_url( false ) );

                        return array(
                            'result'   => 'success',
                            'redirect' => $redirect_url,
                        );
                    } else {

                        /**
                         * This URL contains only a hash, which will be sent to `checkout.js` where it will be set like this:
                         * `window.location = result.redirect`
                         * Once this redirect is sent to JS, the `onHashChange` function will execute `handleCardPayment`.
                         */

                        return array(
                            'result'        => 'success',
                            'redirect'      => $this->get_return_url( $wc_order ),
                            'intent_secret' => $intent->client_secret,
                            'createaccount' => (int) ! empty( $_POST['createaccount'] ), // WPCS: input var ok, CSRF ok.
                        );
                    }
                }    
                 else {
                      return $this->process_order(   end( $intent->charges->data ),$wc_order );
                 }

            }
            catch (\Stripe\Error\Card $e) {
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->process_error(  $wc_order,$err['param'], $err, 'failed' );
                return;
            }
        } catch (Exception $error) {
            $wc_order->update_status( 'failed' );
            $user = wp_get_current_user();
            $user_detail = array(
                'name' => get_user_meta($user->ID, 'first_name', true),
                'email' => $user->user_email,
                'phone' => get_user_meta($user->ID, 'billing_phone', true),
            );

            if (method_exists($error, 'getJsonBody')) {
                $oops = $error->getJsonBody();
                $error_message = $oops['error']['message'];
            } else {
                $oops = array('message' => $error->getMessage());
                $error_message = $error->getMessage();
            }

            wc_add_notice(__('Payment Failed ', 'payment-gateway-stripe-and-woocommerce-integration') . "( " . $error_message . " )." . __('Refresh and try again', 'payment-gateway-stripe-and-woocommerce-integration'), $notice_type = 'error');
            EH_Stripe_Log::log_update('dead', array_merge($user_detail, $oops), get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
            return array (
                'result' => 'failure'
            );
        }
    }

    // add payment method via my account page invoke this function
    public function add_payment_method() {
        
        try{

            if ( !isset( $_POST['eh_stripe_pay_token'] ) && empty( $_POST['eh_stripe_pay_token'] ) || ! is_user_logged_in() ) {
                throw new Exception(__("There was a problem adding the payment method.", 'payment-gateway-stripe-and-woocommerce-integration'));
                
            }
            $payment_method = $_POST['eh_stripe_pay_token'];
            $current_user_obj = wp_get_current_user();

            $logged_in_userid = get_current_user_id();
           //check customer token is exist for the logged in user
            $customer = get_user_meta($logged_in_userid, '_stripe_customer_id', true);

             //create stripe customer 
            if (empty($customer)) { 
                $customer = $this->create_stripe_customer( $payment_method, null, $current_user_obj->user_email, $current_user_obj);

                //if error occured
                if(isset($customer->error) && !empty($customer->error)){
                    throw new Exception($customer->error);
                    
                }  
                
                //saved stripe customer for charging saved cards later
                update_user_meta($logged_in_userid, "_stripe_customer_id", $customer->id);
                $customer = $customer->id;

            }

            //attach payment method to customer
            $payment_method_obj = \Stripe\PaymentMethod::retrieve( $payment_method);
            if (isset($response->error)) {
                throw new Exception($response->error);
            }

            $response = $payment_method_obj->attach( array(
                                'customer' => $customer));

            if (isset($response->error)) {
                throw new Exception($response->error);
            }

            $this->save_cards_for_later($response, true);

            return [
                'result'   => 'success',
                'redirect' => wc_get_endpoint_url( 'payment-methods' ),
            ];
        }
        catch (Exception $error){

            if (gettype($error) == 'string') {
                $error_message = $error->getMessage();
                $oops = array('message' => $error_message);
            }
            else{
                if (method_exists($error, 'getJsonBody')) {
                    $oops = $error->getJsonBody();
                    $error_message = $oops['error']['message'];
                    $error_type =  $oops['error']['type'];
                } else {
                    $oops = array('message' => $error->getMessage());
                    $error_message = $error->getMessage();
                    $error_type =  $oops['error']['type'];
                }        
            }
            wc_add_notice( $error_message, 'error' );
            EH_Stripe_Log::log_update('dead', $oops, get_bloginfo('blogname') . ' - Add Payment Method');

            return false;
        }
    }

    /**
     * Change order based on charge responce
     * @since 3.3.0
     * get the code from processs_payment() method
     */
    function process_order( $charge_response ,$wc_order ) {
        
        $order_id = (WC()->version < '2.7.0') ? $wc_order->id : $wc_order->get_id();
        
        $data = $this->make_charge_params($charge_response, $order_id);
        $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
        if ($charge_response->paid == true) {

            if($charge_response->captured == true){
                $wc_order->payment_complete($data['id']);
            }
            if (!$charge_response->captured) {
                $wc_order->update_status('on-hold');
            }
            $wc_order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($data['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['source_type'] . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $data['captured'] . (is_null($data['transaction_id']) ? '' : '. <br>'.__('Transaction ID : ','payment-gateway-stripe-and-woocommerce-integration') . $data['transaction_id']));
            WC()->cart->empty_cart();
            EH_Helper_Class::wt_stripe_order_db_operations($order_id, $wc_order, 'add', '_eh_stripe_payment_charge', $data, false); 

            EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($wc_order),
            );
        } else {
            wc_add_notice($data['status'], $notice_type = 'error');
            EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $wc_order->get_order_number());
        }
    }
    
    
    //update errors occuring during different processes
    private function process_error($order, $title, $message, $status = 'failed') {
        $order->add_order_note($message['message']);
        $order->update_status($status);

        $error_arr = array('message' => $message);
        EH_Stripe_Log::log_update('dead', $error_arr, get_bloginfo('blogname') . " - $title - Order #" . $order->get_order_number());
    }
    
    /**
     * Process refund process.
     * ( check need to update with payment intent ).
     */
    public function process_refund($order_id, $amount = NULL, $reason = '') {
    
        $client = $this->get_clients_details();
        if ($amount > 0) {
            $data = EH_Helper_Class::wt_stripe_order_db_operations($order_id, null, 'get', '_eh_stripe_payment_charge', null, true);
            
            $intent_id = EH_Helper_Class::wt_stripe_order_db_operations($order_id,  null, 'get', 'eh_stripe_intent_id', null, true);

            $status = $data['captured'];
            if ('Captured' === $status) {
                $charge_id = $data['id'];
                $currency = $data['currency'];
                $total_amount = $data['amount'];
                $wc_order = new WC_Order($order_id);
                $div = $amount * ($total_amount / ((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total()));
                $refund_params = array(
                    'amount' => self::get_stripe_amount($div, $currency),
                    'reason' => 'requested_by_customer',
                    'metadata' => array(
                        'order_id' => $wc_order->get_id(),
                        'Total Tax' => $wc_order->get_total_tax(),
                        'Total Shipping' => (WC()->version < '2.7.0') ? $wc_order->get_total_shipping() : $wc_order->get_shipping_total(),
                        'Customer IP' => $client['IP'],
                        'Agent' => $client['Agent'],
                        'Referer' => $client['Referer'],
                        'Reason for Refund' => $reason
                    )
                );
                
                try {

                    $charge_response = \Stripe\Charge::retrieve($charge_id);
                    $refund_response = $charge_response->refunds->create( $refund_params );
                    

                    if ( $refund_response ) {
                        
                        $refund_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                        $data = $this->make_refund_params($refund_response, $amount, ((WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency()), $order_id);
                        
                        EH_Helper_Class::wt_stripe_order_db_operations($order_id, $wc_order, 'add', '_eh_stripe_payment_refund', $data, false);
                        $wc_order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . (($data['status'] === 'succeeded') ? 'Success' : 'Failed') . ' [ ' . $refund_time . ' ] ' . (is_null($data['transaction_id']) ? '' : '<br>' . __('Transaction ID : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['transaction_id']));
                        EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Refund - Order #' . $wc_order->get_order_number());
                        return true;
                    } else {
                        EH_Stripe_Log::log_update('dead', $refund_response, get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                        $wc_order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __(' Status : Failed ', 'payment-gateway-stripe-and-woocommerce-integration'));
                        return new WP_Error('error', $refund_response->message);
                    }
                } catch (Exception $error) {
                    $oops = $error->getJsonBody();
                    EH_Stripe_Log::log_update('dead', $oops['error'], get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
                    $wc_order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . $oops['error']['message']);
                    return new WP_Error('error', $oops['error']['message']);
                }
            } else {
                return new WP_Error('error', __('Uncaptured Amount cannot be refunded', 'payment-gateway-stripe-and-woocommerce-integration'));
            }
        } else {
            return false;
        }
    }
    
    //gets file size of log files in units of bytes
    public function file_size($bytes) {
        $result = 0;
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ".", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

     /**
     * Change payment rediret URL when "requires_action"
     * @since 3.3.0
     */
    public function modify_successful_payment_result( $result, $order_id ) {
      
        if ( ! isset( $result['intent_secret'] ) ) {
          
            return $result;
        }

        // Put the final thank you page redirect into the verification URL.
        $verification_url = add_query_arg(
            array(
                'order'       => $order_id,
                '_wpnonce'    => wp_create_nonce( 'eh_stripe_confirm_payment_intent' ),
                'redirect_to' => rawurlencode( $result['redirect'] ),
            ),
            WC_AJAX::get_endpoint( 'eh_stripe_verify_payment_intent' )
        );

        // Combine into a hash.
        $redirect = sprintf( '#confirm-pi-%s:%s', $result['intent_secret'], rawurlencode( $verification_url ) );

        return array(
            'result'   => 'success',
            'redirect' => $redirect,
        );
    }
    
    /**
     * Save intent details with order
     * @since 3.3.0
     */
    public function save_payment_intent_to_order( $order, $intent ) {
        $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
        
        if ( version_compare(WC_VERSION, '2.7.0', '<') ) {
            update_post_meta( $order_id, 'eh_stripe_intent_id', $intent->id );
        } else {
            $order->update_meta_data( 'eh_stripe_intent_id', $intent->id );
        }

        if ( is_callable( array( $order, 'save' ) ) ) {
            $order->save();
        }
    }
    
    /**
     * Retreve the payment intent detials from order
     * @since 3.3.0
     */
    public function get_payment_intent_from_order( $order ) {
        $order_id = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();

        if ( version_compare(WC_VERSION, '2.7.0', '<') ) {
            $intent_id = get_post_meta( $order_id, 'eh_stripe_intent_id', true );
        } else {
            $intent_id = $order->get_meta( 'eh_stripe_intent_id' );
        }

        if ( ! $intent_id ) {
            return false;
        }

        return \Stripe\PaymentIntent::retrieve( $intent_id );
    }
    
    /**
     * Check intent status for declaring payment status
     * @since 3.3.0
     */
    public function verify_payment_intent_after_checkout( $order ) {
        
        $payment_method = version_compare(WC_VERSION, '3.0.0', '<') ? $order->payment_method : $order->get_payment_method();
        if ( $payment_method !== $this->id ) {
            // If this is not the payment method, an intent would not be available.
            return;
        }

        $intent = $this->get_payment_intent_from_order( $order );

        if ( ! $intent ) {
            // No intent, redirect to the order received page for further actions.
            return;
        }
        
        if(true != EH_Stripe_Payment::wt_stripe_is_HPOS_compatibile()){
            clean_post_cache( $order->get_id() );
        }
        $order = wc_get_order( $order->get_id() );

        if ( 'pending' !== $order->get_status() && 'failed' !== $order->get_status() ) { // Check payment already completed.
            return;
        }

        if ( $this->lock_order_payment( $order, $intent ) ) {
            return;
        }

        if ( 'succeeded' === $intent->status || 'requires_capture' === $intent->status ) {
        
            // Proceed with the payment completion.
            return $this->process_order(   end( $intent->charges->data ),$order );

        } else if ( 'requires_payment_method' === $intent->status ) {
            // `requires_payment_method` means that SCA got denied for the current payment method.
            $this->failed_sca_authenticate( $order, $intent );
        }

        $this->unlock_order_payment( $order );
    }
    /**
     * Puase processing order if same intent already being handled
     * @since 3.3.0
     */
    public function lock_order_payment( $order, $intent ) {
        $order_id       = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();
        $transient_name = 'eh_stripe_processing_intent_' . $order_id;
        $processing     = get_transient( $transient_name );
        
        if ( $processing === $intent->id ) {
            return true;
        }
        
        set_transient( $transient_name, $intent->id, 5 * MINUTE_IN_SECONDS );

        return false;
    }
    /**
     * Release the order after processing order.
     * @since 3.3.0
     */
    public function unlock_order_payment( $order ) {
        $order_id       = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();
        delete_transient( 'eh_stripe_processing_intent_' . $order_id );
    }
    /**
     *  change order status on fail SCA authentication
     * @since 3.3.0
     */
    public function failed_sca_authenticate( $order, $intent ) {
        if ( 'failed' === $order->get_status() ) { // Chack order already failed.
            return;
        }
        
        
        $status_message = ( $intent->last_payment_error )
            ? sprintf( __( 'Stripe SCA authentication failed. Reason: %s', 'payment-gateway-stripe-and-woocommerce-integration' ), $intent->last_payment_error->message )
            : __( 'Stripe SCA authentication failed.', 'payment-gateway-stripe-and-woocommerce-integration' );
        $order->update_status( 'failed', $status_message ); 
    }

    /**
     * Order Pay page
     * @since 3.3.0
     */
    public function verify_payment_intent_verification_in_order_pay(  ) {

        if ( ( ! is_wc_endpoint_url( 'order-pay' ) || ! isset( $_GET['eh-stripe-confirmation'] ) ) ) {

            return ;
        }
       
        add_filter( 'woocommerce_checkout_show_terms', '__return_false' );
        add_filter( 'woocommerce_pay_order_button_html', '__return_false' );
        add_filter( 'woocommerce_available_payment_gateways', array( $this, '__return_empty_array' ) );
        add_filter( 'woocommerce_no_available_payment_methods_message', array( $this, 'change_no_available_methods_message' ) );
        add_action( 'woocommerce_pay_order_after_submit', array( $this, 'add_payment_intent_hidden_items' ) );
        
        
        
        return array();
    }


    function __return_empty_array() {
        return array();
    }
    public function change_no_available_methods_message() {
        return wpautop( __( "Need to autherise the payment.", 'payment-gateway-stripe-and-woocommerce-integration' ) );
    }
    /**
     * Add payment intent fields for order pay page 
     * @since 3.3.0
     */
    public function add_payment_intent_hidden_items() {

        $order     = wc_get_order( absint( get_query_var( 'order-pay' ) ) );
        
        $intent    = $this->get_payment_intent_from_order( $order );
        $order_id  = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();
        
        EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_intent', $intent->id, false);
        $query_args  = array(
            'order'            => $order_id,
            '_wpnonce'         => wp_create_nonce( 'eh_stripe_confirm_payment_intent' ),
            'redirect_to'      => rawurlencode( $this->get_return_url( $order ) ),
            'is_pay_for_order' => true,
        );
       
        $verification_url = add_query_arg(
            $query_args,
            WC_AJAX::get_endpoint( 'eh_stripe_verify_payment_intent' )
        );
         
        echo '<input type="hidden" id="eh-stripe-intent-id" value="' . esc_attr( $intent->client_secret ) . '" />';
        echo '<input type="hidden" id="eh-stripe-intent-return" value="' . esc_attr( $verification_url ) . '" />';
    }

    // Save cards for later
    public function save_cards_for_later($payment_details, $my_account_page = false){ 

        if ( $payment_details ) {

            if (!$my_account_page) {
                $response = $payment_details->payment_method_details;
                $token = $payment_details->payment_method;
            }
            else{
                $response = $payment_details;
                $token = $payment_details->id;
            } 
            // save payment method as token and set the logged in user            
            if ( class_exists('WC_Payment_Token_CC') ) {
                

                $wc_token = new WC_Payment_Token_CC( );
                $wc_token->set_token( $token );
                $wc_token->set_gateway_id( $this->id );
                $wc_token->set_card_type( strtolower( $response->card->brand ) );
                $wc_token->set_last4( $response->card->last4 );
                $wc_token->set_expiry_month( $response->card->exp_month );
                $wc_token->set_expiry_year( $response->card->exp_year );

                $wc_token->set_user_id( get_current_user_id() );
                $wc_token->save();

            }
        }

        return true;
    }

    public function payment_via_saved_card()
    { 
        $payment_gateway = isset( $_POST['payment_method'] ) ? wc_clean( wp_unslash( $_POST['payment_method'] ) ) : $this->id;
        //if saved card token present in request, payment made via saved card
        if ( isset( $_POST[ 'wc-' . $payment_gateway . '-payment-token' ] ) && 'new' != $_POST[ 'wc-' . $payment_gateway . '-payment-token' ] ){
            return true;
        }
        else{
            return false;
        }

    }

    //save card for later use
    public function should_save_this_card()
    {
        
        if (isset($_REQUEST['wc-' . $this->id . '-new-payment-method']) && $_REQUEST['wc-' . $this->id . '-new-payment-method'] == true) {  
             return true;
        }
        else{
            return false;
        }

    } 

    public function is_load_script($page)
    {   
        $eh_stripe_option = get_option("woocommerce_eh_stripe_pay_settings");


        if((isset($eh_stripe_option['eh_payment_request']) && ($eh_stripe_option['eh_payment_request'] === 'yes') ) && isset($eh_stripe_option['eh_payment_request_button_enable_options'])){
            $eh_stripe_payment_request_button_options = $eh_stripe_option['eh_payment_request_button_enable_options'] ? $eh_stripe_option['eh_payment_request_button_enable_options'] : array();
        }

        //applepay enabled options
        if(( isset($eh_stripe_option['eh_stripe_apple_pay']) && ($eh_stripe_option['eh_stripe_apple_pay'] === 'yes') ) && isset($eh_stripe_option['eh_stripe_apple_pay_options'])){
            $eh_stripe_apple_pay_options = $eh_stripe_option['eh_stripe_apple_pay_options'] ? $eh_stripe_option['eh_stripe_apple_pay_options'] : array();
        }

        if( ( isset($eh_stripe_option['eh_payment_request']) && ($eh_stripe_option['eh_payment_request'] === 'yes') ) || ( isset($eh_stripe_option['eh_stripe_apple_pay']) && ($eh_stripe_option['eh_stripe_apple_pay'] === 'yes') ) ){

            if ($page == 'product') {
                if (is_product() && ((isset($eh_stripe_payment_request_button_options) && in_array('product', $eh_stripe_payment_request_button_options)) || (isset($eh_stripe_apple_pay_options) && in_array('product', $eh_stripe_apple_pay_options)) )) {
                    return true;
                }
                else{
                    return false;
                }          
            } 
            elseif ($page == 'cart') {
                if (is_cart() && ((isset($eh_stripe_payment_request_button_options) && in_array('cart', $eh_stripe_payment_request_button_options)) || (isset($eh_stripe_apple_pay_options) && in_array('cart', $eh_stripe_apple_pay_options)))) {
                    return true;
                }
                else{
                    return false;
                }  
            }
            else{
                if (is_checkout() && ((isset($eh_stripe_payment_request_button_options) && in_array('checkout', $eh_stripe_payment_request_button_options)) || (isset($eh_stripe_apple_pay_options) && in_array('checkout', $eh_stripe_apple_pay_options)))) {
                    return true;
                }
                else{
                    return false;
                }

            }


        }
        else{
            return false;
        }
    }       

    static function wt_get_api_version(){
        return apply_filters('wt_stripe_api_version', '2022-08-01');
    }

    static function wt_stripe_is_HPOS_compatibile()
    {
         if(class_exists('Automattic\WooCommerce\Utilities\OrderUtil') && method_exists('Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled')){
            return OrderUtil::custom_orders_table_usage_is_enabled();
        }else{
            return false;
        }
    }     

}
