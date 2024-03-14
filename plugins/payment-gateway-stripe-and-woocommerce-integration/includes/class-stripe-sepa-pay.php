<?php

if (!defined('ABSPATH')) {
    exit;
}  

/**
 * EH_Stripe_Sepa_Pay class.
 *
 * @extends EH_Stripe_Payment
 */
#[\AllowDynamicProperties]
class EH_Sepa_Stripe_Gateway extends WC_Payment_Gateway {

    /**
     * Constructor
     */
    public function __construct() {
        
        $this->id                 = 'eh_sepa_stripe';
        $this->method_title       = __( 'SEPA', 'payment-gateway-stripe-and-woocommerce-integration' );

        $url = add_query_arg( 'wc-api', 'wt_stripe', trailingslashit( get_home_url() ) );
        $this->method_description = sprintf( __( 'SEPA (Single Euro Payments Area) Direct Debit payment authenticates customers using the IBAN number. ' . '<a  class="thickbox" href="'.EH_STRIPE_MAIN_URL_PATH . 'assets/img/sepa-preview.png?TB_iframe=true&width=100&height=100"> [Preview] </a>', 'payment-gateway-stripe-and-woocommerce-integration' ));
        $this->supports = array(
            'products',
            'refunds',

        );

        // Load the form fields.
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        
        $stripe_settings               = get_option( 'woocommerce_eh_stripe_pay_settings' );
        
        $this->title                   = __($this->get_option( 'eh_stripe_sepa_title' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->description             = __($this->get_option( 'eh_stripe_sepa_description' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->enabled                 = $this->get_option( 'enabled' );
        $this->eh_order_button         = $this->get_option( 'eh_stripe_sepa_order_button');
        $this->order_button_text       = __($this->eh_order_button, 'payment-gateway-stripe-and-woocommerce-integration');

        if (isset($stripe_settings['eh_stripe_mode']) && 'test' === $stripe_settings['eh_stripe_mode']) {
            $this->description = $this->description . ' ' . __( '<p><strong>TEST MODE ENABLED</strong>. In test mode, you can use IBAN number AT611904300234573201.</p>', 'payment-gateway-stripe-and-woocommerce-integration' );
        }

        $this->mandate_description = __(apply_filters('wt_sepa_mandate', 'By providing your IBAN and confirming this payment, you authorise and Stripe, our payment service provider, to send instructions to your bank to debit your account and your bank to debit your account in accordance with those instructions. You are entitled to a refund from your bank under the terms and conditions of your agreement with your bank. A refund must be claimed within 8 weeks starting from the date on which your account was debited.'), 'payment-gateway-stripe-and-woocommerce-integration' );

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        // Set stripe API key.
       
        \Stripe\Stripe::setApiKey(EH_Stripe_Payment::get_stripe_api_key());
        \Stripe\Stripe::setAppInfo( 'WordPress payment-gateway-stripe-and-woocommerce-integration', EH_STRIPE_VERSION, 'https://wordpress.org/plugins/payment-gateway-stripe-and-woocommerce-integration/', 'pp_partner_KHip9dhhenLx0S' );

 

        // Hooks
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

        add_action( 'woocommerce_api_wt_stripe', array( $this, 'eh_callback_handler' ) );
    }


    /**
     * Initialize form fields in sepa payment settings page.
     */
    public function init_form_fields() {

        $stripe_settings   = get_option( 'woocommerce_eh_stripe_pay_settings' );
        
        $this->form_fields = array(

            'eh_sepa_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%sSupported currency: %s EUR %sStripe accounts in the following countries can accept the payment: %sAustralia, Austria, Belgium, Bulgaria, Canada, Cyprus, Czech Republic, Denmark, Estonia, Finland, France, Germany, Greece, Hong Kong, Hungary, Ireland, Italy, Japan, Latvia, Lithuania, Luxembourg, Malta, Mexico, Netherlands, New Zealand, Norway, Poland, Portugal, Romania, Singapore, Slovakia, Slovenia, Spain, Sweden, Switzerland, United Kingdom, United States%s %s Read documentation %s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><ul><li>', '<b>','</b></li><li>', '<b>', '</b></li></ul></div>', '<p><a target="_blank" href="https://www.webtoffee.com/woocommerce-stripe-payment-gateway-plugin-user-guide/#sepa_pay">', '</a></p>'),
            ),
            'eh_stripe_sepa_form_title'   => array(
                'type'        => 'title',
                'class'       => 'eh-css-class',
            ),
            'enabled'                       => array(
                'title'       => __('SEPA Pay','payment-gateway-stripe-and-woocommerce-integration'),
                'label'       => __('Enable','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'checkbox',
                'default'     => isset($stripe_settings['eh_stripe_sepa']) ? $stripe_settings['eh_stripe_sepa'] : 'no',
                'desc_tip'    => __('Enables customers in the Single Euro Payments Area (SEPA) to pay by providing their bank account details.','payment-gateway-stripe-and-woocommerce-integration'),
            ),
            'eh_stripe_sepa_title'         => array(
                'title'       => __('Title','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'text',
                'description' =>  __('Input title for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'default'     =>isset($stripe_settings['eh_stripe_sepa_title']) ? $stripe_settings['eh_stripe_sepa_title'] : __('SEPA Pay', 'payment-gateway-stripe-and-woocommerce-integration'),
                'desc_tip'    => true,
            ),
            'eh_stripe_sepa_description'     => array(
                'title'       => __('Description','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'textarea',
                'css'         => 'width:25em',
                'description' => __('Input texts for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'default'     =>isset($stripe_settings['eh_stripe_sepa_description']) ? $stripe_settings['eh_stripe_sepa_description'] : __('Secure debit payment via SEPA.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'desc_tip'    => true
            ),

            'eh_stripe_sepa_order_button'    => array(
                'title'       => __('Order button text', 'payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'text',
                'description' => __('Input a text that will appear on the order button to place order at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'default'     => isset($stripe_settings['eh_stripe_sepa_order_button']) ? $stripe_settings['eh_stripe_sepa_order_button'] :__('Pay via SEPA', 'payment-gateway-stripe-and-woocommerce-integration'),
                'desc_tip'    => true
            ),
            'eh_sepa_webhook_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%1$sTo accept payments via SEPA payment method, you must configure the webhook endpoint and subscribe to relevant events. %2$sClick here%3$s to know more%4$s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><p>', '<a target="_blank" href="https://www.webtoffee.com/setting-up-webhooks-and-supported-webhooks/">', '</a>', '</p></div>'),
            ),
        );   
    }
    
    public function get_icon() {
        $style = version_compare(WC()->version, '2.6', '>=') ? 'style="margin-left: 0.3em"' : '';
        $icon = '';
        
        $icon .= '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/sepa.png') . '" alt="SEPA" width="52" title="SEPA" ' . $style . ' />';
        return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
    }

    /**
     * Outputs scripts used for stripe payment.
     */
    public function payment_scripts() {

        if(!$this->is_available()){
            return false;
        }

        if ( (is_checkout()  && !is_order_received_page())) {
            $stripe_settings   = get_option( 'woocommerce_eh_stripe_pay_settings' );
            wp_register_script('stripe_v3_js', 'https://js.stripe.com/v3/');

           wp_enqueue_script('eh_sepa_pay', plugins_url('assets/js/eh-sepa.js', EH_STRIPE_MAIN_FILE), array('stripe_v3_js','jquery'),EH_STRIPE_VERSION, true);
            if (isset($stripe_settings['eh_stripe_mode']) && 'test' === $stripe_settings['eh_stripe_mode']) {
                if (!isset($stripe_settings['eh_stripe_test_publishable_key']) || !isset($stripe_settings['eh_stripe_test_secret_key']) || ! $stripe_settings['eh_stripe_test_publishable_key'] || ! $stripe_settings['eh_stripe_test_secret_key']) {
                    return false;
                }
                else{
                    $public_key = $stripe_settings['eh_stripe_test_publishable_key'];
                }
            } else {
                if (!isset($stripe_settings['eh_stripe_live_secret_key']) || !isset($stripe_settings['eh_stripe_live_publishable_key']) || !$stripe_settings['eh_stripe_live_secret_key'] || !$stripe_settings['eh_stripe_live_publishable_key']) {
                    return false;
                }
                else{
                    $public_key = $stripe_settings['eh_stripe_live_publishable_key'];
                }
            }

            $show_zip_code = apply_filters('eh_stripe_ccshow_zipcode',true);
            $stripe_params = array(
                'key' => $public_key,
                'show_zip_code' => $show_zip_code,
                'i18n_terms' => __('Please accept the terms and conditions first', 'payment-gateway-stripe-and-woocommerce-integration'),
                'i18n_required_fields' => __('Please fill in required checkout fields first', 'payment-gateway-stripe-and-woocommerce-integration'),
            );
            $stripe_params['sepa_elements_option']                   = apply_filters(
                'eh_stripe_sepa_elements_option',
                array(
                    'supportedCountries' => array( 'SEPA' ),
                    
                    'placeholderCountry' => 'DE',
                    'style'              => array( 'base' => array( 'fontSize' => '15px' ,
                                                                "iconColor" => "#666EE8",
                                                                "color" => "#31325F",
                                                                "fontSize" => "15px",
                                                                "::placeholder" => array(
                                                                                    "color" => "#CFD7E0",
                                                                                ),
                                                            )
                                             ),
                )
            );

            $stripe_params['is_checkout']                             = ( is_checkout() && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no';
            $stripe_params['inline_postalcode']                       = apply_filters('hide_inline_postal_code', true);

            // If we're on the pay page we need to pass stripe.js the address of the order.
            if ( isset( $_GET['pay_for_order'] ) && 'true' === $_GET['pay_for_order'] ) {

                $order     = wc_get_order( absint( get_query_var( 'order-pay' ) ) );

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
            $stripe_params['version'] = EH_Stripe_Payment::wt_get_api_version();
            wp_localize_script('eh_sepa_pay', 'eh_sepa_val', apply_filters('eh_sepa_val', $stripe_params));
        }
    }

    /**
     *override woocommerce payment form.
     */
    public function payment_fields() {

        $user = wp_get_current_user();
        if ($user->ID) {
            $user_email = get_user_meta($user->ID, 'billing_email', true);
            $user_email = $user_email ? $user_email : $user->user_email;
        } else {
            $user_email = '';
        }
        echo '<div class="status-box">';

        if ($this->description) {
            echo apply_filters('eh_sepa_desc', wpautop(wp_kses_post("<span>" . $this->description . "</span>")));
        }
        echo "</div>";
        $pay_button_text = __('Pay', 'payment-gateway-stripe-and-woocommerce-integration');
        if (is_checkout_pay_page()) {
            $order_id = get_query_var('order-pay');
            $order = wc_get_order($order_id);
            $email = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
            echo '<div
                id="eh-sepa-pay-data"
                data-panel-label="' . esc_attr($pay_button_text) . '"
                data-email="' . esc_attr(($email !== '') ? $email : get_bloginfo('name', 'display')) . '"
                data-amount="' . esc_attr(EH_Stripe_Payment::get_stripe_amount(((WC()->version < '2.7.0') ? $order->order_total : $order->get_total()))) . '"
                data-name="' . esc_attr(sprintf(get_bloginfo('name', 'display'))) . '"
                data-currency="' . esc_attr(((WC()->version < '2.7.0') ? $order->order_currency : $order->get_currency())) . '">';

           echo $this->elements_form();
            echo '</div>';

        } else {
            echo '<div
                id="eh-sepa-pay-data"
                data-panel-label="' . esc_attr($pay_button_text) . '"
                data-email="' . esc_attr($user_email) . '"
                data-amount="' . esc_attr(EH_Stripe_Payment::get_stripe_amount(WC()->cart->total)) . '"
                data-name="' . esc_attr(sprintf(get_bloginfo('name', 'display'))) . '"
                data-currency="' . esc_attr(strtolower(get_woocommerce_currency())) . '">';

           echo $this->elements_form();
           
           echo '</div>';
        }
    }


        /**
     *Renders stripe elements on payment form.
     */
    public function elements_form() {
        ?>
        <fieldset id="eh-<?php echo esc_attr( $this->id ); ?>-cc-form" class="eh-credit-card-form eh-payment-form" style="background:transparent;">

                <div class="form-row  form-row-wide">
                    <input type="hidden" name="eh_sepa_source"  id="eh_sepa_source" value="test">
                    <input type="hidden" name="eh_sepa_source_status"  id="eh_sepa_source_status" value="test">
                    <!--
                    Using a label with a for attribute that matches the ID of the
                    Element container enables the Element to automatically gain focus
                    when the customer clicks on the label.
                    -->
                    <label for="eh-stripe-iban-element"><?php esc_html_e( 'IBAN', 'payment-gateway-stripe-and-woocommerce-integration' ); ?> <span class="required">*</span></label>
                    <div id="eh-stripe-iban-element"  class="eh-stripe-elements-field">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                </div> 
              <!-- Display mandate acceptance text. -->
              <div id="mandate-acceptance"><?php esc_html_e($this->mandate_description, "payment_gateway_stripe_and_woocommerce_integration")  ?>
              </div>
                <div class="clear"></div>

            <!-- Used to display form errors -->
            <div class="sepa-source-errors" role="alert" style="color:#ff0000"></div>
            <div class="clear"></div>
        </fieldset>
        <?php
    }



    /**
     *Makes gateway available 
     */
    public function is_available() {

        $stripe_settings   = get_option( 'woocommerce_eh_stripe_pay_settings' );

        if (!empty($stripe_settings) && 'yes' === $this->enabled) {

            if (isset($stripe_settings['eh_stripe_mode']) && 'test' === $stripe_settings['eh_stripe_mode']) {
                if (!isset($stripe_settings['eh_stripe_test_publishable_key']) || !isset($stripe_settings['eh_stripe_test_secret_key']) || ! $stripe_settings['eh_stripe_test_publishable_key'] || ! $stripe_settings['eh_stripe_test_secret_key']) {
                    return false;
                }
            } else {
                if (!isset($stripe_settings['eh_stripe_live_secret_key']) || !isset($stripe_settings['eh_stripe_live_publishable_key']) || !$stripe_settings['eh_stripe_live_secret_key'] || !$stripe_settings['eh_stripe_live_publishable_key']) {
                    return false;
                }
            }

            return true;
        }
        return false; 
    }
    
    /**
     *Process stripe payment.
     */
    public function process_payment($order_id) { 
        $order = wc_get_order( $order_id );
        $currency =  $order->get_currency();
        
        try{ 

            $payment_method = isset($_POST['eh_sepa_token']) ? sanitize_text_field($_POST['eh_sepa_token']) : '';
            if (empty($payment_method)) {
                throw new Exception(__('Unable to process this payment, please try again.', 'payment_gateway_stripe_and_woocommerce_integration' ));
                
            }
            $currency =  $order->get_currency();
            $amount = EH_Stripe_Payment::get_stripe_amount(((WC()->version < '2.7.0') ? $order->order_total : $order->get_total())) ;

             $customer = $this->create_stripe_customer($order_id, ((WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email()));
                
            if (!empty($customer) && isset($customer->id)) {
                $user_id = $order->get_user_id();
                update_user_meta($user_id, "_sepa_customer_id", sanitize_text_field($customer->id));

                $intent = $this->get_payment_intent_from_order( $order );
               
                $client = $this->get_clients_details();

                $payment_intent_args  = $this->get_charge_details($order, $client, $currency, $amount, $payment_method);

                if(! empty($intent)){

                    if ( $intent->status === 'succeeded' ) {
                        wc_add_notice(__('An error has occurred internally, due to which you are not redirected to the order received page. Please contact support for more assistance.', 'payment_gateway_stripe_and_woocommerce_integration'), 'error');
                        wp_redirect(wc_get_checkout_url());
                    }else{
                        $intent = \Stripe\PaymentIntent::create( $payment_intent_args , array(
                            'idempotency_key' => $order->get_order_key() . '-' . $payment_method
                        ));
                    }
                }else{
                    $intent = \Stripe\PaymentIntent::create( $payment_intent_args , array(
                        'idempotency_key' => $order->get_order_key() . '-' . $payment_method
                    ));
                } 
                $this->save_payment_intent_to_order( $order, $intent );

                EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_intent', $intent->id, false); 
                if (isset($intent->status) && ( $intent->status === 'requires_action' ) &&
                    $intent->next_action->type === 'redirect_to_url') {

                    return array(
                        'result'        => 'success',
                        'redirect'      => $intent->next_action->redirect_to_url->url,
                    );
                } else {
                     $this->eh_process_payment_response( $intent,$order );
                    return array(
                        'result'        => 'success',
                        'redirect'      => $this->get_return_url( $order ),
                    );
                }
            }
            else{
                throw new Exception( __( 'Unable to process this payment, please try again.', 'payment_gateway_stripe_and_woocommerce_integration' ));
            }

        }
        catch(Exception $e){
            $order->update_status( 'failed', sprintf( __( 'Sepa payment failed: %s', 'payment_gateway_stripe_and_woocommerce_integration' ),$e->getMessage() ) );
            
           wc_add_notice( $e->getMessage(), 'error' );
            return array (
                'result' => 'failure'
            );
        }
        

    }

    /**
     * Save intent details with order
     * @since 3.2.3
     */
    public function save_payment_intent_to_order( $order, $intent ) {
        $order_id = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();
        
        if ( version_compare(WC_VERSION, '2.7.0', '<') ) {
            update_post_meta( $order_id, '_eh_stripe_payment_intent', $intent->id );
        } else {
            $order->update_meta_data( '_eh_stripe_payment_intent', $intent->id );
        }

        if ( is_callable( array( $order, 'save' ) ) ) {
            $order->save();
        }
    }
    
    /**
     *Creates stripe customer
     */
    public function create_stripe_customer( $order_id, $user_email = false) {
        
        $response = \Stripe\Customer::create(array(
                    "description" => "Customer for Order #" . $order_id,
                    "email" => $user_email
                ));

        if (empty($response->id)) {
            return false;
        }

        return $response;
    }


     /**
     *Gets details for stripe charge creation.
     */
    public function get_charge_details( $wc_order, $client, $currency, $amount, $payment_method) {
        $product_name = array();
        $order_id = $wc_order->get_id();
        foreach ($wc_order->get_items() as $item) {
            array_push($product_name, $item['name']);
        }

        $charge = array(
            'payment_method_types' => array('sepa_debit'),
            'amount' => $amount,
            'payment_method' => $payment_method,
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
        
        $eh_stripe = get_option("woocommerce_eh_stripe_pay_settings");

        $product_list = implode(' | ', $product_name);

        $charge['metadata']['Products'] = substr($product_list, 0, 499);
                
        $show_items_details = apply_filters('eh_stripe_show_items_in_payment_description', false);
                
        if($show_items_details){
            
            $charge['description']=$charge['metadata']['Products'] .' '.wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' Order #' . $wc_order->get_order_number();
        }
        $charge['confirm'] =  true ;
        $charge['return_url'] =  add_query_arg('order_id', $order_id, WC()->api_request_url('EH_Sepa')) ; 
        $charge['capture_method'] =  'automatic';

       // if (!is_checkout_pay_page()) {
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
       // }
        
        $charge['mandate_data']['customer_acceptance']['type'] =  "online";

        $client_details = $this->get_clients_details();
        $charge['mandate_data']['customer_acceptance']['online']['ip_address'] =  $client_details['IP'];
        $charge['mandate_data']['customer_acceptance']['online']['user_agent'] =  $client_details['Agent'];

       return apply_filters('eh_sepa_payment_intent_args', $charge);
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
     * gets required parameters for creating stripe charge.
     *
     */
    public function eh_make_charge_params( $order, $source_id,  $customer = null ) {
        
        $stripe_settings               = get_option( 'woocommerce_eh_stripe_pay_settings' );

        $post_data                       =  array();
        $currency                        =  $order->get_currency();
        $post_data['currency']           =  strtolower( $currency);
        $post_data['amount']             =  EH_Stripe_Payment::get_stripe_amount( $order->get_total(), $currency );
        $post_data['description']        =  sprintf( __( '%1$s - Order %2$s', 'payment-gateway-stripe-and-woocommerce-integration' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $order->get_order_number() );
        $billing_email                   =  (WC()->version < '2.7.0') ? $order->billing_email      : $order->get_billing_email();
        $billing_first_name              =  (WC()->version < '2.7.0') ? $order->billing_first_name : $order->get_billing_first_name();
        $billing_last_name               =  (WC()->version < '2.7.0') ? $order->billing_last_name  : $order->get_billing_last_name();
        
        $post_data['shipping']['name']   =  $billing_first_name . ' ' . $billing_last_name;
        $post_data['shipping']['phone']  =  (WC()->version < '2.7.0') ? $order->billing_phone : $order->get_billing_phone();

        $post_data['shipping']['address']['line1']       = (WC()->version < '2.7.0') ? $order->shipping_address_1 : $order->get_shipping_address_1();
        $post_data['shipping']['address']['line2']       = (WC()->version < '2.7.0') ? $order->shipping_address_2 : $order->get_shipping_address_2();
        $post_data['shipping']['address']['state']       = (WC()->version < '2.7.0') ? $order->shipping_state     : $order->get_shipping_state();
        $post_data['shipping']['address']['city']        = (WC()->version < '2.7.0') ? $order->shipping_city      : $order->get_shipping_city();
        $post_data['shipping']['address']['postal_code'] = (WC()->version < '2.7.0') ? $order->shipping_postcode  : $order->get_shipping_postcode();
        $post_data['shipping']['address']['country']     = (WC()->version < '2.7.0') ? $order->shipping_country   : $order->get_shipping_country();
        
        $post_data['metadata']  = array(
            __( 'customer_name', 'payment-gateway-stripe-and-woocommerce-integration' ) => sanitize_text_field( $billing_first_name ) . ' ' . sanitize_text_field( $billing_last_name ),
            __( 'customer_email', 'payment-gateway-stripe-and-woocommerce-integration' ) => sanitize_email( $billing_email ),
            'order_id' => $order->get_id(),
        );
        
        if ( $source_id ) {
            $post_data['source'] = $source_id;
        }
       if ( $customer ) {
            $post_data['customer'] = $customer;
        }

        if (isset($this->capture_now) && $this->capture_now == 'no') {
            $post_data['capture'] = false;
        }
        return apply_filters( 'eh_sepa_generate_charge_request', $post_data, $order, $source_id );
    }

    /**
     *Process alipay refund process.
     */
    public function process_refund($order_id, $amount = NULL, $reason = '') {
    
        $client = $this->get_clients_details();
        if ($amount > 0) {
            
            $data = EH_Helper_Class::wt_stripe_order_db_operations($order_id, null, 'get', '_eh_stripe_payment_charge', null, true); 
            $status = $data['captured'];

            if ('Captured' === $status) {
                $charge_id = $data['id'];
                $currency = $data['currency'];
                $total_amount = $data['amount'];
                        
                $order = new WC_Order($order_id);
                $div = $amount * ($total_amount / ((WC()->version < '2.7.0') ? $order->order_total : $order->get_total()));
                $refund_params = array(
                    'amount' => EH_Stripe_Payment::get_stripe_amount($div, $currency),
                    'reason' => 'requested_by_customer',
                    'charge' => $charge_id,
                    'metadata' => array(
                        'order_id' => $order->get_id(),
                        'Total Tax' => $order->get_total_tax(),
                        'Total Shipping' => (WC()->version < '2.7.0') ? $order->get_total_shipping() : $order->get_shipping_total(),
                        'Customer IP' => $client['IP'],
                        'Agent' => $client['Agent'],
                        'Referer' => $client['Referer'],
                        'Reason for Refund' => $reason
                    )
                );
                        
                try {
                    //$charge_response = \Stripe\Charge::retrieve($charge_id);
                    $refund_response = \Stripe\Refund::create($refund_params);
                    if ($refund_response) {
                                        
                        $refund_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                        $obj = new EH_Stripe_Payment();
                        $data = $obj->make_refund_params($refund_response, $amount, ((WC()->version < '2.7.0') ? $order->order_currency : $order->get_currency()), $order_id);
                       EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_refund', $data, false); 
                        $order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : refunded ', 'payment-gateway-stripe-and-woocommerce-integration') . ' [ ' . $refund_time . ' ] ' . (is_null($data['transaction_id']) ? '' : '<br>' . __('Transaction ID : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['transaction_id']));
                        EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Refund - Order #' . $order->get_order_number());
                        return true;
                    } else {
                        EH_Stripe_Log::log_update('dead', $data, get_bloginfo('blogname') . ' - Refund Error - Order #' . $order->get_order_number());
                        $order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __(' Status : Failed ', 'payment-gateway-stripe-and-woocommerce-integration'));
                        return new WP_Error('error', $data->message);
                    }
                } catch (Exception $error) {
                    $oops = $error->getJsonBody();
                    EH_Stripe_Log::log_update('dead', $oops['error'], get_bloginfo('blogname') . ' - Refund Error - Order #' . $order->get_order_number());
                    $order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . $oops['error']['message']);
                    return new WP_Error('error', $oops['error']['message']);
                }
            } else {
                return new WP_Error('error', __('Uncaptured Amount cannot be refunded', 'payment-gateway-stripe-and-woocommerce-integration'));
            }
        } else {
            return false;
        }
    }


            /**
     * Retreve the payment intent detials from order
     * @since 3.3.0
     */
    public function get_payment_intent_from_order( $order ) {
        $order_id = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();

        if ( version_compare(WC_VERSION, '2.7.0', '<') ) {
            $intent_id = get_post_meta( $order_id, '_eh_stripe_payment_intent', true );
        } else {
            $intent_id = $order->get_meta( '_eh_stripe_payment_intent' );
        }

        if ( ! $intent_id ) {
            return false;
        }

        return \Stripe\PaymentIntent::retrieve( $intent_id );
    }

    public function eh_sepa_callback_handler() { 
        if (isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id'])) {
            $order_id = sanitize_text_field($_REQUEST['order_id']);
            $order = wc_get_order( $order_id );

        }
        if (isset($_REQUEST['payment_intent']) && !empty($_REQUEST['payment_intent'])) {
            $intent_id = sanitize_text_field($_REQUEST['payment_intent']);
            $intent_result = \Stripe\PaymentIntent::retrieve( $intent_id );
            if (!empty($intent_result)) {
                $this->eh_process_payment_response($intent_result, $order);
                wp_safe_redirect($this->get_return_url( $order ));
            }
            else{
                if ($order) {
                $order->update_status( 'failed', __( 'Stripe payment failed', 'payment_gateway_stripe_and_woocommerce_integration' ) );
                }
                
                wc_add_notice( __( 'Unable to process this payment.', 'payment_gateway_stripe_and_woocommerce_integration' ), 'error' );
                wp_safe_redirect( wc_get_checkout_url() );
            }
        }
        else{
            if ($order) {
                $order->update_status( 'failed', __( 'Stripe payment failed', 'payment_gateway_stripe_and_woocommerce_integration' ) );
            }
            
            wc_add_notice( __( 'Unable to process this payment.', 'payment_gateway_stripe_and_woocommerce_integration' ), 'error' );
            wp_safe_redirect( wc_get_checkout_url() );
         }


    }

        /**
     * Store extra meta data for an order and adds order notes for orders.
     */
    public function eh_process_payment_response( $response, $order = null ) {
     
        if (!$order) {
            $order_id = $response->metadata->order_id;
            $order = wc_get_order( $order_id );
        }
        $order_id = $order->get_id();
        
        // Stores charge data.
        $obj1 = new EH_Stripe_Payment();
        $charge_response = end($response->charges->data);
        if (!empty($charge_response)) {
            $charge_param = $obj1->make_charge_params($charge_response , $order_id);
            
            EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_charge', $charge_param, false); 
            
            //$order_id  = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();
             $captured = ( isset( $charge_response->captured ) &&  $charge_response->captured == true) ? 'Captured' : 'Uncaptured';

            // Stores charge capture data.
            if ( version_compare(WC_VERSION, '2.7.0', '<') ) {
                update_post_meta( $order_id, '_eh_sepa_charge_captured', $captured );
            } else {
                $order->update_meta_data( '_eh_sepa_charge_captured', $captured );
            }
        }
        
        $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600); 
        $charge_status = (isset($charge_response->status) ? $charge_response->status : '');
        $payment_method_tye = (isset($charge_response->payment_method_details->type) ? $charge_response->payment_method_details->type : '');

        $order->set_transaction_id( $charge_response->id );
        if(isset($response->status) && $response->status === 'succeeded'){
            if (isset($charge_response->paid) && $charge_response->paid === true) {

                if(isset($charge_response->captured) && $charge_response->captured === true){
                    $order->payment_complete( $charge_response->id );
                }
                else{
                    $order->update_status('on-hold');
                }
                $order->add_order_note( __('Payment Status : ', 'payment_gateway_stripe_and_woocommerce_integration') . ucfirst($charge_status) .' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment_gateway_stripe_and_woocommerce_integration') . $payment_method_tye . '. ' . __('Charge Status :', 'payment_gateway_stripe_and_woocommerce_integration') . $captured . (is_null($charge_response->balance_transaction) ? '' :'. Transaction ID : ' . $charge_response->balance_transaction) );
                WC()->cart->empty_cart();
                EH_Stripe_Log::log_update('live', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order),
                );
            } else {
                $order->update_status( 'failed', __( 'Stripe payment failed.', 'payment_gateway_stripe_and_woocommerce_integration' ) );
                wc_add_notice($charge_status, 'error');
                EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());
            }
        }
        elseif($response->status == 'processing' || $response->status == 'pending'){
            $order->update_status( 'on-hold', __( 'Waiting for the payment to succeed or fail.', 'payment_gateway_stripe_and_woocommerce_integration' ) );

        }         
        elseif($response->status == 'requires_capture'){
            $order->update_status( 'on-hold', __( 'Payment is authorized and requires a capture.', 'payment_gateway_stripe_and_woocommerce_integration' ) );
            $order->add_order_note( __('Payment Status : ', 'payment_gateway_stripe_and_woocommerce_integration') . ucfirst($charge_status) .' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment_gateway_stripe_and_woocommerce_integration') . $payment_method_tye . '. ' . __('Charge Status :', 'payment_gateway_stripe_and_woocommerce_integration') . $captured . (is_null($charge_response->balance_transaction) ? '' :'. Transaction ID : ' . $charge_response->balance_transaction) );


        }        
        else{
            $order->update_status( 'failed', __( 'Stripe payment failed.', 'payment_gateway_stripe_and_woocommerce_integration' ) );
                wc_add_notice($charge_status, 'error');
                EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());

        }
        return $charge_response;
        
    }

    //webhook callback
    public function eh_callback_handler() {
        global $wpdb;
 
        $raw_post = file_get_contents( 'php://input' );
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $eh_stripe_option = get_option("woocommerce_eh_stripe_pay_settings");
        $endpoint_secret = isset($eh_stripe_option["eh_stripe_webhook_secret"]) ? $eh_stripe_option["eh_stripe_webhook_secret"] : '';

        try {

            if(!empty($endpoint_secret)){
                $event = \Stripe\Webhook::constructEvent(
                    $raw_post, $sig_header, $endpoint_secret, 1000
                ); 

                if(empty($event)){
                    throw new Exception("Error Processing Request", 1);
                    
                }             
            }

            if (!empty($raw_post)) { 
                $decoded  = json_decode( $raw_post, true );

                if (!empty($decoded)) {



                    $check_site_url_match = apply_filters('eh_enable_site_miss_match_multisite', false);
                    $check_site_url_match = (bool)$check_site_url_match;
                    if(true === $check_site_url_match){
                         
                        if (isset($decoded['data']['object']['metadata'])) {
                            $site_url_match = false;
                            $home_url = home_url();
                            
                                foreach ($decoded['data']['object']['metadata'] as $key => $value) {
                                    if (strpos($value, $home_url) !== false) {
                                        $site_url_match = true;
                                        break;
                                    }
                                }
                                
                                if (false === $site_url_match) {
                                    exit;
                                }
                        }
                    }

                    EH_Stripe_Log::log_update('live', $decoded, get_bloginfo('blogname') . ' - WebHook event');
                   switch (strtolower($decoded['type'])) {
                       case 'charge.succeeded':
                       case 'charge.failed':
                           if (isset($decoded['data']['object']['metadata']['order_id']) && !empty($decoded['data']['object']['metadata']['order_id'])) {
                                $order_id = $decoded['data']['object']['metadata']['order_id'];

                                $transaction_id = sanitize_text_field($decoded['data']['object']['id']);


                                if(!$order = wc_get_order( $order_id )){
                                    if(true === EH_Stripe_Payment::wt_stripe_is_HPOS_compatibile()){
                                        $meta = $wpdb->get_results( $wpdb->prepare("SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = '_transaction_id' AND meta_value= %s", $transaction_id ));
                                    }
                                    else{
                                        $meta = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_transaction_id' AND meta_value = %s",  $transaction_id ));                                        
                                    }                                

                                    if (!empty($meta) && isset($meta[0]->post_id)) {
                                        $order_id = $meta[0]->post_id;
                                        $order = wc_get_order( $order_id );  
                                   }
                                   else if(isset($decoded['data']['object']['payment_intent'])){
                                        $payment_intent_id = sanitize_text_field($decoded['data']['object']['payment_intent']);
                                        if(true === EH_Stripe_Payment::wt_stripe_is_HPOS_compatibile()){
                                            $meta = $wpdb->get_results($wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = '_eh_stripe_payment_intent' AND meta_value = %s", $payment_intent_id));                                            
                                        }
                                        else{
                                            $meta = $wpdb->get_results($wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_eh_stripe_payment_intent' AND meta_value= %s", $payment_intent_id) );                                            
                                        }                                    
                                        
                                        if (!empty($meta) && isset($meta[0]->post_id)) {
                                            $order_id = $meta[0]->post_id;
                                            $order = wc_get_order( $order_id );  
                                       }                               
                                   }

                                }
                                
                                if (!$order) {
                                    exit;
                                }

                                $obj1 = new EH_Stripe_Payment();
                                $charge_param = $obj1->make_charge_params($decoded['data']['object'], $order_id);
                                 EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'update', '_eh_stripe_payment_charge', $charge_param, false);
                                
                                if ( 'on-hold' === $order->status || 'pending' === $order->status) {
                                    if (isset($decoded['data']['object']['status']) && $decoded['data']['object']['status'] === 'succeeded') {
                                         $status = $decoded['data']['object']['status'];

                                            $order->set_transaction_id( sanitize_text_field($decoded['data']['object']['id'] ));

                                            
                                            $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600); 
                                            $source_type = $decoded['data']['object']['payment_method_details']['type'];
                                            $source_type = (isset($decoded['data']['object']['payment_method_details']['type']) ? $decoded['data']['object']['payment_method_details']['type'] : (isset($decoded['data']['object']['source']['type']) ? $decoded['data']['object']['source']['type'] : 'unknown') );
                                             $balance_transaction_id = ((is_array($decoded['data']['object']['balance_transaction']) && isset($decoded['data']['object']['balance_transaction']['id'])) ? $decoded['data']['object']['balance_transaction']['id'] : (isset($decoded['data']['object']['balance_transaction']) ? $decoded['data']['object']['balance_transaction'] : 'unknown'));



                                            if ($decoded['data']['object']['captured'] == true) {
                                                $captured = 'Captured';
                                                $order->payment_complete( $transaction_id );

                                            }
                                            else{
                                                $captured = 'Uncaptured';
                                                 $order->update_status('on-hold');
                                            }
                                            $order->add_order_note( __('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($status) .' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $source_type . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $captured . __('. Transaction ID : ', 'payment-gateway-stripe-and-woocommerce-integration') . $balance_transaction_id . __('. via webhook', 'payment-gateway-stripe-and-woocommerce-integration') );
                                    } 
                                    else {
                                        // Set order status to payment failed
                                        $order->update_status( 'failed', sprintf( __( 'Payment failed.', 'payment-gateway-stripe-and-woocommerce-integration' ) ) );
                                    }
                                }
                            }
                           break;
                       
                       case 'charge.dispute.created':
                            if (isset($decoded['data']['object']['charge'])) {
                               $charge_id = sanitize_text_field($decoded['data']['object']['charge']);
                                if (!empty($charge_id)) {
                                    if(true === EH_Stripe_Payment::wt_stripe_is_HPOS_compatibile()){
                                        $meta = $wpdb->get_results($wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = '_transaction_id' AND meta_value= %s", $charge_id ) );                                         
                                    }
                                    else{
                                        $meta = $wpdb->get_results($wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_transaction_id' AND meta_value= %s", $charge_id) );                                        
                                    }                                 
                                     if (!empty($meta)) { 
                                        $order_id = $meta[0]->post_id;
                                        $order = wc_get_order( $order_id );

                                        $order->add_order_note( __('A dispute was created for this order : ', 'payment-gateway-stripe-and-woocommerce-integration') . $decoded['data']['object']['charge']);

                                        // Set order status to payment failed
                                            $order->update_status( 'failed', sprintf( __( 'Payment failed.', 'payment-gateway-stripe-and-woocommerce-integration' ) ) );
                                    }
                                }
                            }
                           break;
                       
                       /*case 'charge.refund.updated':
                           if (isset($decoded['data']['object']['charge'])) {
                               $charge_id = sanitize_text_field($decoded['data']['object']['charge']);
                                if (!empty($charge_id)) {
                                    if (isset($decoded['data']['object']['object']) && $decoded['data']['object']['object'] == 'refund') {

                                         $meta = $wpdb->get_results( "SELECT post_id FROM " . $wpdb->postmeta ." WHERE meta_key = '_transaction_id' AND meta_value= '" . $charge_id  . "'" );
                                         if (!empty($meta)) { 
                                            $order_id = $meta[0]->post_id;
                                            $order = wc_get_order( $order_id );

                                        }

                                        $refund_params = get_post_meta($order_id, '_eh_stripe_payment_refund', true);
                                        if(isset($refund_params['transaction_id']) && !empty($refund_params['transaction_id']) && $refund_params['transaction_id'] != $decoded['data']['object']['balance_transaction']){


                                            $refund_amount = EH_Stripe_Payment::reset_stripe_amount($decoded['data']['object']['amount'], $order->get_currency());

                                            if ($decoded['data']['object']['status'] == 'failed') {
                                                $reason = ((isset($decoded['data']['object']['failure_reason']) && !empty($decoded['data']['object']['failure_reason'])) ? $decoded['data']['object']['failure_reason'] 
                                                : 'Refund failed - Unknown error occurred');
                                                $order->add_order_note( __('Refund of '  . get_woocommerce_currency_symbol() . $refund_amount . ' failed - ' . $reason, 'payment-gateway-stripe-and-woocommerce-integration'));

                                                // Set order status to payment failed
                                                    $order->update_status( 'processing', sprintf( __( 'Refund Failed.', 'payment-gateway-stripe-and-woocommerce-integration' ) ) );
                                            }
                                            else{
                                                
                                                $order->add_order_note((__('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') .get_woocommerce_currency_symbol() . $refund_amount . '.<br>' . __('Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . 'Success' . (is_null($decoded['data']['object']['balance_transaction']) ? '' : '<br>' . __('Transaction ID : ', 'payment-gateway-stripe-and-woocommerce-integration') . $decoded['data']['object']['balance_transaction'] )));

                                                // Set order status to payment failed
                                                    $order->update_status( 'refunded', sprintf( __( 'Refunded.', 'payment-gateway-stripe-and-woocommerce-integration' ) ) );
                                            }
                                        }
                                    }

                                }
                           }
                           break;
                       */
                       case 'payment_intent.succeeded':
                       case 'payment_intent.payment_failed':
                            if (isset($decoded['data']['object']['id']) && !empty($decoded['data']['object']['id'])) {
                                $intent_id = sanitize_text_field($decoded['data']['object']['id']);
                                
                                if (isset($decoded['data']['object']['metadata']['order_id']) && !empty($decoded['data']['object']['metadata']['order_id'])) {
                                    $order_id = $decoded['data']['object']['metadata']['order_id'];
                                    if (!$order = wc_get_order( $order_id )) {
                                        //if sequential plugin is installed payapl response return order no instead of order id. Then get order id from order number
                                        if(class_exists('Wt_Advanced_Order_Number')){ 
                                            $args    = array(
                                                        'post_type'      => 'shop_order',
                                                        'post_status'    => 'any',
                                                        'meta_query'     => array(
                                                            array(
                                                                'key'        => '_order_number',
                                                                'value'      => $order_id,  //here you pass the Order Number
                                                                'compare'    => '=',
                                                            )
                                                        )
                                                    );
                                            $query   = new WP_Query( $args );
                                            if ( !empty( $query->posts ) ) {
                                                 $order_id = $query->posts[ 0 ]->ID;
                                            } 
                                        }                            
                                    }
                                }
                                else{
                                    if(true === EH_Stripe_Payment::wt_stripe_is_HPOS_compatibile()){
                                        $meta = $wpdb->get_results($wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = '_eh_stripe_payment_intent' AND meta_value= %s", $intent_id) );                                         
                                    }
                                    else{
                                        $meta = $wpdb->get_results($wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_eh_stripe_payment_intent' AND meta_value= %s", $intent_id) );                                        
                                    }                                

                                    if (!empty($meta) && isset($meta[0]->post_id)) {
                                        $order_id = $meta[0]->post_id;
                                        
                                   }
                                }

                                if (!empty($order_id)) {
                                    if($order = wc_get_order( $order_id )){
                                        $request = array('id' => $intent_id);
                                        $reqst_json = json_encode($request );
                                        if ( 'on-hold' === $order->status || 'pending' === $order->status) {

                                        //charges array not present by default for API version 2022-11-15 onwards
                                        if(isset($decoded['data']['object']['charges']) && !empty($decoded['data']['object']['charges'])){
                                            $charges = $decoded['data']['object']['charges'];
                                        }
                                        else{
                                            $expanded_intent = \Stripe\PaymentIntent::retrieve(array('id' => $intent_id, 'expand' => array('latest_charge') ) );
                                            $charges = isset($expanded_intent->latest_charge) ? $expanded_intent->charges : array();
                                        }

                                            if (isset($decoded['data']['object']['status']) && 'succeeded' === $decoded['data']['object']['status']) {
                                                $obj1 = new EH_Stripe_Payment();
                                                $charge_param = $obj1->make_charge_params($charges['data'][0], $order_id);
                                                EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'update', '_eh_stripe_payment_charge', $charge_param, false);
                                                
                                                 $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
                                                if (true === $charges['data'][0]['paid']) {

                                                    if(true === $charges['data'][0]['captured']){
                                                        $order->payment_complete($charge_param['id']);
                                                    }
                                                    if (!$charges['data'][0]['captured']) {
                                                        $order->update_status('on-hold');
                                                    }
                                                    $order->add_order_note(__('Payment Status : ', 'payment_gateway_stripe_and_woocommerce_integration') . ucfirst($charge_param['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment_gateway_stripe_and_woocommerce_integration') . $charge_param['source_type'] . '. ' . __('Charge Status :', 'payment_gateway_stripe_and_woocommerce_integration') . $charge_param['captured'] . (is_null($charge_param['transaction_id']) ? '' : '. <br>'.__('Transaction ID : ','payment_gateway_stripe_and_woocommerce_integration') . $charge_param['transaction_id']));
                                                    WC()->cart->empty_cart();
                                                    EH_Stripe_Log::log_update('live', $charge_param, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());

                                                } else {
                                                    EH_Stripe_Log::log_update('dead', $charges['data'][0], get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());
                                                }                                  


                                            } 
                                            else {
                                                // Set order status to payment failed
                                                $reason = 'Payment failed';
                                                if(isset($charges['data'][0]['failure_message']) && !empty($charges['data'][0]['failure_message'])){
                                                    $reason .= ' - ' .$charges['data'][0]['failure_message'];
                                                }

                                               $order->update_status( 'failed', sprintf( __( 'Payment failed.', 'payment_gateway_stripe_and_woocommerce_integration' ) ) );
                                            } 
                                        }                                 
                                    }
                                }
                                 
                            }
                            
                           break;


                        case 'source.chargeable': 

                                if (isset($decoded['data']['object']['metadata']['order_id']) && !empty($decoded['data']['object']['metadata']['order_id'])) {
                                    $order_id = $decoded['data']['object']['metadata']['order_id'];
                                }
                                elseif(isset($decoded['data']['object']['redirect']['return_url']) && !empty($decoded['data']['object']['redirect']['return_url'])){ 
                                    $return_url = $decoded['data']['object']['redirect']['return_url'];
                                    $arr_parts = wp_parse_url($return_url);
                                    if(isset($arr_parts) && !empty($arr_parts) && isset($arr_parts['query']) && !empty($arr_parts['query'])){ 
                                        wp_parse_str($arr_parts['query'], $arr_params);
                                        if(!empty($arr_params) && isset($arr_params['order_id']) && !empty($arr_params['order_id'])){
                                             $order_id = $arr_params['order_id'];
                                        }
                                    }
                                }
                                if(isset($order_id) && !empty($order_id)){ 
                                    $source_id = sanitize_text_field($decoded['data']['object']['id']);

                                    $order = wc_get_order( $order_id );
                                    if($order && $order->has_status('on-hold')){ 

                                        //check stripe vendor folder is exist
                                        if (!class_exists('Stripe\Stripe')) {
                                            include(EH_STRIPE_MAIN_PATH . "vendor/autoload.php");
                                        }
                                        $objKlarna = new EH_Klarna_Gateway();

                                        //check the source stats is chargeable
                                        $source_response = \Stripe\Source::retrieve($source_id);
                                        if (isset($source_response->status) && !empty($source_response->status) && 'chargeable' == $source_response->status) {
                                            
                                            $charge_response = \Stripe\Charge::create($objKlarna->eh_make_charge_params( $order, $source_response->id), array(
                                                    'idempotency_key' => $order->get_order_key()
                                                )); 

                                            $objKlarna->eh_process_payment_response($charge_response, $order, true);
                                        }

                                    }

                                }
                                

                            break; 

                        case 'checkout.session.expired':
                                if (isset($decoded['data']['object']['metadata']['order_id']) && !empty($decoded['data']['object']['metadata']['order_id'])) {
                                    $order_id = $decoded['data']['object']['metadata']['order_id'];
                                }
                                elseif(isset($decoded['data']['object']['success_url']) && !empty($decoded['data']['object']['success_url'])){ 
                                    $arr_parts = wp_parse_url($decoded['data']['object']['success_url']);
                                    if(isset($arr_parts) && !empty($arr_parts) && isset($arr_parts['query']) && !empty($arr_parts['query'])){ 
                                        wp_parse_str($arr_parts['query'], $arr_params);
                                        if(!empty($arr_params) && isset($arr_params['order_id']) && !empty($arr_params['order_id'])){
                                             $order_id = $arr_params['order_id'];
                                        }
                                    }
                                }
                                if(isset($order_id) && !empty($order_id)){ 
                                    $order = wc_get_order( $order_id );
                                    if($order){   
                                        if('eh_stripe_checkout' === $order->get_payment_method() && 'processing' != $order->status && 'completed' != $order->status ){ 

                                            $session_id = (isset($decoded['data']['object']['id']) && !empty($session_id)) ? $decoded['data']['object']['id'] : '';
                                            if(empty($session_id)){
                                                return;
                                            }                                                  
                                            $session = \Stripe\Checkout\Session::retrieve($session_id);

                                            $intent = get_post_meta( $order_id, '_eh_stripe_payment_intent', true);                                     
                                            if($intent == $session->payment_intent){
                                               $order->update_status( 'cancelled', __( 'Stripe checkout abandoned.', 'payment_gateway_stripe_and_woocommerce_integration' ));

                                            }
                                        }
                                    }
                                }

                            break;
                    case 'checkout.session.async_payment_succeeded':
                    case 'checkout.session.async_payment_failed':
                            if (isset($decoded['data']['object']['metadata']['order_id']) && !empty($decoded['data']['object']['metadata']['order_id'])) {
                                $order_id = $decoded['data']['object']['metadata']['order_id'];
                                $order = wc_get_order( $order_id );
                                
                                if($order &&  ('on-hold' === $order->status || 'pending' === $order->status)){
                                   if(isset($decoded['data']['object']['payment_intent']) && !empty($decoded['data']['object']['payment_intent'])){
                                        $intent_id = $decoded['data']['object']['payment_intent'];

                                        $obj_checkout = new Eh_Stripe_Checkout();
                                        $obj_checkout->wt_process_payment_intent($order_id, $order, $intent_id);


                                   }

                                }

                            }
                        break;
                                                                    
                       default:
                           // code...
                           break;
                   }
                }

            }

        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }  
        catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }   
        catch(Exception $e){
            http_response_code(400);
            exit();
        }     

        
        die;
    }
  
}