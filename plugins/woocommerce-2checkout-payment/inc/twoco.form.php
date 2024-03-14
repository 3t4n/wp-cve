<?php

if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

    use Omnipay\Omnipay;

    class WC_Gateway_NM_TwoCheckout extends WC_Payment_Gateway{

        // Logging
        public static $log_enabled = false;
        public static $log = false;

        public function __construct(){

            $plugin_dir = plugin_dir_url(__FILE__);

            global $woocommerce;

            $this->id   = 'nmwoo_2co';
            $icon_url   = ($this->get_option( 'image' ) != '' ? $this->get_option( 'image' ) : TWOCO_URL.'/images/2co_logo.png');
			$this->icon = $icon_url;
			$this->method_title 		= '2Checkout - Credit Card/PayPal';
			$this->method_description 	= '2Checkout Payment Gateway for WooCommerce. Setup your 2Checkout account with <a href="http://najeebmedia.com/2checkout-payment-gateway-for-woocommerce/" target="_blank">these setting</a>';
            $this->has_fields = true;
            

            // Load the settings
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title = $this->get_option('title');
            $this->seller_id = $this->get_option('seller_id');
            $this->checkout_url     	= 'https://www.2checkout.com/checkout/purchase';
            $this->secret_word			= trim( html_entity_decode($this->get_option('secret_word')));
            $this->secret_key			= trim( html_entity_decode($this->get_option('secret_key')));
            $this->description = $this->get_option('description');
            $this->demo 				= $this->get_option('demo');
            $this->debug = $this->get_option('debug');
            $this->disable_fraud_review = $this->get_option('disable_fraud_review');
            $this->checkout_mode = $this->get_option('checkout_mode');
            // $this->paypal_direct = $this->get_option('paypal_direct');
            $this->xchange_rate  = $this->get_option('xchange_rate');
            $this->curr_converter_api = $this->get_option('curr_converter_api');
            $this->towco_curr_version = $this->get_option('towco_curr_version');

            self::$log_enabled = $this->debug;
            
            $this->demo	= $this->demo == 'yes' ? true : false;
            
            // Sandbox is depreated by 2Checkout since June, 2020
            $this->sandbox	= false;
            
            $this->supports = array(
                'products',
            );

            // Saving options
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            
            // Payment listener/API hook
		    add_action( 'woocommerce_api_wc_gateway_nm_twocheckout', array( $this, 'sniff_ins' ), 10 );
		    add_action( 'woocommerce_api_twoco_return', array( $this, 'wc_checkout_return' ) );
		

        }
        
        function get_price($price){
		
    		$price = wc_format_decimal($price, 2);
    		
    		return apply_filters('nm_get_price', $price);
    	}

        /**
        * Logging method
        * @param  string $message
        */
        public static function log( $message ) {
            if ( self::$log_enabled ) {
                if ( empty( self::$log ) ) {
                    self::$log = new WC_Logger();
                }
                $message = is_array($message) ? json_encode($message) : $message;
                self::$log->add( 'twoco', $message );
            }
        }

        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         *
         * @since 1.0.0
         */
        public function admin_options() {

            ?>
            <h3><?php _e( '2Checkout by N-Media', 'twoco' ); ?></h3>
            <p><?php _e( 'Credit Card Payment Form on WooCommerce Checkout', 'twoco' ); ?></p>

            <table class="form-table">
                <?php
                // Generate the HTML For the settings form.
                $this->generate_settings_html();
                ?>
            </table><!--/.form-table-->
            <?php
        }
        
        
        /**
         * Initialise Gateway Settings Form Fields
         *
         * @access public
         * @return void
         */
        function init_form_fields() {
            
            $the_link 	= 'https://najeebmedia.com/';
	        $more_detail = '<a target="_blank" href="'.esc_attr($the_link).'">What is Mode?<a>';
            $paypal_direct_url = '<a target="_blank" href="'.esc_attr($the_link).'">About PayPal Direct?<a>';
            $api_key_url	 = 'https://free.currencyconverterapi.com/free-api-key';
            $get_apikey_url	= '<a target="_blank" href="'.esc_url($api_key_url).'">';
            $xchange_rate_desc = sprintf(__("If your currency is not supported by 2CO, then use this option. It will automatically convert your currency to %s using API",'twoco'),twoco_get_conversion_currency());
	        $xchange_api_desc  = sprintf(__("Get API Key to use Currency Converter. %s Get API Key.",'twoco'), $get_apikey_url);
	        
	        $xchange_rate 		= $this->get_option('xchange_rate');
        	$curr_converter_api	= trim( $this->get_option('curr_converter_api') );
        	
        	if( ! twoco_is_currency_supported() && $curr_converter_api != '') {
        		$xchange_rate = 'yes';
        	}

            $this->form_fields = array(
                'enabled' => array(
                    'title' => __( 'Enable/Disable', 'twoco' ),
                    'type' => 'checkbox',
                    'label' => __( 'Enable 2Checkout', 'twoco' ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __( 'Title', 'twoco' ),
                    'type' => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', 'twoco' ),
                    'default' => __( 'Credit Card/PayPal', 'twoco' ),
                    'desc_tip'      => true,
                ),
                'description' => array(
                    'title' => __( 'Description', 'twoco' ),
                    'type' => 'textarea',
                    'description' => __( 'This controls the description which the user sees during checkout.', 'twoco' ),
                    'default' => __( 'Pay with Credit Card/PayPal', 'twoco' )
                ),
                'image' => array(
						'title' => __( 'Image URL', 'twoco' ),
						'type' => 'text',
						'description' => __( 'Use your own image at checkout page by pasting image URL from Media Library. Left blank for default.', 'twoco' ),
						'default' => __( '', 'twoco' ),
						'desc_tip'      => true,
				),
                'seller_id' => array(
                    'title' => __( 'Merchant Code', 'twoco' ),
                    'type'          => 'text',
                    'description' => __( 'Please enter your 2Checkout Merchant Code (Seller ID).', 'twoco' ),
                    'default' => '',
                    'desc_tip'      => true,
                    'placeholder'   => ''
                ),
                'secret_word' => array(
						'title' => __( 'Secret Word', 'twoco' ),
						'type' => 'text',
						'description' => __( 'Secret Word must be same as given in 2Checkout Settings..', 'twoco' ),
						'default' => __( '', 'twoco' ),
						'desc_tip'      => true,
				),
				'disable_fraud_review' => array(
                    'title' => __('Disable Fraud Review', 'twoco'),
                    'label' => __('Check to Disable - PRO ONLY', 'twoco'),
                    'type' => 'checkbox',
                    'description' => __('Order status will be completed instantly if disabled.',
                        'twoco'),
                    'default' => 'yes',
                ),
                'checkout_mode' => array(
                    'title' => __('Checkout Mode', 'twoco'),
                    'type' => 'select',
                    'description' => __('Select mode of payment checkout.', 'twoco'),
                    'options' => array(
                        'standard' => __('Standard Checkout [off-site]', 'twoco'),
                        'credit_card' => __('Credit Card [On-site] - PRO ONLY', 'twoco'),
                        'convert_plus' => __('Convert Plus Inline - PRO ONLY', 'twoco'),
                    ),
                    'default' => 'standard',
                ),
                'demo' => array(
                    'title' => __( 'Demo Mode', 'twoco' ),
                    'type' => 'checkbox',
                    'label' => __( 'Enable Demo Mode (Not the Sandbox)', 'twoco' ),
                    'default' => 'no'
                ),
                'debug' => array(
                    'title'       => __( 'Debug Log', 'twoco' ),
                    'type'        => 'checkbox',
                    'label'       => __( 'Enable logging', 'twoco' ),
                    'default'     => 'no',
                    'description' => sprintf( __( 'Log 2Checkout events', 'twoco' ), wc_get_log_file_path( 'twoco' ) )
                ),
            );

        }
        
        
        public function is_fraud_status_active()
        {
            return $this->disable_fraud_review == 'no' ? true : false;
        }
        
       
        /**
         * Generate the credit card payment form
         *
         * @access public
         * @param none
         * @return string
         */
        
        public function payment_fields() {
            
        	if ( $this->description ) {
    			// you can instructions for test mode, I mean test card numbers etc.
    			if ( $this->demo ) {
    			    $test_card_url = 'https://knowledgecenter.2checkout.com/Documentation/09Test_ordering_system/01Test_payment_methods';
    				$this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="'.esc_url($test_card_url).'" target="_blank">documentation</a>.';
    				$this->description  = trim( $this->description );
    			}
    			// display the description with <p> tags etc.
    			echo wpautop( wp_kses_post( $this->description ) );
			}
			
		}
		
		/** Prcess Payment Method **/
		public function process_payment($order_id)
        {
            $order = new WC_Order($order_id);
            $this->returnUrl = WC()->api_request_url(__CLASS__);
    
          	$twoco_args = $this->get_twoco_args( $order );
			$twoco_args = http_build_query( $twoco_args, '', '&' );
			$this->log("========== Payment Procesing Started: args =========");
			$this->log($twoco_args);
			
			$checkout_url =	$this->checkout_url;
			
			// var_dump($checkout_url.'?'.$twoco_args); exit;
			
			return array(
					'result' 	=> 'success',
					'redirect'	=> $checkout_url.'?'.$twoco_args
			);
        }
        

        function get_twoco_args( $order ) {
			global $woocommerce;

			$order_id = $order->get_id();

			// 2Checkout Args
			$twoco_args = array(
					'sid' 					=> $this->seller_id,
					'mode' 					=> '2CO',
					'merchant_order_id'		=> $order_id,
					'currency_code'			=> get_woocommerce_currency(),
						
					// Billing Address info
					'first_name'			=> $order->get_billing_first_name(),
					'last_name'				=> $order->get_billing_last_name(),
					'street_address'		=> $order->get_billing_address_1(),
					'street_address2'		=> $order->get_billing_address_2(),
					'city'					=> $order->get_billing_city(),
					'state'					=> $order->get_billing_state(),
					'zip'					=> $order->get_billing_postcode(),
					'country'				=> $order->get_billing_country(),
					'email'					=> $order->get_billing_email(),
					'phone'					=> $order->get_billing_phone(),
			);

			// Shipping
			
			if ($order->needs_shipping_address()) {

				$twoco_args['ship_name']			= $order->get_shipping_first_name().' '.$order->get_shipping_last_name();
				$twoco_args['company']				= $order->get_shipping_company();
				$twoco_args['ship_street_address']	= $order->get_shipping_address_1();
				$twoco_args['ship_street_address2']	= $order->get_shipping_address_2();
				$twoco_args['ship_city']			= $order->get_shipping_city();
				$twoco_args['ship_state']			= $order->get_shipping_state();
				$twoco_args['ship_zip']				= $order->get_shipping_postcode();
				$twoco_args['ship_country']			= $order->get_shipping_country();
			}
			
			$twoco_args['x_receipt_link_url'] 	= WC()->api_request_url('twoco_return');
			$twoco_args['return_url']			= str_replace('https', 'http', $order->get_cancel_order_url());
			
			
			//if demo is enabled
			if ($this -> demo == 'yes'){
				$twoco_args['demo'] =	'Y';
			}

			$item_names = array();

			if ( sizeof( $order->get_items() ) > 0 ){
				
				$twoco_product_index = 0;
				
				foreach ( $order->get_items() as $item ){
					if ( $item['qty'] )
						$item_names[] = $item['name'] . ' x ' . $item['qty'];
				
					/*echo '<pre>';
					print_r($item);
					echo '</pre>';
					exit;*/
					
					
					/**
					 * since version 1.6
					 * adding support for both WC Versions
					 */
					$_sku = '';
					if ( function_exists( 'get_product' ) ) {
							
						// Version 2.0
						$product = $order->get_product_from_item($item);
							
						// Get SKU or product id
						if ( $product->get_sku() ) {
							$_sku = $product->get_sku();
						} else {
							$_sku = $product->get_id();
						}
							
					} else {
							
						// Version 1.6.6
						$product = new WC_Product( $item['id'] );
							
						// Get SKU or product id
						if ( $product->get_sku() ) {
							$_sku = $product->get_sku();
						} else {
							$_sku = $item['id'];
						}	
					}
					
					$tangible = "N";
					
					$item_formatted_name 	= $item['name'] . ' (Product SKU: '.$item['product_id'].')';
				
					$twoco_args['li_'.$twoco_product_index.'_type'] 	= 'product';
					$twoco_args['li_'.$twoco_product_index.'_name'] 	= sprintf( __( 'Order %s' , 'woocommerce'), $order->get_order_number() ) . " - " . $item_formatted_name;
					$twoco_args['li_'.$twoco_product_index.'_quantity'] = $item['qty'];
					$twoco_args['li_'.$twoco_product_index.'_price'] 	= $this -> get_price($order->get_item_total( $item, false ));
					$twoco_args['li_'.$twoco_product_index.'_product_id'] = $_sku;
					$twoco_args['li_'.$twoco_product_index.'_tangible'] = $tangible;
					
					$twoco_product_index++;
				}
				
				//getting extra fees since version 2.0+
				$extrafee = $order -> get_fees();
				if($extrafee){
				
					
					$fee_index = 1;
					foreach ( $order -> get_fees() as $item ) {
						
						$twoco_args['li_'.$twoco_product_index.'_type'] 	= 'product';
						$twoco_args['li_'.$twoco_product_index.'_name'] 	= sprintf( __( 'Other Fee %s' , 'woocommerce'), $item['name'] );
						$twoco_args['li_'.$twoco_product_index.'_quantity'] = 1;
						$twoco_args['li_'.$twoco_product_index.'_price'] 	= $this->get_price( $item['line_total'] );

						$fee_index++;
						$twoco_product_index++;
	 				}	
				}
				
				// Shipping Cost
				if ( $order -> get_total_shipping() > 0 ) {
					
					
					$twoco_args['li_'.$twoco_product_index.'_type'] 		= 'shipping';
					$twoco_args['li_'.$twoco_product_index.'_name'] 		= __( 'Shipping charges', 'woocommerce' );
					$twoco_args['li_'.$twoco_product_index.'_quantity'] 	= 1;
					$twoco_args['li_'.$twoco_product_index.'_price'] 		= $this->get_price( $order -> get_total_shipping() );
					$twoco_args['li_'.$twoco_product_index.'_tangible'] = 'Y';
					
					$twoco_product_index++;
				}
				
				// Taxes (shipping tax too)
				if ( $order -> get_total_tax() > 0 ) {
				
					$twoco_args['li_'.$twoco_product_index.'_type'] 		= 'tax';
					$twoco_args['li_'.$twoco_product_index.'_name'] 		= __( 'Tax', 'woocommerce' );
					$twoco_args['li_'.$twoco_product_index.'_quantity'] 	= 1;
					$twoco_args['li_'.$twoco_product_index.'_price'] 		= $this->get_price( $order->get_total_tax() );
					
					$twoco_product_index++;
				}

				
			}

			
			
			$twoco_args = apply_filters( 'woocommerce_twoco_args', $twoco_args );
			
			return $twoco_args;
		}


    /**
	 * Check for 2Checkout IPN Response
	 *
	 * @access public
	 * @return void
	 */
	public function wc_checkout_return() {
	    
	    $this->log(" === Header Redirect ===");
		$this->log($_REQUEST);
		
		if (isset($_REQUEST['invoice_id']) && !empty($_REQUEST['invoice_id'])) {
            
            $wc_order_id = isset($_GET['merchant_order_id']) ? sanitize_text_field($_GET['merchant_order_id']) : '';
            if (class_exists('WC_Seq_Order_Number_Pro')) {
                $order_id = WC_Seq_Order_Number_Pro()->find_order_by_order_number($wc_order_id);
                $wc_order = new WC_Order($order_id);
            } else {
                $wc_order = new WC_Order($wc_order_id);
            }
            
            global $woocommerce;
		
    		$this->log("Order Found ==> {$wc_order_id}");
    		
    		$twoco_order_no	= sanitize_text_field($_REQUEST['order_number']);
    		$order_total	= $wc_order->get_total();
    		
    		if ( isset($_REQUEST['demo']) && $_REQUEST['demo'] == 'Y' ){
    			$compare_string = $this->secret_word . $this->seller_id . "1" . $order_total;
    		}else{
    			$compare_string = $this->secret_word . $this->seller_id . $twoco_order_no . $order_total;
    		}
    		$compare_hash1 = strtoupper(md5($compare_string));
    		
    		$this->log("Hash generated ==> {$compare_hash1}");
    		
    		$compare_hash2 = sanitize_text_field($_REQUEST['key']);
    		if ( ! $this->is_fraud_status_active() && $compare_hash1 == $compare_hash2 ) {
    			$wc_order->add_order_note( sprintf(__('Payment completed via 2Checkout Order Number %d', 'twoco'), $twoco_order_no) );
    			// Mark order complete
    			$wc_order->payment_complete();
    			// Empty cart and clear session
    			$woocommerce->cart->empty_cart();
    			$order_redirect = add_query_arg('twoco','processed', $this->get_return_url( $wc_order ));
    			$this->log( __("Payment Completed via Standard Checkout ==> {$wc_order_id}", 'twoco') );
    			wp_redirect( $order_redirect );
    			exit;
		    } else {
		        $wc_order->update_status('on-hold', __('Waiting for payment status.', 'twoco'));
		        $order_redirect = $this->get_return_url( $wc_order );
		        wp_redirect( $order_redirect );
    			exit;
		    }
            
        }
	}
	
	function sniff_ins() {
	
		// Old URL support
		if( isset($_GET['merchant_order_id']) ) $this->wc_checkout_return();
		
	    if( ! $this->is_fraud_status_active() ) exit;
		/**
		 * source code: https://github.com/craigchristenson/woocommerce-2checkout-api
		 * Thanks to: https://github.com/craigchristenson
		 */
		global $woocommerce;
		
		// twoco_pa($_REQUEST);
		
		$this->log(__("== INS Response Received == ", "twoco") );
		$this->log( $_REQUEST );
		
		$wc_order_id = '';
		
		if( !isset($_REQUEST['vendor_order_id']) ) {
			$this->log( '===== NO ORDER NUMBER FOUND =====' );
			exit;
		} 
		
		$wc_order_id = sanitize_text_field($_REQUEST['vendor_order_id']);
		$this->log(" ==== ORDER -> {$wc_order_id} ====");
		
		
		// echo $wc_order_id;
		$wc_order_id = apply_filters('twoco_order_no_received', $wc_order_id, $_REQUEST);
		$this->log( "Order Received ==> {$wc_order_id}" );
		// exit;
		
		
		$wc_order 		= new WC_Order( absint( $wc_order_id ) );
		
		
		$message_type	= isset($_REQUEST['message_type']) ? sanitize_text_field($_REQUEST['message_type']) : '';
		$sale_id		= isset($_REQUEST['sale_id']) ? sanitize_text_field($_REQUEST['sale_id']) : '';
		$invoice_id		= isset($_REQUEST['invoice_id']) ? sanitize_text_field($_REQUEST['invoice_id']) : '';
		$fraud_status	= isset($_REQUEST['fraud_status']) ? sanitize_text_field($_REQUEST['fraud_status']) : '';
		
		$this->log( "Message Type/Fraud Status: {$message_type}/{$fraud_status}" );
		
		switch( $message_type ) {
			
			case 'ORDER_CREATED':
				$wc_order->add_order_note( sprintf(__('ORDER_CREATED with Sale ID: %d', 'twoco'), $sale_id) );
				$this->log(sprintf(__('ORDER_CREATED with Sale ID: %d', 'twoco'), $sale_id));
				
			break;
			
			case 'FRAUD_STATUS_CHANGED':
				if( $fraud_status == 'pass' ) {
					// Mark order complete
					$wc_order->payment_complete();
					$wc_order->add_order_note( sprintf(__('Payment Status Pass with Invoice ID: %d', 'twoco'), $invoice_id) );
					$this->log(sprintf(__('Payment Status Pass with Invoice ID: %d', 'twoco'), $invoice_id));
					add_action('twoco_order_completed', $wc_order, $sale_id, $invoice_id);
					
				} elseif( $fraud_status == 'fail' ) {
					
					$wc_order->update_status('failed');
					$wc_order->add_order_note(  __("Payment Decliented", 'twoco') );
					$this->log( __("Payment Decliented", 'twoco') );
				}
				
			break;
		}
		
		exit;
	}
	
}