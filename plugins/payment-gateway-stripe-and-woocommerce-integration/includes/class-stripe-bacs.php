<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles stripe checkout payment method.
 *
 * @extends WC_Payment_Gateway
 *
 */
#[\AllowDynamicProperties]
class Eh_Bacs extends WC_Payment_Gateway {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		$this->id                 = 'eh_bacs';
		$this->method_title       = __( 'Bacs Direct Debit', 'payment-gateway-stripe-and-woocommerce-integration' );
		$this->method_description =  __( 'Pay with Bacs Direct Debit', 'payment-gateway-stripe-and-woocommerce-integration' );
		$this->supports           = array(
			'products',
			'refunds',
		);

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();
        
        $this->eh_stripe_option        = get_option("woocommerce_eh_stripe_pay_settings");
		$this->title                   = __($this->get_option( 'eh_bacs_title' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->description             = __($this->get_option( 'eh_bacs_description' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->method_description      = __( '<p style="max-width: 88%;">Enables users in the UK can accept Bacs Direct Debit payments from customers with a UK bank account. <a class="thickbox" href="'.EH_STRIPE_MAIN_URL_PATH . 'assets/img/bacs-preview.png?TB_iframe=true&width=100&height=100" >Preview</a>', 'payment-gateway-stripe-and-woocommerce-integration' );
        
        $this->enabled                     = $this->get_option( 'enabled' );
        $this->eh_order_button             = $this->get_option( 'eh_bacs_order_button');
        $this->order_button_text           = __($this->eh_order_button, 'payment-gateway-stripe-and-woocommerce-integration');
        $this->stripe_checkout_page_locale = $this->get_option( 'eh_bacs_page_locale');

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

       add_action( 'woocommerce_api_eh_bacs', array( $this, 'eh_bacs_stripe_checkout_order_callback' ), 30 );

       // add_action( 'woocommerce_available_payment_gateways',array($this, 'eh_disable_gateway_for_order_pay' ));
        add_action( 'set_logged_in_cookie', array( $this, 'eh_set_cookie_on_current_request' ) );

        // Set stripe API key.
        \Stripe\Stripe::setApiKey(EH_Stripe_Payment::get_stripe_api_key());
        \Stripe\Stripe::setAppInfo( 'WordPress Stripe Payment Gateway for WooCommerce', EH_STRIPE_VERSION, 'https://www.webtoffee.com/product/woocommerce-stripe-payment-gateway/', 'pp_partner_KHip9dhhenLx0S' );
        
	}

	/**
	 * Initialize form fields in stripe checkout payment settings page.
     * @since 3.2.6
	 */
	public function init_form_fields() {


		$this->form_fields = array(
            'eh_bacs_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%sSupported currencies: %sGBP%sStripe accounts in the following countries can accept the payment: %sUK%s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><ul><li>', '<b>', '</b></li><li>', '<b>', '</b></li></ul></div>'),
            ),

            'eh_bacs_form_title' => array(
                'type' => 'title',
                'class'=> 'eh-table-css-class',
            ),

			'enabled'      => array(
				'title'       => __('Bacs Direct Debit','payment-gateway-stripe-and-woocommerce-integration'),
				'label'       => __('Enable','payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'checkbox',
                'default'     => 'no',
                'desc_tip'    => __('Enable to accept Bacs Direct Debit payments.','payment-gateway-stripe-and-woocommerce-integration'),
			),
			'eh_bacs_title'         => array(
				'title'       => __('Title','payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'text',
				'description' =>  __('Input title for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
				'default'     => __('Bacs Direct Debit', 'payment-gateway-stripe-and-woocommerce-integration'),
				'desc_tip'    => true,
			),
			'eh_bacs_description'     => array(
				'title'       => __('Description','payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'textarea',
				'css'         => 'width:25em',
				'description' => __('Input texts for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
			 	'default'     => __('Secure payment via Bacs Direct Debit.', 'payment-gateway-stripe-and-woocommerce-integration'),
			 	'desc_tip'    => true
			),

			'eh_bacs_order_button'    => array(
				'title'       => __('Order button text', 'payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'text',
				'description' => __('Input a text that will appear on the order button to place order at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
				'default'     => __('Pay via Bacs Direct Debit', 'payment-gateway-stripe-and-woocommerce-integration'),
				'desc_tip'    => true
            ),
            'eh_bacs_page_locale' => array(
                'title'      => __( 'Locale', 'payment-gateway-stripe-and-woocommerce-integration' ),
                'type'       => 'select',
                'class'      => 'wc-enhanced-select',
                'options'    => array(
                    
                    'auto' => __('Browser\'s locale', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'bg'  => __('Bulgarian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'cs'  => __('Czech', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'da'  => __('Danish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'nl'  => __('Dutch', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'en'  => __('English', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'et'  => __('Estonian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'fi'  => __('Finnish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'fr'  => __('French', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'de'  => __('German', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'el'  => __('Greek', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'hu'  => __('Hungarian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'it'  => __('Italian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'ja'  => __('Japanese', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'lv'  => __('Latvian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'lt'  => __('Lithuanian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'ms'  => __('Malay', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'mt'  => __('Maltese', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'nb'  => __('Norwegian Bokmål', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'pl'  => __('Polish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'pt'  => __('Portuguese', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'ro'  => __('Romanian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'ru'  => __('Russian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'zh'  => __('Simplified Chinese', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'sl'  => __('Slovak', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'es'  => __('Spanish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'sv'  => __('Swedish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'tr'  => __('Turkish', 'payment-gateway-stripe-and-woocommerce-integration'),
                ),
                'description' => sprintf(__('Choose a desired locale code from the drop down (Languages supported by Bacs Direct Debit are listed)', 'payment-gateway-stripe-and-woocommerce-integration')),
                'default'     => 'auto',
                'desc_tip'    => true,
            ),
            'eh_bacs_webhook_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%1$sTo accept payments via Bacs Direct Debit payment method, you must configure the webhook endpoint and subscribe to relevant events. %2$sClick here%3$s to know more%4$s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><p>', '<a target="_blank" href="https://www.webtoffee.com/setting-up-webhooks-and-supported-webhooks/">', '</a>', '</p></div>'),
            ),            
		);   
    }
    
	/**
     * Checks if gateway should be available to use.
     * @since 3.2.6
     */
	public function is_available() {

        if ('yes' === $this->enabled) {
           
            if (isset($this->eh_stripe_option['eh_stripe_mode']) && 'test' === $this->eh_stripe_option['eh_stripe_mode']) {
                if (!isset($this->eh_stripe_option['eh_stripe_test_publishable_key']) || !isset($this->eh_stripe_option['eh_stripe_test_secret_key']) || ! $this->eh_stripe_option['eh_stripe_test_publishable_key'] || ! $this->eh_stripe_option['eh_stripe_test_secret_key']) {
                    return false;
                }
            } else {
                if (!isset($this->eh_stripe_option['eh_stripe_live_secret_key']) || !isset($this->eh_stripe_option['eh_stripe_live_publishable_key']) || !$this->eh_stripe_option['eh_stripe_live_secret_key'] || !$this->eh_stripe_option['eh_stripe_live_publishable_key']) {
                    return false;
                }
            }

            return true;
        }
        return false; 
    }

    /**
	 * Proceed with current request using new login session (to ensure consistent nonce).
	 */
	public function eh_set_cookie_on_current_request( $cookie ) {
		$_COOKIE[ LOGGED_IN_COOKIE ] = $cookie;
    }
    
    /**
	 * Get gateway icon.
	 *
	 */
	public function get_icon() {

        $icon = '';
       
		return apply_filters('eh_bacs_icon', $icon, $this->id);
	}

	/**
	 * Payment form on checkout page
     * @since 3.2.6
	 */
	public function payment_fields() {
		$user        = wp_get_current_user();
		$total       = WC()->cart->total;
		$description = $this->get_description();
		echo '<div class="status-box">';
        
        if ($this->description) {
            echo apply_filters('eh_stripe_desc', wpautop(wp_kses_post("<span>" . $this->description . "</span>")));
        }
        echo "</div>";
	}
    
    /**
     * loads stripe checkout scripts.
     * @since 3.2.6
     */
    public function payment_scripts(){
        
        if(!$this->is_available()){
            return false;
        }

        if(is_checkout()){ 
             wp_register_script('stripe_v3_js', 'https://js.stripe.com/v3/');
            wp_enqueue_script('eh_checkout_script', EH_STRIPE_MAIN_URL_PATH . 'assets/js/eh-checkout.js',array('stripe_v3_js','jquery'),EH_STRIPE_VERSION ,true);
            
            if(isset($this->eh_stripe_option['eh_stripe_mode'])){

                if ('test' == $this->eh_stripe_option['eh_stripe_mode']) {
                    $public_key = $this->eh_stripe_option['eh_stripe_test_publishable_key'];
                    $secret_key = $this->eh_stripe_option['eh_stripe_test_secret_key'];
                } else {
                    $public_key = $this->eh_stripe_option['eh_stripe_live_publishable_key'];
                    $secret_key = $this->eh_stripe_option['eh_stripe_live_secret_key'];
                }
            }

            $eh_bacs_params = array(
                'key'                                           => isset($public_key) ? $public_key : '',
                'wp_ajaxurl'                                    => admin_url("admin-ajax.php"),
                'wc_ajaxurl'                                    => WC_AJAX::get_endpoint( '%%change_end%%' ),
            );
            $eh_bacs_params['version'] = EH_Stripe_Payment::wt_get_api_version(); 
            wp_localize_script( 'eh_checkout_script', 'eh_stripe_checkout_params', $eh_bacs_params);
        }
    }

     /**
	 * Process the payment
	 *
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
        $currency =  $order->get_currency();
        
        $total = EH_Stripe_Payment::get_stripe_amount( $order->get_total(), get_woocommerce_currency());
        
        $images    = array();
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            
            $quantity_label =  0 < $cart_item['quantity'] ?  $cart_item['quantity']  : '';
            $_product       =  wc_get_product( $cart_item['data']->get_id()); 

            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($_product->get_id()));
            if(isset($featured_image[0])){
                $images[] = $featured_image[0];
            }
           
        }
        
        $email = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
        
        $capture_method = 'automatic';
        if(isset($this->eh_stripe_option['eh_stripe_capture'])){

            if ('no' == $this->eh_stripe_option['eh_stripe_capture']) {
                $capture_method = 'manual';
            }
        }

        $session_data = array(
            'payment_method_types' => ['bacs_debit'],
            'mode' => 'payment',
            'payment_intent_data' => [
                'description' => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' Order #' . $order->get_order_number(),
                //'capture_method' => $capture_method,
                'setup_future_usage' => 'off_session',
            ],
             'metadata' => ['order_id' => $order_id],

          'success_url'=> add_query_arg(array('session_id' => '{CHECKOUT_SESSION_ID}', 'order_id' => $order_id, '_wpnonce' => wp_create_nonce('eh_checkout_nonce')), WC()->api_request_url('EH_Bacs')),
            'cancel_url' => add_query_arg(array('action' => 'cancel_order', 'order_id' => $order_id, '_wpnonce' => wp_create_nonce('eh_checkout_nonce')), WC()->api_request_url('EH_Bacs')),
        );

        $session_data['line_items'][] = array(
            'quantity' => 1,
            'price_data' => array(
                'currency' => strtolower($currency),
                'unit_amount' => $total,
                'product_data' => array(
                    'name' => esc_html( __( 'Total', 'payment-gateway-stripe-and-woocommerce-integration' ) ),
                ),
            ),
           
            
        );          
        
        //if there is image for the product add image to the line items 
        $count = 0;

        foreach ($images as $key => $value) {
            
            if( !empty($value)) {

               $count = 1;
            }
        }

        if( $count == 1 ) { 
            $session_data['line_items'][0]['price_data']['product_data']['images'][] =  array($images);
        }

        if(!empty($email)){
            $session_data['customer_email'] = $email;
        }
        
        $session_data['locale'] = $this->stripe_checkout_page_locale;

        $session = \Stripe\Checkout\Session::create($session_data);

        return  array(
            'result'   => 'success',
            'redirect' => $this->get_payment_session_checkout_url( $session->id, $order ),
        );
    }

    function get_payment_session_checkout_url($session_id, $order){

        return sprintf(
            '#response=%s',
            base64_encode(
                wp_json_encode(
                    array(
                        'session_id' => $session_id,
                        'order_id'      => (WC()->version < '2.7.0') ? $order->id : $order->get_id(),
                        'time'          => rand(
                            0,
                            999999
                        ),
                    )
                )
            )
        );

    }

	/**
     * creates order after checkout session is completed.
     * @since 3.2.6
     */
    public function eh_bacs_stripe_checkout_order_callback() { 
      
        if(!EH_Helper_Class::verify_nonce(EH_STRIPE_PLUGIN_NAME, 'eh_checkout_nonce'))
        {
            die(_e('Access Denied', 'payment-gateway-stripe-and-woocommerce-integration'));
        }

        if (isset($_REQUEST['action']) && 'cancel_order' == $_REQUEST['action']) { 
            $order_id = intval( $_GET['order_id'] );
            $order = wc_get_order($order_id);

            wc_add_notice(__('You have cancelled Bacs Direct Debit Session. Please try to process your order again.', 'payment-gateway-stripe-and-woocommerce-integration'), 'notice');
            wp_redirect(wc_get_checkout_url());
            exit;        
        }
        else{ 
            $session_id = sanitize_text_field( $_GET['session_id'] );
            $order_id = intval( $_GET['order_id'] );
            $order = wc_get_order($order_id);

            $obj = new EH_Stripe_Payment();
           
            $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
            
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            $payment_intent_id = $session->payment_intent;
            
            EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_intent', $payment_intent_id, false);

            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            if (isset($payment_intent->status) && $payment_intent->status == 'processing') {
               $order->update_status('on-hold');
               $order->add_order_note(__('Wait for the payment to succeed or fail.', 'payment-gateway-stripe-and-woocommerce-integration'));
                 // Return thank you page redirect.
                $result =  array(
                    'result'   => 'success',
                    'redirect'     => $obj->get_return_url($order),
                );

                wp_safe_redirect($result['redirect']);
                exit;

            }
            else{
                $charge_details = $payment_intent->charges['data'];
            
                foreach($charge_details as $charge){

                    $charge_response = $charge;  
                }

                $data = $obj->make_charge_params($charge_response, $order_id);
                
                if (true === $charge_response->paid) {

                    if(true === $charge_response->captured){
                        $order->payment_complete($data['id']);
                    }

                    if (!$charge_response->captured) {
                        $order->update_status('on-hold');
                    }

                    $order->set_transaction_id( $data['transaction_id'] );

                    $order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($data['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['source_type'] . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $data['captured'] . (is_null($data['transaction_id']) ? '' : '. <br>'.__('Transaction ID : ','payment-gateway-stripe-and-woocommerce-integration') . $data['transaction_id']));
                    WC()->cart->empty_cart();
                    
                     EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_charge', $data, false);
                    EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
                    
                    // Return thank you page redirect.
                    $result =  array(
                        'result'   => 'success',
                        'redirect'     => $obj->get_return_url($order),
                    );
                
                    wp_safe_redirect($result['redirect']);
                    exit;
                    
                } else {
                    wc_add_notice($data['status'], $notice_type = 'error');
                    EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
                }   
            }     
        }
       
    }
    
    /**
     * Disable stripe checkout gateway for order-pay page 
     * @since 3.2.7
     */ 
    function eh_disable_gateway_for_order_pay( $available_gateways ) {
        if ( is_wc_endpoint_url( 'order-pay' ) ) {
            
           unset( $available_gateways['eh_bacs'] );
        }
        return $available_gateways;
    }

    /**
     * process order refund
     * @since 3.2.6
     */
    public function process_refund($order_id, $amount = NULL, $reason = '') {
        
        $obj = new EH_Stripe_Payment();
		$client = $obj->get_clients_details();
		if ($amount > 0) {
			
            $data = EH_Helper_Class::wt_stripe_order_db_operations($order_id, null, 'get', '_eh_stripe_payment_charge', null, true);

            $status = $data['captured'];

			if ('Captured' === $status) {
				$charge_id = $data['id'];
				$currency = $data['currency'];
				$total_amount = $data['amount'];
						
				$wc_order = new WC_Order($order_id);
				$div = $amount * ($total_amount / ((WC()->version < '2.7.0') ? $wc_order->order_total : $wc_order->get_total()));
				$refund_params = array(
					'amount' => EH_Stripe_Payment::get_stripe_amount($div, $currency),
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
					$refund_response = $charge_response->refunds->create($refund_params);
					if ($refund_response) {
										
						$refund_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
						
						$data = $obj->make_refund_params($refund_response, $amount, ((WC()->version < '2.7.0') ? $wc_order->order_currency : $wc_order->get_currency()), $order_id);
						
                        EH_Helper_Class::wt_stripe_order_db_operations($order_id, $wc_order, 'add', '_eh_stripe_payment_refund', $data, false);

						$wc_order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __('Status : refunded ', 'payment-gateway-stripe-and-woocommerce-integration') . ' [ ' . $refund_time . ' ] ' . (is_null($data['transaction_id']) ? '' : '<br>' . __('Transaction ID : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['transaction_id']));
						EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Refund - Order #' . $wc_order->get_order_number());
						return true;
					} else {
						EH_Stripe_Log::log_update('dead', $data, get_bloginfo('blogname') . ' - Refund Error - Order #' . $wc_order->get_order_number());
						$wc_order->add_order_note(__('Reason : ', 'payment-gateway-stripe-and-woocommerce-integration') . $reason . '.<br>' . __('Amount : ', 'payment-gateway-stripe-and-woocommerce-integration') . get_woocommerce_currency_symbol() . $amount . '.<br>' . __(' Status : Failed ', 'payment-gateway-stripe-and-woocommerce-integration'));
						return new WP_Error('error', $data->message);
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

}