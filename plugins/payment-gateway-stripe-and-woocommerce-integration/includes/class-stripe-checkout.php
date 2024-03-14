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
class Eh_Stripe_Checkout extends WC_Payment_Gateway {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		$this->id                 = 'eh_stripe_checkout';
		$this->method_title       = __( 'Stripe Checkout', 'payment-gateway-stripe-and-woocommerce-integration' );
		$this->method_description = sprintf( __( 'Pay with Stripe Checkout', 'payment-gateway-stripe-and-woocommerce-integration' ) );
		$this->supports           = array(
			'products',
			'refunds',
		);

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();
        
        $this->eh_stripe_option        = get_option("woocommerce_eh_stripe_pay_settings");
		$this->title                   = __($this->get_option( 'eh_stripe_checkout_title' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->description             = __($this->get_option( 'eh_stripe_checkout_description' ), 'payment-gateway-stripe-and-woocommerce-integration' );
        $this->method_description      = __( '<p style="max-width: 97%;"> Stripe Checkout redirects users to a secure, Stripe-hosted payment page to accept payment. You will have to specify an account name in Stripe <a href="https://dashboard.stripe.com/account" target="_blank">Dashboard</a> prior to configuring the settings. <a class="thickbox" href="'.EH_STRIPE_MAIN_URL_PATH . 'assets/img/stripe_checkout_line_items.gif?TB_iframe=true&width=100&height=100" >Preview</a></p><p><a target="_blank" href="https://www.webtoffee.com/woocommerce-stripe-payment-gateway-plugin-user-guide/#stripe_checkout"> Read documentation </a>  </p>', 'payment-gateway-stripe-and-woocommerce-integration' );
        
        $this->enabled                 = $this->get_option( 'enabled' );
        $this->eh_order_button         = $this->get_option( 'eh_stripe_checkout_order_button');
        $this->order_button_text       = __($this->eh_order_button, 'payment-gateway-stripe-and-woocommerce-integration');
        $this->stripe_checkout_page_locale = $this->get_option( 'eh_stripe_checkout_page_locale');
        $this->stripe_checkout_send_line_items = $this->get_option( 'eh_send_line_items');
        $this->collect_billing = $this->get_option( 'eh_collect_billing');
        $this->collect_shipping = $this->get_option( 'eh_collect_shipping');

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

        add_action( 'wc_ajax_eh_spg_stripe_checkout_order', array( $this, 'eh_spg_stripe_checkout_order_callback' ) );
        add_action( 'wc_ajax_eh_spg_stripe_cancel_order', array( $this, 'eh_spg_stripe_cancel_order' ) );
        add_action( 'set_logged_in_cookie', array( $this, 'eh_set_cookie_on_current_request' ) );
 
        // Set stripe API key.
        \Stripe\Stripe::setApiKey(EH_Stripe_Payment::get_stripe_api_key());
        \Stripe\Stripe::setAppInfo( 'WordPress payment-gateway-stripe-and-woocommerce-integration', EH_STRIPE_VERSION, 'https://wordpress.org/plugins/payment-gateway-stripe-and-woocommerce-integration/', 'pp_partner_KHip9dhhenLx0S' );
        
	}
    
	/**
	 * Initialize form fields in stripe checkout payment settings page.
     * @since 3.3.4
	 */
	public function init_form_fields() {


		$this->form_fields = array(

            'eh_stripe_checkout_form_title' => array(
                'type' => 'title',
                'class'=> 'eh-css-class',
            ),

			'enabled'      => array(
				'title'       => __('Stripe Checkout','payment-gateway-stripe-and-woocommerce-integration'),
				'label'       => __('Enable','payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'checkbox',
                'default'     => 'no',
                'desc_tip'    => __('Enable to accept Stripe Checkout payments.','payment-gateway-stripe-and-woocommerce-integration')
			),
			'eh_stripe_checkout_title'         => array(
				'title'       => __('Title','payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'text',
				'description' =>  __('Input title for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
				'default'     => __('Stripe Checkout', 'payment-gateway-stripe-and-woocommerce-integration'),
				'desc_tip'    => true,
			),
			'eh_stripe_checkout_description'     => array(
				'title'       => __('Description','payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'textarea',
				'css'         => 'width:25em',
				'description' => __('Input texts for the payment gateway displayed at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
			 	'default'     => __('Secure payment via Stripe Checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
			 	'desc_tip'    => true
			),

			'eh_stripe_checkout_order_button'    => array(
				'title'       => __('Order button text', 'payment-gateway-stripe-and-woocommerce-integration'),
				'type'        => 'text',
				'description' => __('Input a text that will appear on the order button to place order at the checkout.', 'payment-gateway-stripe-and-woocommerce-integration'),
				'default'     => __('Pay via Stripe Checkout', 'payment-gateway-stripe-and-woocommerce-integration'),
				'desc_tip'    => true
            ),
            'eh_stripe_checkout_page_locale' => array(
                'title'      => __( 'Locale', 'payment-gateway-stripe-and-woocommerce-integration' ),
                'type'       => 'select',
                'class'      => 'wc-enhanced-select',
                'options'    => array(
                    
                    'auto' => __('Browser\'s locale', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'bg'  => __('Bulgarian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'hr'  => __('Croatian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'cs'  => __('Czech', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'da'  => __('Danish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'nl'  => __('Dutch', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'en'  => __('English', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'et'  => __('Estonian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'fi'  => __('Finnish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'fil'  => __('Filipino', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'fr'  => __('French', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'de'  => __('German', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'el'  => __('Greek', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'hu'  => __('Hungarian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'id'  => __('Indonesian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'it'  => __('Italian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'ja'  => __('Japanese', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'ko'  => __('Korean', 'payment-gateway-stripe-and-woocommerce-integration'),
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
                    'sk'  => __('Slovak', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'sl'  => __('Slovenian', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'es'  => __('Spanish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'sv'  => __('Swedish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'th'  => __('Thai', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'tr'  => __('Turkish', 'payment-gateway-stripe-and-woocommerce-integration'),
                    'vi'  => __('Vietnamese', 'payment-gateway-stripe-and-woocommerce-integration'),
                ),
                'description' => sprintf(__('Choose a desired locale code from the drop down (Languages supported by Stripe Checkout are listed)', 'payment-gateway-stripe-and-woocommerce-integration')),
                'default'     => 'auto',
                'desc_tip'    => true,
            ),
            'eh_send_line_items'      => array(
                'title'       => __('Send line items to stripe','payment-gateway-stripe-and-woocommerce-integration'),
                'label'       => __('Enable to view all order line items like tax, shipping, fees, discount, etc.  at the stripe hosted payment page.','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'checkbox',
                'default'     => 'no',
            ), 

            'eh_collect_billing'      => array(
               'title'       => __('Ask for address details from the stripe hosted page','payment-gateway-stripe-and-woocommerce-integration'),
                'label'       => __('Billing address','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'checkbox',
                'default'     => 'no',
            ),  

            'eh_collect_shipping'      => array(
                'label'       => __('Shipping address','payment-gateway-stripe-and-woocommerce-integration'),
                'type'        => 'checkbox',
                'default'     => 'no',
            ),
            'eh_checkout_webhook_desc' => array(
                'type' => 'title',
                'description' => sprintf(__('%1$sTo accept payments via delayed payment methods from Stripe hosted page, you must configure the webhook endpoint and subscribe to relevant events. %2$sClick here%3$s to know more%4$s', 'payment-gateway-stripe-and-woocommerce-integration'), '<div class="wt_info_div"><p>', '<a target="_blank" href="https://www.webtoffee.com/setting-up-webhooks-and-supported-webhooks/">', '</a>', '</p></div>'),
            ),              
		);   
    }
    
	/**
     * Checks if gateway should be available to use.
     * @since 3.3.4
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
	 * Payment form on checkout page
     * @since 3.3.4
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
     * @since 3.3.4
     */
    public function payment_scripts(){
        
    }

    /**
	 * Proceed with current request using new login session (to ensure consistent nonce).
	 */
	public function eh_set_cookie_on_current_request( $cookie ) {
		$_COOKIE[ LOGGED_IN_COOKIE ] = $cookie;
	}

    /**
	 * Process the payment
	 *
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
        $currency =  $order->get_currency();
        
        $total = EH_Stripe_Payment::get_stripe_amount( $order->get_total(), $currency);
        
        $email = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
        
        $images    = array();
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            
            $quantity_label =  0 < $cart_item['quantity'] ?  $cart_item['quantity']  : '';
            $_product       =  wc_get_product( $cart_item['data']->get_id()); 

            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($_product->get_id()));
            if(isset($featured_image[0])){
                $images[] = sanitize_url($featured_image[0]);
            }
           
        }

        $capture_method = 'automatic';
        if(isset($this->eh_stripe_option['eh_stripe_capture'])){

            if ('no' == $this->eh_stripe_option['eh_stripe_capture']) {
                $capture_method = 'manual';
            }
        }

        $session_data = array(

            'payment_method_types' => ['card'],
            'mode' => 'payment',
            
            'payment_intent_data' => [
                'description' => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' Order #' . $order->get_order_number(),
                'capture_method' => $capture_method,
            ],
            'success_url'=> wp_sanitize_redirect(home_url()).'/?wc-ajax=eh_spg_stripe_checkout_order'.'&sessionid={CHECKOUT_SESSION_ID}'.'&order_id='.$order_id.'&_wpnonce='.wp_create_nonce('eh_checkout_nonce'),
            'cancel_url' => wp_sanitize_redirect(home_url()).'/?wc-ajax=eh_spg_stripe_cancel_order'.'&_wpnonce='.wp_create_nonce('eh_checkout_nonce').'&order_id='. base64_encode($order_id),
            'metadata' => ['order_id' => $order_id],
            'expires_at' => time() + (3600 * 1),

        );

        if ('yes' === $this->collect_billing) {
            $session_data['billing_address_collection'] = 'required';
        }

        if ('yes' === $this->collect_shipping) {
            $country = ((WC()->version < '2.7.0') ? $order->shipping_country : $order->get_shipping_country());
            if(empty($country)){
                $country = ((WC()->version < '2.7.0') ? $order->billing_country : $order->get_billing_country());
            }
            if (!empty($country)) {
                $session_data['shipping_address_collection']['allowed_countries']   = [$country];
            }            
            
        }

        //only display total amount as default 
        $display_line_items = $this->stripe_checkout_send_line_items;
        if('yes' === $display_line_items){ 

            $index = 0;
            foreach ($order->get_items() as $item_id => $item) { 
               $prod = wc_get_product($item->get_product_id());
               if (WC()->cart->display_cart_ex_tax) {
                    $item_amount = $item->get_subtotal();
                }
                else{
                    $item_amount = $item->get_subtotal() + $item->get_subtotal_tax();
                }

                 $session_data['line_items'][] = array(
                        'quantity' => $item->get_quantity(),
                        'price_data' => array(
                            'currency' => strtolower($currency),
                            'unit_amount' => EH_Stripe_Payment::get_stripe_amount( round( $item_amount / $item->get_quantity(), 2), $currency ),
                            'product_data' => array(
                                'name' => esc_html( __($item->get_name(), 'payment-gateway-stripe-and-woocommerce-integration' ) ),
                            ),
                        ),
                       
                        
                    ); 

                $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($prod->get_id()));
                if(isset($featured_image[0])){
                    $session_data['line_items'][$index]['price_data']['product_data']['images'][] =  array(sanitize_url($featured_image[0]));        
                }
                $index++;
            }

            if ( 0 < $order->get_shipping_total() ) {
                $shipping = $order->get_shipping_total();
                if(!WC()->cart->display_cart_ex_tax){
                    $shipping += $order->get_shipping_tax();
                }  

                $session_data['shipping_options'][] = array(
                    'shipping_rate_data' => array(
                        'display_name'        => apply_filters('wt_stripe_alter_item_title',__('Shipping', 'payment-gateway-stripe-and-woocommerce-integration')),
                        'fixed_amount'    => array(
                            'amount'      => EH_Stripe_Payment::get_stripe_amount( $shipping, $currency ),
                            'currency'    => $currency,
                        ),
                        'type' => 'fixed_amount',
                    )
                );
            } 

            // discount
            $discount = 0;
            $discount = apply_filters('wt_stripe_checkout_discount_amount', $discount, $order);            
            if ($discount || 0 < $order->get_discount_total() ) { 
                if(WC()->cart->display_cart_ex_tax){
                    $discount += $order->get_discount_total();
                }
                else{
                    $discount += $order->get_discount_total() + $order->get_discount_tax();
                }
                //create coupons
               $coupons = \Stripe\Coupon::create([
                    'amount_off' => EH_Stripe_Payment::get_stripe_amount( $discount, $currency ),
                    'currency'    => $currency,
                    'name'        => apply_filters('wt_stripe_alter_item_title',__( 'Discount', 'payment-gateway-stripe-and-woocommerce-integration' )),
                ]); 
               if(!empty($coupons) && isset($coupons->id)){
                   $session_data['discounts'][] = array(
                    
                        'coupon'        => $coupons->id,
                       
                    );

               }

            } 
            // fees
            if ( $order->get_fees() ) { 
                $fee_total = 0;
                foreach ( $order->get_fees() as $fee ) {
                    $fee_total +=  $fee->get_total();

                    if (!WC()->cart->display_cart_ex_tax) {
                        $fee_total +=  $fee->get_total_tax();
                    }
                }

                //compatibility with Conditional Discounts for WooCommerce by ORION, the plugin add discount as order fee
                if(defined("WAD_DIR")){
                    if (file_exists(WAD_DIR . '/wad.php')) {

                        //create coupons
                       $discounts = \Stripe\Coupon::create([
                            'amount_off' => EH_Stripe_Payment::get_stripe_amount( abs($fee_total), $currency ),
                            'currency'    => $currency,
                            'name'        => apply_filters('wt_stripe_alter_item_title',__( 'Discount', 'payment-gateway-stripe-and-woocommerce-integration' )),
                        ]); 
                       if(!empty($discounts) && isset($discounts->id)){
                           $session_data['discounts'][] = array(
                            
                                'coupon'        => $discounts->id,
                               
                            );

                       }

                    }
                    else{ 
                        $session_data['line_items'][] = array(
                            'quantity' => 1,
                            'price_data' => array(
                                'currency' => strtolower($currency),
                                'unit_amount' => EH_Stripe_Payment::get_stripe_amount($fee_total, $currency ),
                                'product_data' => array(
                                    'name' => apply_filters('wt_stripe_alter_item_title',__( 'Fee', 'payment-gateway-stripe-and-woocommerce-integration' )),
                                ),
                            ),
                           
                            
                        );                           
                    }
                }
                else{ 
                    $session_data['line_items'][] = array(
                        'quantity' => 1,
                        'price_data' => array(
                            'currency' => strtolower($currency),
                            'unit_amount' => EH_Stripe_Payment::get_stripe_amount($fee_total, $currency ),
                            'product_data' => array(
                                'name' => apply_filters('wt_stripe_alter_item_title',__( 'Fee', 'payment-gateway-stripe-and-woocommerce-integration' )),
                            ),
                        ),
                       
                        
                    );                               
               }

            }

            if(WC()->cart->display_cart_ex_tax){

                // tax
                if ( 0 < $order->get_total_tax() ) {
                    $session_data['line_items'][] = array(
                        'quantity' => 1,
                        'price_data' => array(
                            'currency' => strtolower($currency),
                            'unit_amount' => EH_Stripe_Payment::get_stripe_amount( $order->get_total_tax() ),
                            'product_data' => array(
                                'name' => apply_filters('wt_stripe_alter_item_title',__( 'Tax', 'payment-gateway-stripe-and-woocommerce-integration' )),
                            ),
                        ),
                       
                        
                    );                     
                } 
            }   

        }
        else{
            //if there is image for the product add image to the line items 
            $count = 0;

            foreach ($images as $key => $value) {
                
                if( !empty($value)) {

                   $count = 1;
                   break;
                }
            }

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

            if( $count == 1 ) { 
                $session_data['line_items'][0]['price_data']['product_data']['images'][] =  array($images);
                   
            }      
        }


       //check customer token is exist for the logged in user
        $user = wp_get_current_user();
        $logged_in_userid = $user->ID;
        $customer_id = get_user_meta($logged_in_userid, '_stripe_ch_customer_id', true);

        //create stripe customer 
        if (empty($customer_id)) { 

            $customer = $this->create_stripe_customer( $order, $user);

            $customer_id = $customer->id;
            //saved stripe customer for charging  cards later
            update_user_meta($logged_in_userid, "_stripe_ch_customer_id", $customer_id);
        }
        $session_data['customer'] = $customer_id;
        
        //$session_data['payment_intent_data']['setup_future_usage'] = 'off_session';

        $session_data['locale'] = $this->stripe_checkout_page_locale;

        if(apply_filters("wt_ch_collect_vat", false)){
            $session_data['tax_id_collection'] = ['enabled' => true];
            $session_data['customer_update'] = ['name' => 'auto', 'address' => 'auto', 'shipping' => 'auto'];
            
        }


        $session_data = apply_filters('wt_stripe_alter_checkout_request_params', $session_data, $order);
        $session = \Stripe\Checkout\Session::create($session_data);
        
        if(isset($session->url) && !empty($session->url)){
            return array(
                'result'        => 'success',
                'redirect'      => $session->url,
            );
        }
        else{
            return  array(
                'result'   => 'failure',
                
            );
        }
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

    public function create_stripe_customer($order, $user_obj) {
        if(!empty($order)){
            $order_no = $order->get_order_number();
            $params = array(
                'description' => "Customer for Order #" . $order_no,
                "email" => ((WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email()),
                "address" => array(
                    'city' => method_exists($order, 'get_billing_city') ? $order->get_billing_city() : $order->billing_city,
                    'country' => method_exists($order, 'get_billing_country') ? $order->get_billing_country() : $order->billing_country,
                    'line1' => method_exists($order, 'get_billing_address_1') ? $order->get_billing_address_1() : $order->billing_address_1,
                    'line2' => method_exists($order, 'get_billing_address_2') ? $order->get_billing_address_2() : $order->billing_address_2,
                    'postal_code' => method_exists($order, 'get_billing_postcode') ? $order->get_billing_postcode() : $order->billing_postcode,
                    'state' => method_exists($order, 'get_billing_state') ? $order->get_billing_state() : $order->billing_state,
                ),
                'name' => (method_exists($order, 'get_billing_first_name') ? $order->get_billing_first_name() : $order->billing_first_name) . " " . (method_exists($order, 'get_billing_last_name')  ? $order->get_billing_last_name()  : $order->billing_last_name),
            );
        
        }
        else{
            $fname = (isset($user_obj->user_firstname) ?  $user_obj->user_firstname : '');
            $lname = (isset($user_obj->user_lastname) ?  $user_obj->user_lastname : '');
            $params = array(
                'description' => 'Added manually',
                'name' => $fname . " " . $lname,
                "email" => $user_obj->user_email,
            );
        
        }

        $response = \Stripe\Customer::create($params);

        if (empty($response->id)) {
            return false;
        }

        return $response;
    }

	/**
     * creates order after checkout session is completed.
     * @since 3.3.4
     */
    public function eh_spg_stripe_checkout_order_callback() {
        
        if(!EH_Helper_Class::verify_nonce(EH_STRIPE_PLUGIN_NAME, 'eh_checkout_nonce'))
        {
            die(_e('Access Denied', 'payment-gateway-stripe-and-woocommerce-integration'));
        }
      
        $session_id = sanitize_text_field( $_GET['sessionid'] );
        $order_id = intval( $_GET['order_id'] );
        $order = wc_get_order($order_id);

        $obj  = new EH_Stripe_Payment();
            
        $order_time = date('Y-m-d H:i:s', time() + get_option('gmt_offset') * 3600);
        
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $payment_intent_id = $session->payment_intent;

         EH_Helper_Class::wt_stripe_order_db_operations($order_id,  $order, 'add', '_eh_stripe_payment_intent', $payment_intent_id, false);  

        $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        $charge_details = $payment_intent->charges['data'];
    
        foreach($charge_details as $charge){

            $charge_response = $charge;  
        }

        $data = $obj->make_charge_params($charge_response, $order_id);
        
        if ($charge_response->paid == true) {

            if($charge_response->captured == true){
                $order->payment_complete($data['id']);
            }

            if (!$charge_response->captured) {
                $order->update_status('on-hold');
            }

            $order->set_transaction_id( $data['transaction_id'] );

            $order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($data['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['source_type'] . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $data['captured'] . (is_null($data['transaction_id']) ? '' : '.'.__('Transaction ID : ','payment-gateway-stripe-and-woocommerce-integration') . $data['transaction_id']));
            WC()->cart->empty_cart();
            
            EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'add', '_eh_stripe_payment_charge', $data, false); 
            EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
            
            // Return thank you page redirect.
            $result =  array(
                'result'    => 'success',
                'redirect'  => $obj->get_return_url($order),
            );
        
            wp_safe_redirect($result['redirect']);
            exit;
            
        } else {
            wc_add_notice($data['status'], $notice_type = 'error');
            EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
        }
       
    }


    public function eh_spg_stripe_cancel_order(){
        
        if(!EH_Helper_Class::verify_nonce(EH_STRIPE_PLUGIN_NAME, 'eh_checkout_nonce'))
        {
            die(_e('Access Denied', 'payment-gateway-stripe-and-woocommerce-integration'));
        }
        
        wc_add_notice(__('You have cancelled Stripe Checkout Session. Please try to process your order again.', 'payment-gateway-stripe-and-woocommerce-integration'), 'notice');
        wp_redirect(wc_get_checkout_url());
        exit;
    }

    /**
     * process order refund
     * @since 3.3.4
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

    //process payment intent received via webhook
    public function wt_process_payment_intent($order_id, $order, $payment_intent_id)
    {
        //check stripe vendor folder is exist
        if (!class_exists('Stripe\Stripe')) {
            include(EH_STRIPE_MAIN_PATH . "vendor/autoload.php");
        }

         $payment_intent = \Stripe\PaymentIntent::retrieve(array('id' => $payment_intent_id, 'expand' => array('charges.data.balance_transaction') ) );

         EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'update', '_eh_stripe_payment_intent', $payment_intent_id, false);


        if (isset($payment_intent->charges['data']) && is_array($payment_intent->charges['data']) && !empty($payment_intent->charges['data'])) {
            $charge_details = $payment_intent->charges['data'];
            if(is_array( $charge_details) && count( $charge_details) > 0){
                $obj = new EH_Stripe_Payment();
                foreach($charge_details as $charge){

                    $charge_response = $charge; 

                    $data = $obj->make_charge_params($charge_response, $order_id);
                    if (isset($data['transaction_id']->id)) {
                        $transaction_id = $data['transaction_id']->id;
                    }
                    else{
                         $transaction_id = $data['transaction_id'];
                    } 

                    $order->set_transaction_id( $transaction_id );

                }                    
            }

        }

        if('succeeded' === $payment_intent->status){
            
            /*true if the charge succeeded, or was successfully authorized for later capture.*/
            if (true === $charge_response->paid) {

                if(true === $charge_response->captured){
                    $order->payment_complete($data['id']);
                }

                if (!$charge_response->captured) {
                    $order->update_status('on-hold');
                }


                $order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($data['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['source_type'] . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $data['captured'] . (is_null($transaction_id) ? '' : '. <br>'.__('Transaction ID : ','payment-gateway-stripe-and-woocommerce-integration') . $transaction_id));
                WC()->cart->empty_cart();
                
                EH_Helper_Class::wt_stripe_order_db_operations($order_id, $order, 'update', '_eh_stripe_payment_charge', $data, false);
                EH_Stripe_Log::log_update('live', $data, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
                                
            } 
            else {
                $order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($data['status']) . ' [ ' . $order_time . ' ] . ' . __('Source : ', 'payment-gateway-stripe-and-woocommerce-integration') . $data['source_type'] . '. ' . __('Charge Status :', 'payment-gateway-stripe-and-woocommerce-integration') . $data['captured'] . (is_null($transaction_id) ? '' : '. <br>'.__('Transaction ID : ','payment-gateway-stripe-and-woocommerce-integration') . $transaction_id));

               
                EH_Stripe_Log::log_update('dead', $charge_response, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
            }
        }
        elseif ('processing' === $payment_intent->status || 'requires_capture' === $payment_intent->status) {
            $order->update_status('on-hold');
            $order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($payment_intent->status) . ' [ ' . $order_time . ' ] . ');
            EH_Stripe_Log::log_update('dead', $payment_intent, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
        }
        else{
            
            $order->update_status('failed');
            $order->add_order_note(__('Payment Status : ', 'payment-gateway-stripe-and-woocommerce-integration') . ucfirst($payment_intent->status) . ' [ ' . $order_time . ' ] . ');            
            EH_Stripe_Log::log_update('dead', $payment_intent, get_bloginfo('blogname') . ' - Charge - Order #' . $order_id);
        }

    }
}