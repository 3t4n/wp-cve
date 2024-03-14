<?php

if (!defined('ABSPATH')) {
    exit;
}  

/**
 * EH_Klarna_Gateway class.
 *
 * @extends EH_Stripe_Payment
 */
#[\AllowDynamicProperties]
class EH_Boleto extends WC_Payment_Gateway {

    /**
     * Constructor
     */
    public function __construct() { 
        
        $this->id                 = 'eh_boleto_stripe';
        $this->method_title       = __( 'Boleto', 'payment-gateway-stripe-and-woocommerce-integration' );

        $this->method_description =  __( 'Boleto is an official (regulated by the Central Bank of Brazil) payment method in Brazil.' . '<a  class="thickbox" href="'.EH_STRIPE_MAIN_URL_PATH . 'assets/img/boleto-preview.png?TB_iframe=true&width=100&height=100">[Preview] </a>', 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->supports = array(
            'products',
            'refunds',
        );

        // Load the form fields.
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        
        $stripe_settings               = get_option( 'woocommerce_eh_stripe_pay_settings' );
        $this->capture_now = ((isset($stripe_settings['eh_stripe_capture']) && !empty($stripe_settings['eh_stripe_capture'])) ? $stripe_settings['eh_stripe_capture'] : '');
        
        $this->title                   = __($this->get_option( 'eh_stripe_boleto_title' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->description             = __($this->get_option( 'eh_stripe_boleto_description' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->enabled                 = $this->get_option( 'enabled' );
        $this->eh_order_button         = $this->get_option( 'eh_stripe_boleto_order_button');
        $this->order_button_text       = __($this->eh_order_button, 'payment-gateway-stripe-and-woocommerce-integration');

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        // Set stripe API key.
       
        \Stripe\Stripe::setApiKey(EH_Stripe_Payment::get_stripe_api_key());
        \Stripe\Stripe::setAppInfo( 'WordPress Stripe Payment Gateway for WooCommerce', EH_STRIPE_VERSION, 'https://www.webtoffee.com/product/woocommerce-stripe-payment-gateway/', 'pp_partner_KHip9dhhenLx0S' );
        
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));
       add_action( 'woocommerce_api_eh_boleto', array( $this, 'eh_boleto_callback_handler' ) );
       add_filter( 'woocommerce_payment_successful_result', array( $this, 'modify_successful_payment_result' ), 9999, 2 );
       add_action( 'woocommerce_available_payment_gateways',array($this, 'eh_disable_gateway_for_order_pay' ));



    }


    /**
     * Initialize form fields in klarna payment settings page.
     */
    public function init_form_fields() {

        $stripe_settings   = get_option( 'woocommerce_eh_stripe_pay_settings' );
        
        $this->form_fields = array(
            'eh_oxxo_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%sSupported currency: %sBRL%sStripe accounts in the following countries can accept the payment: %sBrazil%s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><ul><li>', '<b>', '</b></li><li>', '<b>', '</b></li></ul></div>'),
            ),
            'eh_stripe_boleto_form_title'   => array(
                'type'        => 'title',
                'class'       => 'eh-css-class',
            ),
            'enabled'                       => array(
                'title'       => __('Boleto','payment-gateway-stripe-and-woocommerce-integration'),
                'label'       => __('Enable','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'checkbox',
                'default'     => isset($stripe_settings['eh_stripe_boleto']) ? $stripe_settings['eh_stripe_boleto'] : 'no',
                'desc_tip'    => __('Enables to accept payments using Boleto.','payment-gateway-stripe-and-woocommerce-integration'),
            ),

            'eh_stripe_boleto_title'         => array(
                'title'       => __('Title','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'text',
                'description' =>  __('Input title for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'default'     =>isset($stripe_settings['eh_stripe_boleto_title']) ? $stripe_settings['eh_stripe_boleto_title'] : __('Boleto', 'payment-gateway-stripe-and-woocommerce-integration'),
                'desc_tip'    => true,
            ),
            'eh_stripe_boleto_description'     => array(
                'title'       => __('Description','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'textarea',
                'css'         => 'width:25em',
                'description' => __('Input texts for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'default'     =>isset($stripe_settings['eh_stripe_boleto_description']) ? $stripe_settings['eh_stripe_boleto_description'] : __('Accept payments using Boleto.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'desc_tip'    => true
            ),

            'eh_stripe_boleto_order_button'    => array(
                'title'       => __('Order button text', 'payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'text',
                'description' => __('Input a text that will appear on the order button to place order at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
                'default'     => isset($stripe_settings['eh_stripe_boleto_order_button']) ? $stripe_settings['eh_stripe_boleto_order_button'] :__('Pay via Boleto', 'payment-gateway-stripe-and-woocommerce-integration'),
                'desc_tip'    => true
            ),
            'eh_boleto_webhook_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%1$sTo accept payments via Boleto payment method, you must configure the webhook endpoint and subscribe to relevant events. %2$sClick here%3$s to know more%4$s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><p>', '<a target="_blank" href="https://www.webtoffee.com/setting-up-webhooks-and-supported-webhooks/">', '</a>', '</p></div>'),
            ),
        );   
    }
 
     
    public function get_icon() {
        $style = version_compare(WC()->version, '2.6', '>=') ? 'style="margin-left: 0.3em"' : '';
        $icon = '';
        
        //$icon .= '<img src="' . WC_HTTPS::force_https_url(EH_STRIPE_MAIN_URL_PATH . 'assets/img/boleto.svg') . '" width="55" alt="Boleto" title="Boleto" ' . $style . ' />';
        return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
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
     * Outputs scripts used for stripe payment.
     */
    public function payment_scripts() {

        if(!$this->is_available()){
            return false;
        }

        $stripe_settings   = get_option( 'woocommerce_eh_stripe_pay_settings' );
        if ( (is_checkout()  && !is_order_received_page())) {
            wp_register_script('stripe_v3_js', 'https://js.stripe.com/v3/');

         wp_enqueue_script('eh_boleto_js', plugins_url('assets/js/eh-boleto.js', EH_STRIPE_MAIN_FILE), array('stripe_v3_js','jquery'),EH_STRIPE_VERSION, true);
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


            $stripe_params = array(
                'key' => $public_key,
                'currency' => get_woocommerce_currency(),
            );

            $stripe_params['is_checkout'] = ( is_checkout() && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no';

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
                    $stripe_params['billing_email']    = method_exists($order, 'get_billing_email')    ? $order->get_billing_email()    : $order->billing_email;
                    $stripe_params['billing_phone']    = method_exists($order, 'get_billing_phone')    ? $order->get_billing_phone()    : $order->billing_phone;
                    $stripe_params['currency']    =  ((WC()->version < '2.7.0') ? $order->order_currency : $order->get_currency());
                }                       
            }
            $stripe_params['version'] = EH_Stripe_Payment::wt_get_api_version();
           wp_localize_script('eh_boleto_js', 'eh_boleto_val', apply_filters('eh_boleto_val', $stripe_params));
        }
    }

    public function payment_fields() {
        $description = $this->get_description();
        echo '<div class="status-box">';
        
        if ($this->description) {
            echo apply_filters('eh_stripe_desc', wpautop(wp_kses_post("<span>" . $this->description . "</span>")));
        }
        echo "</div>";
         ?>
        <fieldset id="eh-<?php echo esc_attr( $this->id ); ?>-cc-form" class="eh-credit-card-form eh-payment-form" style="background:transparent;">  
               <div class="form-row  form-row-wide">
                    <!--
                    Using a label with a for attribute that matches the ID of the
                    Element container enables the Element to automatically gain focus
                    when the customer clicks on the label.
                    -->
                    <label for="eh-boleto-element"><?php esc_html_e( 'CPF/CNPJ', 'payment-gateway-stripe-and-woocommerce-integration' ); ?> <span class="required">*</span></label>
                    <div id="eh-stripe-boleto-element"  class="eh-stripe-elements-field">
                       <input id="eh-boleto-element" name="eh-boleto-element" required />
                    </div>
                </div> 
              
                <div class="clear"></div>

            <!-- Used to display form errors -->
            <div class="eh-boleto-errors" role="alert" style="color:#ff0000"></div>
            <div class="clear"></div>
        </fieldset><?php
    }

    /**
     *Process stripe payment.
     */
    public function process_payment($order_id) { 
        $order = wc_get_order( $order_id );
        
        try{ 

            $currency =  $order->get_currency();
            $amount = EH_Stripe_Payment::get_stripe_amount(((WC()->version < '2.7.0') ? $order->order_total : $order->get_total())) ;

             $customer = $this->create_stripe_customer($order_id, ((WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email()));
                
            if (!empty($customer) && isset($customer->id)) {
                $user_id = $order->get_user_id();
                update_user_meta($user_id, "_boleto_customer_id", sanitize_text_field($customer->id));

                $intent = $this->get_payment_intent_from_order( $order );
               
                $client = $this->get_clients_details();

                $payment_intent_args  = $this->get_charge_details($order, $client, $currency, $amount);

                if(! empty($intent)){

                    if ( $intent->status === 'succeeded' ) {
                        wc_add_notice(__('An error has occurred internally, due to which you are not redirected to the order received page. Please contact support for more assistance.', 'payment-gateway-stripe-and-woocommerce-integration'), $notice_type = 'error');
                        wp_redirect(wc_get_checkout_url());
                    }else{
                        $intent = \Stripe\PaymentIntent::create( $payment_intent_args , array(
                            'idempotency_key' => $order->get_order_key() . '-' . $customer->id
                        ));
                    }
                }else{
                    $intent = \Stripe\PaymentIntent::create( $payment_intent_args , array(
                        'idempotency_key' => $order->get_order_key() . '-' . $customer->id
                    ));
                } 
                $this->save_payment_intent_to_order( $order, $intent );

                EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_intent', $intent->id, false);   
                if (isset($intent->status) && ( $intent->status == 'requires_payment_method' )) {
                    return array(
                        'result'        => 'success',
                        'intent_secret' => $intent->client_secret,
                        'gateway' => $this->id,
                        'boleto_id' => (isset($_REQUEST['eh-boleto-element']) ? $_REQUEST['eh-boleto-element'] : ''),
                        //'redirect'      => $this->get_return_url($order)
                        'redirect'      => add_query_arg(array('order_id' => $order_id, 'payment_intent' => $intent->id), WC()->api_request_url('EH_Boleto'))
                    );
                }
                elseif (isset($intent->status) && ( $intent->status == 'requires_action' ) &&
                    $intent->next_action->type == 'boleto_display_details') {

                    return array(
                        'result'        => 'success',
                        'redirect'      => $intent->next_action->boleto_display_details->hosted_voucher_url,
                    );
                } else {
                     $this->eh_process_payment_response( $intent,$order );
                    $redirect_url = $this->get_return_url( $order );
                    wp_safe_redirect($redirect_url);
                }
            }
            else{
                throw new Exception( __( 'Unable to process this payment, please try again.', 'payment-gateway-stripe-and-woocommerce-integration' ));
            }

        }
        catch(Exception $e){
            $order->update_status( 'failed', sprintf( __( 'Boleto payment failed: %s', 'payment-gateway-stripe-and-woocommerce-integration' ),$e->getMessage() ) );
            
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
    public function get_charge_details( $wc_order, $client, $currency, $amount) {
        $product_name = array();
        $order_id = $wc_order->get_id();
        foreach ($wc_order->get_items() as $item) {
            array_push($product_name, $item['name']);
        }

        $charge = array(
            'payment_method_types' => array('boleto'),
            'amount' => $amount,
            //'payment_method' => $payment_method,
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
        
        $eh_stripe = get_option("woocommerce_eh_boleto_stripe_settings");

        $product_list = implode(' | ', $product_name);

        $charge['metadata']['Products'] = substr($product_list, 0, 499);
                
        $show_items_details = apply_filters('eh_stripe_show_items_in_payment_description', false);
                
        if($show_items_details){
            
            $charge['description']=$charge['metadata']['Products'] .' '.wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' Order #' . $wc_order->get_order_number();
        }
        //$charge['confirm'] =  true ;
        //$charge['return_url'] =  add_query_arg('order_id', $order_id, WC()->api_request_url('EH_Boleto')) ; 


       // if (!is_checkout_pay_page()) {
            $charge['shipping'] = array(
                'address' => array(
                    'line1' => (WC()->version < '2.7.0') ? $wc_order->shipping_address_1 : $wc_order->get_shipping_address_1(),
                    'line2' => (WC()->version < '2.7.0') ? $wc_order->shipping_address_2 : $wc_order->get_shipping_address_2(),
                    'city' => (WC()->version < '2.7.0') ? $wc_order->shipping_city : $wc_order->get_shipping_city(),
                    //'state' => (WC()->version < '2.7.0') ? $wc_order->shipping_state : $wc_order->get_shipping_state(),
                    'country' => (WC()->version < '2.7.0') ? $wc_order->shipping_country : $wc_order->get_shipping_country(),
                    'postal_code' => (WC()->version < '2.7.0') ? $wc_order->shipping_postcode : $wc_order->get_shipping_postcode()
                ),
                'name' => ((WC()->version < '2.7.0') ? $wc_order->shipping_first_name : $wc_order->get_shipping_first_name()) . ' ' . ((WC()->version < '2.7.0') ? $wc_order->shipping_last_name : $wc_order->get_shipping_last_name()),
                'phone' => (WC()->version < '2.7.0') ? $wc_order->billing_phone : $wc_order->get_billing_phone(),
            );
       // }
        
       return apply_filters('eh_boleto_payment_intent_args', $charge);
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

    public function eh_boleto_callback_handler() {
        if (isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id'])) {
            $order_id = $_REQUEST['order_id'];
            $order = wc_get_order( $order_id );

        }
        if (isset($_REQUEST['payment_intent']) && !empty($_REQUEST['payment_intent'])) {
            $intent_id = $_REQUEST['payment_intent'];
            $intent_result = \Stripe\PaymentIntent::retrieve( $intent_id );
            if (!empty($intent_result)) {
                $this->eh_process_payment_response($intent_result, $order);
                 $redirect_url = $this->get_return_url( $order );
                wp_safe_redirect($redirect_url);
            }
            else{
                if ($order) {
                $order->update_status( 'failed', __( 'Stripe payment failed', 'payment-gateway-stripe-and-woocommerce-integration' ) );
                }
                
                wc_add_notice( __( 'Unable to process this payment.', 'payment-gateway-stripe-and-woocommerce-integration' ), 'error' );
                wp_safe_redirect( wc_get_checkout_url() );
            }
        }
        else{
            if ($order) {
                $order->update_status( 'failed', __( 'Stripe payment failed', 'payment-gateway-stripe-and-woocommerce-integration' ) );
            }
            
            wc_add_notice( __( 'Unable to process this payment.', 'payment-gateway-stripe-and-woocommerce-integration' ), 'error' );
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
        
        if ($response->status == 'requires_action') {
            $order->update_status('on-hold', 'Order awaiting for payment.');
        }
        else{
            // Stores charge data.
            $obj1 = new EH_Stripe_Payment();
            $charge_response = end($response->charges->data);
            if (!empty($charge_response)) {
                $charge_param = $obj1->make_charge_params($charge_response , $order_id);
               
                 EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_charge', $charge_param, false); 
                
                //$order_id  = version_compare(WC_VERSION, '2.7.0', '<') ? $order->id : $order->get_id();
                $captured = ( isset( $charge_response->captured )) ? 'Captured' : 'Uncaptured';

                // Stores charge capture data.
                if ( version_compare(WC_VERSION, '2.7.0', '<') ) {
                    update_post_meta( $order_id, '_eh_boleto_charge_captured', $captured );
                } else {
                    $order->update_meta_data( '_eh_boleto_charge_captured', $captured );
                }
            
                $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600); 
                
                $order->set_transaction_id( $charge_response->id );

                if($response->status == 'succeeded'){
                    if ($charge_response->paid == true) {

                        if($charge_response->captured == true){
                            $order->payment_complete( $charge_response->id );
                        }
                        if (!$charge_response->captured) {
                            $order->update_status('on-hold');
                        }
                        $order->add_order_note( __('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($charge_response->status) .' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $charge_response->payment_method_details->type . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $captured . (is_null($charge_response->balance_transaction) ? '' :'. Transaction ID : ' . $charge_response->balance_transaction) );
                        WC()->cart->empty_cart();
                        EH_Stripe_Log::log_update('live', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());
                        return array(
                            'result' => 'success',
                            'redirect' => $this->get_return_url($order),
                        );
                    } else {
                        $order->update_status( 'failed', __( 'Stripe payment failed.', 'payment-gateway-stripe-and-woocommerce-integration' ) );
                        wc_add_notice($charge_response->status, $notice_type = 'error');
                        EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());
                    }
                }
                elseif($response->status != 'processing'){
                    $order->update_status( 'failed', __( 'Stripe payment failed.', 'payment-gateway-stripe-and-woocommerce-integration' ) );
                        wc_add_notice($$charge_response->status, $notice_type = 'error');
                        EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order->get_order_number());

                }
                
            }        
        }

        return true;
    }

    public function modify_successful_payment_result( $result, $order_id ) { 
       
        // Only redirects with intents need to be modified.
        if ( ! isset( $result['intent_secret'] ) || !isset($result['gateway']) || (isset($result['gateway']) && $result['gateway'] != $this->id)  ) {
          
            return $result;
        }

        $verification_url = add_query_arg(
            array(
                'order'         => $order_id,
                '_wpnonce'      => wp_create_nonce( 'eh_stripe_confirm_payment_intent' ),
                'redirect_to'   => rawurlencode( $result['redirect'] ),
                'boleto_id'   =>  $result['boleto_id']
             ), WC_AJAX::get_endpoint( 'eh_stripe_verify_payment_intent' )
        );
         $redirect_url = sprintf( '#confirm-boleto-pi-%s:%s', $result['intent_secret'], rawurlencode( $verification_url ) );
        return array(
            'result'   => 'success',
            'redirect' => $redirect_url,
        );
    }

    /**
     * Disable stripe checkout gateway for order-pay page 
     * @since 3.2.7
     */ 
    function eh_disable_gateway_for_order_pay( $available_gateways ) {
        if ( is_wc_endpoint_url( 'order-pay' ) ) {
            
           unset( $available_gateways['eh_boleto_stripe'] );
        }
        return $available_gateways;
    }    
  
}