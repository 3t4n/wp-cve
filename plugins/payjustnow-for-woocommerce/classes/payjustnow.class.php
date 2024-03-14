<?php
/*
* @class       woocommerce_gateway_payjustnow
* @package     WooCommerce
* @category    Payment Gateways
* @author      PayJustNow
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_Gateway_PayJustNow extends WC_Payment_Gateway
{

// Error logger
public function log( $message )
{
	if ( !property_exists( $this, 'logger' ) && !$this->logger ) {
	$this->logger = new WC_Logger();
	}
	$this->logger->add( 'payjustnow', $message );
}

// Setup the plugin
public function __construct()
	{
		
	global $woocommerce;
	
	$this->id = 'payjustnow';
	$this->method_description = sprintf( __( 'This sends the user to %sPayJustNow%s to enter their payment information.', 'woocommerce_gateway_payjustnow' ), '<a href="https://payjustnow.com/">', '</a>' );
	$this->has_fields = true;
	$this->method_title = "PayJustNow";
	
	// Now supports refunds
	$this->supports = array(
	  'products',
	  'refunds'
	);
	
 	$this->wc_version = get_option( 'woocommerce_db_version' );
	
	// Load the form fields
	$this->init_form_fields();
	
	// Load the settings
	$this->init_settings();
	
	// Get setting values
	$this->title = $this->settings['title'];
	$this->description = $this->settings['description'];
	
	if( isset( $this->settings['usedarktheme'] ) ){
		$payjustnow_dark_theme = $this->settings['usedarktheme'];
		if ( $payjustnow_dark_theme == 'yes' ) { 
			$payjustnow_logo = 'payjustnow_logo_dark_theme.png';
		} else {
			$payjustnow_logo = 'payjustnow_logo_light_theme.png';
		}
	} else {
			$payjustnow_logo = 'payjustnow_logo_light_theme.png';
	}
	
	$this->icon = apply_filters( 'woocommerce_payjustnow_icon', $this->plugin_url() . '/assets/images/'.$payjustnow_logo );
	
	// Hooks
	if ( $this->enabled == 'yes' ) {
		add_action( 'woocommerce_api_wc_gateway_payjustnow', array( $this, 'callback_handler' ) );
	}
	
	// Admin options
	if ( is_admin() ) {
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

}

// Handles the success/failure response from PayJustNow
public function callback_handler() 
	{
		
	$check_pjn_token = 'error';
	$check_pjn_url = 'error';

	// Error logging...
	if ( $this->settings['enablelogs'] <> 'no' ) {
		 $this->log('================ Callback received from PJN:');
		 $this->log('order_key: '.sanitize_text_field($_GET['order_key']) );
		 $this->log('pjn_key: '.sanitize_text_field($_GET['pjn_key']) );
		 $this->log('status: '.sanitize_text_field($_GET['status']) );
		 $this->log('================');
	}

	if ( !empty( $_GET ) && isset( $_GET['order_key'] ) && isset( $_GET['pjn_key'] ) ) {
		
		if (isset( $_GET['status'] )) {
			$pjn_status=sanitize_text_field($_GET['status']);
		} else {
			$pjn_status='UNKNOWN';
		}
		
		global $woocommerce;
		
		$order_key = sanitize_text_field($_GET['order_key']);
		$pjn_key = sanitize_text_field($_GET['pjn_key']);
		
		// Get order number with order_key		
		$order_id = wc_get_order_id_by_order_key($order_key); //returns 0 if order not found

		if ( $order_id == '0' ) {
			$error_message = 'PAYJUSTNOW ERROR: Order not found.';
			$this->log( $error_message );
			// Not a good url, early exit
			exit; 
		}
		
		$order = wc_get_order( $order_id );

		//if ( !empty($order) && $order->order_key==$order_key ) {
		if ( !empty($order) ) {
			
			// Order exists, retrieving the pjn_key
			$check_pjn_key = get_post_meta( $order_id, 'pjn_key', true );
			$check_pjn_token = get_post_meta( $order_id, 'pjn_token', true );
			
			$currentstatus = $order->get_status();
			
			if ( $check_pjn_key==$pjn_key && $currentstatus<>'processing' && $currentstatus<>'completed' ) {
				// Transaction successful
				$order->update_status('processing', __('Payment Successful.', 'woocommerce_gateway_payjustnow'));
				$order->payment_complete($check_pjn_token);
				wc_reduce_stock_levels($order_id); 
				$order->add_order_note('PayJustNow transaction status: '.$pjn_status);
				$woocommerce->cart->empty_cart();
				// Send success url - order received page
				$check_pjn_url = $order->get_checkout_order_received_url();
			} 
			
			if ( $pjn_key=='0' && $currentstatus<>'processing' && $currentstatus<>'completed' ) {
				// Transaction failed
				$order->add_order_note('PayJustNow transaction status: '.$pjn_status);
				$order->update_status('failed', __('Payment Failed.', 'woocommerce_gateway_payjustnow'));
				// Send failure url - send to checkout page
				$check_pjn_url = $order->get_checkout_payment_url( $on_checkout = false );
			}			
			
		}
		
	} else { // GET not set, failed
		$error_message = 'PAYJUSTNOW ERROR: GET variables not set.';
		$this->log( $error_message );
	}    
	
	// All checks done, send response to PayJustNow
	if ( $check_pjn_url == 'error' ) {
		//error - omit the return_url
		$data = [ 'token' => $check_pjn_token ];
		
		// Error logging...
		if ( $this->settings['enablelogs'] <> 'no' ) {
			 $this->log('================ Response sent to PJN:');
			 $this->log('token: '.$check_pjn_token );
			 $this->log('return_url: not sent - check Callback!' );
			 $this->log('================');
		}

	} else {
		$data = [ 'token' => $check_pjn_token, 'return_url' => $check_pjn_url ];
		
		// Error logging...
		if ( $this->settings['enablelogs'] <> 'no' ) {
			 $this->log('================ Response sent to PJN:');
			 $this->log('token: '.$check_pjn_token );
			 $this->log('return_url: '.$check_pjn_url);
			 $this->log('================');
		}

	}
	
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($data);
	exit;
	
}

// Settings page
public function init_form_fields()
	{
	$this->form_fields = array(
		'enabled'     => array(
		'title'       => __( 'Enable/Disable', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Enable PayJustNow Payment Gateway', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'Whether this gateway is enabled in WooCommerce or not.',
		'default'     => 'yes'
		),
		'title'       => array(
		'title'       => __( 'Title', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce_gateway_payjustnow' ),
		'default'     => __( 'Instalments via PayJustNow', 'woocommerce_gateway_payjustnow' ),
		'css'         => 'width: 300px;'
		),
		'description' => array(
		'title'       => __( 'Description', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'textarea',
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce_gateway_payjustnow' ),
		'default'     => __( 'Pay over 3 zero-interest instalments with PayJustNow.', 'woocommerce_gateway_payjustnow' )
		),
		'pjn_username' => array(
		'title'       => __( 'Merchant ID', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'text',
		'description' => __( 'This is your Merchant ID, received from PayJustNow.', 'woocommerce_gateway_payjustnow' ),
		'default'     => ''
		),
		'pjn_password' => array(
		'title'       => __( 'Merchant Api Key', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'text',
		'description' => __( 'This is your Merchant Api Key, received from PayJustNow.', 'woocommerce_gateway_payjustnow' ),
		'default'     => ''
		),
		'show_on_single_product'   => array(
		'title'       => __( 'Show on Product Page', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Show on Product Page', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'Whether to show the PayJustNow payment instalments on individual product pages.',
		'default'     => 'yes'
		),
		'show_after_cart_total'   => array(
		'title'       => __( 'Show after Cart Total', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Show after Cart Total', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'Whether to show the PayJustNow payment instalments text after Cart Total on the View Cart page.',
		'default'     => 'yes'
		),
		'sandboxmode' => array(
		'title'       => __( 'Sandbox Mode', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Enable Sandbox Mode', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'Whether this gateway is in Sandbox Mode (only enable for testing/integration).',
		'default'     => 'no'
		),
		'enablelogs' => array(
		'title'       => __( 'Enable Logs', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Enable Logs', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'Whether this gateway should log to WooCommerce > Status > Logs (only enable for testing/integration).',
		'default'     => 'no'
		),
		'usedarktheme' => array(
		'title'       => __( 'Dark Theme', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Enable Dark Theme', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'Choose the dark theme when your website has a black or dark background.',
		'default'     => 'no'
		),		
		'useordernumber' => array(
		'title'       => __( 'Order ID or Number', 'woocommerce_gateway_payjustnow' ),
		'label'       => __( 'Use the Order Number', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'checkbox',
		'description' => 'By default the Order ID (Post ID) is used for reference. Select this option to use the Order Number instead.',
		'default'     => 'no'
		),
		'order_text'  => array(
		'title'       => __( 'Order Button Text', 'woocommerce_gateway_payjustnow' ),
		'type'        => 'text',
		'description' => __( 'What text should appear on the order button.', 'woocommerce_gateway_payjustnow' ),
		'default'     => 'Proceed to PayJustNow'
		)
	);
	
}

// Provide the plugin URL
public function plugin_url()
	{
	if ( isset( $this->plugin_url ) ) {
		return $this->plugin_url;
	}
	
	if ( is_ssl() ) {
		return $this->plugin_url = str_replace( 'http://', 'https://', WP_PLUGIN_URL ) . "/" . plugin_basename( dirname( dirname( __FILE__ ) ) );
	} else {
		return $this->plugin_url = WP_PLUGIN_URL . "/" . plugin_basename( dirname( dirname( __FILE__ ) ) );
	}
}

// Admin Panel Options
public function admin_options()
	{
	?>
	<h3>
	<?php _e( 'PayJustNow options', 'woocommerce_gateway_payjustnow' );?>
	</h3>
	<p><?php printf( __( 'This sends the user to %sPayJustNow%s to enter their payment information.', 'woocommerce_gateway_payjustnow' ), '<a href="https://payjustnow.com/">', '</a>' );?></p>
	<table class="form-table">
	<?php
	// Generate the HTML For the settings form.
	$this->generate_settings_html();
	?>
	<tr valign="top">
	<td colspan="2"></td>
	</tr>
	</table>
	<?php
}

// No payment fields needed, only added to show description under the PayJustNow option on checkout
public function payment_fields()
	{
	global $woocommerce;
	$ordertotal = $woocommerce->cart->total;	
	$payjustnowone = $ordertotal/3;
	$payjustnowone = ceil($payjustnowone*100)/100;
	echo wpautop(wptexturize('Pay over 3 zero-interest instalments of '.wc_price($payjustnowone).' with <b>PayjustNow</b>.'));
}

// Process payment
public function process_payment( $order_id )
{
	
global $woocommerce;
$order = wc_get_order( $order_id );
  
	try {
	
		// Get order details
		if(!session_id()) {
			session_start();
		}
		
		$amount = $order->get_total();
		$currency = get_option( 'woocommerce_currency' );
		$order_id = $order->get_id();
		$_SESSION['orderID'] = $order_id;
		$order_number = trim( str_replace( '#', '', $order->get_order_number() ) );
		$order_email = $order->get_billing_email();
		$order_key = $order->get_order_key();
		$order_province =  $order->get_billing_state();	
		$_SESSION['order_key'] = $order_key;
		$total_in_cents = round( $order->get_total() * 100 );
		
		// Get basket items and count them, and add to the orderitemsarray
		$items = $order->get_items();
		$basket_count=0;
		$orderitemsarray = array();
		
		foreach ( $items as $item ) {
			$product = wc_get_product( $item['product_id'] );
			$prod_description = $product->get_name();
			$price_in_cents = round( $product->get_price() * 100 );
			$product_sku = $product->get_sku();
			if ($product_sku==''){
				$product_sku = $item['product_id'];
			}
			$product_qty = $item['quantity'];
			$orderitemsarray[$basket_count] = array(
				'merchant_reference' => $product_sku,
				'quantity' => $product_qty,
				'description' => $prod_description,				
				'unit_price' => $price_in_cents
			);		 	
			$basket_count++;
		}
		
		// Add order shipping costs
		$order_shipping_total = round( $order->get_total_shipping() * 100 );
		
		// Create security string and save for succesful payment verification
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$input_length = strlen($permitted_chars);
		$random_string = '';
		for($i = 0; $i < 20; $i++) {
			$random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}
		$pjn_key = $random_string;
		
		//Check if no pjn_key exists already (this is to prevent creation of a new key)
		$check_pjn_key = get_post_meta( $order->id, 'pjn_key', true );
		if ( ! empty( $check_pjn_key ) ) { $pjn_key = $check_pjn_key; }
		
		update_post_meta( $order_id, 'pjn_key', $pjn_key );
		
		$success_callback_url = add_query_arg( array( 'wc-api' => 'wc_gateway_payjustnow', 'pjn_key' => $pjn_key, 'order_key' => $order_key), home_url( '/' ) );
		$fail_callback_url = add_query_arg( array( 'wc-api' => 'wc_gateway_payjustnow', 'pjn_key' => '0', 'order_key' => $order_key), home_url( '/' ) );

		// Build payload
		
		//order id or order number?
		$payjustnow_settings = get_option('woocommerce_payjustnow_settings');
		$payjustnow_order_number = $payjustnow_settings['useordernumber'];
		if ( $payjustnow_order_number == 'yes' ) { 
			$merchant_reference = $order_number;
		} else {
			$merchant_reference = $order_id;
		}

		$billing_first_name	= $order->get_billing_first_name();
		$billing_last_name = $order->get_billing_last_name();
		$billing_company = $order->get_billing_company();
		$billing_email = $order->get_billing_email();
		$billing_phone = $order->get_billing_phone();
		$billing_address_1 = $order->get_billing_address_1();
		$billing_address_2 = $order->get_billing_address_2();
		$billing_city = $order->get_billing_city();
		$billing_postcode = $order->get_billing_postcode();
		$billing_country = $order->get_billing_country();
		
		$payload = array(
			'customer' => array(
				'first_name' => $billing_first_name,
				'last_name' => $billing_last_name,
				'company' => $billing_company,
				'email' => $billing_email,
				'mobile_number' => $billing_phone,
				'address' => array(
				'address_line_1' => $billing_address_1,
				'address_line_2' => $billing_address_2,
				'city' => $billing_city,
				'postal_code' => $billing_postcode,
				'province' => $order_province,
				'country' => $billing_country
			)
			),
			'order' => array(
				'amount' => $total_in_cents,
				'merchant_reference' => $merchant_reference,
				'success_callback_url'=> $success_callback_url,
				'fail_callback_url'=> $fail_callback_url,
				'basket_count' => $basket_count,
				'shipping_cost' => $order_shipping_total,
				'items' => $orderitemsarray
			)
		);
		
		$merchant_id = $this->settings['pjn_username'];
		$merchant_api_key = $this->settings['pjn_password'];
		$basicauth = 'Basic ' . base64_encode( $merchant_id . ':' . $merchant_api_key );
		
		// Build headers
		$headers = array( 
			'Authorization' => $basicauth,
			'Content-type' => 'application/json',
			'Accept'        => 'application/json'
		);
		
		// Put it all together
		$pload = array(
			'method' => 'POST',
			'timeout' => 30,
			'allow_redirects' => false,
			'httpversion' => '1.0',
			'headers' => $headers,
			'body' => json_encode($payload),
			'cookies' => array()
		);
		
		// Are we in Sandbox Mode?
		$url = 'https://sandbox.payjustnow.com/api/v1/merchant/checkout';
		if ( $this->settings['sandboxmode'] == 'no' ) {
			$url = 'https://api.payjustnow.com/api/v1/merchant/checkout';
		}
	
		// Post to PayJustNow
		$response = wp_remote_post($url, $pload);

		// Error logging...
		if ( $this->settings['enablelogs'] <> 'no' ) {
			 $this->log('================ New transaction started on:');
			 $this->log(print_r($url, true) );
			 $this->log('================ Token request sent to PJN:');
			 $this->log(print_r($pload, true) );
			 $this->log('================ Token response from PJN:');
			 $this->log(print_r($response, true) );
			 $this->log('================');
		}
	
		// Check for error and exit if needed
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$this->log( $error_message );
			return;
		} else {
				
			// Token received from PayJustNow
			$responseData = json_decode(wp_remote_retrieve_body($response), true);
			$responseDataToken = '';
			$responseDataExpires = '';
			$responseDataRedirect = '';
				
				// Check for wrong ID/Key
				if (!empty($responseData['message'])) {
					// Can not be redirected, no url/token
					$error_message = 'Error received from PayJustNow: '.$responseData['message'];
					$this->log( $error_message );
					return;
				}
				
				
				if (!empty($responseData['data']['token'])) {
					$responseDataToken = $responseData['data']['token'];
					$responseDataExpires = $responseData['data']['expires_at'];
					$responseDataRedirect = $responseData['data']['redirect_to'];
				}
				
				// Client redirected to PayJustNow for processing
				if ($responseDataRedirect<>'' && $responseDataToken<>'') {
					update_post_meta( $order_id, 'pjn_token', $responseDataToken );
					$order->add_order_note('Redirected to PayJustNow for processing. ('.$responseDataToken.')');
					return array('result' => 'success', 'redirect' => $responseDataRedirect);
				} else {
					// Can not be redirected, no url/token
					$error_message = 'No token/redirect received from PayJustNow for order no: '.$order_number;
					$this->log( $error_message );
					return;
				}
			
		}
	
	} catch ( Exception $e ) {
		$error_message = __( 'Error:', 'woocommerce_gateway_payjustnow' ) . ' "' . $e->getMessage() . '"';
		wc_add_notice( $error_message, 'error' );
		$this->log( $error_message );
		return;
	}

}

// New from version 2.0: Refunds
public function process_refund( $order_id, $amount = NULL, $reason = '' ) {
	
	// "type": "full" or "partial"
	// "status": "REFUNDED" or "FAILED"
	
	global $woocommerce;
	
	$order = wc_get_order( $order_id );
	$order_number = trim( str_replace( '#', '', $order->get_order_number() ) );
	$order_key = $order->get_order_key();
	
	$ordertotal_in_cents = round( $order->get_total() * 100 );
	$refundamount = round( $amount * 100 );

	//order id or order number?
	$payjustnow_settings = get_option('woocommerce_payjustnow_settings');
	$payjustnow_order_number = $payjustnow_settings['useordernumber'];
	if ( $payjustnow_order_number == 'yes' ) { 
		$merchant_reference = $order_number;
		} else {
		$merchant_reference = $order_id;
	}

	if ($reason == ''){$reason = 'No reason supplied.';}
	
	if ($ordertotal_in_cents == $refundamount){ $refundtype = 'full'; } else { $refundtype = 'partial'; }
	
	if( 'refunded' == $order->get_status() ) {
		$order->add_order_note('Order has been already refunded.');		
		$error_message = 'Order has been already refunded: '.$order_number;
		$this->log( $error_message );
		return;		
	}
	
	$check_pjn_token = get_post_meta( $order->id, 'pjn_token', true );
		
	// Build payload
	//{"merchant_reference": "4456",    "token": "683b38b558628acbb4aa137d4cfdd337",    "type": "full",    "amount": 50000,    "reason": "test"}

	$payload = array(
		'merchant_reference' => $merchant_reference,
		'token' => $check_pjn_token,
		'type' => $refundtype,
		'amount' => $refundamount,
		'reason' => $reason
	);
		
	$merchant_id = $this->settings['pjn_username'];
	$merchant_api_key = $this->settings['pjn_password'];
	$basicauth = 'Basic ' . base64_encode( $merchant_id . ':' . $merchant_api_key );
		
	// Build headers
	$headers = array( 
		'Authorization' => $basicauth,
		'Content-type' => 'application/json',
		'Accept'        => 'application/json'
	);
		
	// Put it all together
	$pload = array(
		'method' => 'POST',
		'timeout' => 30,
		'allow_redirects' => false,
		'httpversion' => '1.0',
		'headers' => $headers,
		'body' => json_encode($payload),
		'cookies' => array()
	);
		
	// Are we in Sandbox Mode?
	$url = 'https://sandbox.payjustnow.com/api/v1/merchant/refund';
	if ( $this->settings['sandboxmode'] == 'no' ) {
		$url = 'https://api.payjustnow.com/api/v1/merchant/refund';
	}
	
	// Post to PayJustNow
	$response = wp_remote_post($url, $pload);
	
	// Error logging...
	if ( $this->settings['enablelogs'] <> 'no' ) {
		 $this->log('================ New refund started on:');
		 $this->log(print_r($url, true) );
		 $this->log('================ Token request sent to PJN:');
		 $this->log(print_r($pload, true) );
		 $this->log('================ Token response from PJN:');
		 $this->log(print_r($response, true) );
		 $this->log('================');
	}	
	
	// Check for error and exit if needed
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		$this->log( $error_message );
		return;
	} else {
				
		// Token received from PayJustNow
		//{    "status": "REFUNDED",    "reason": "Order successfully refunded",    "refunded_at": "2022-02-08T09:03:56.292949Z",    "amount_refunded": 50000}
		//{    "status": "FAILED",    "reason": {        "errors": {            "code": 400,            "message": "Amount is greater than available amount refundable."        }    }}
		$responseData = json_decode(wp_remote_retrieve_body($response), true);
		$responseDataToken = '';
		$responseDataExpires = '';
		$responseDataRedirect = '';
		
		// Check for wrong ID/Key
		if (!empty($responseData['errors'])) {
			// Can not be redirected, no url/token
			$error_message = 'Error received from PayJustNow: '.$responseData['message'];
			$this->log( $error_message );
			return;
		}
		
		// "status": "REFUNDED" or "FAILED"
		if (!empty($responseData['status'])) {
			$responseDataStatus = $responseData['status'];			
		}
		
		if ($responseDataStatus=='FAILED') {
			$error_message = 'Error received from PayJustNow: '.$responseData['errors'];
			$this->log( $error_message );
			return;
		} 
		
		if ($responseDataStatus=='REFUNDED') {
		
			$responseDatareason = $responseData['reason'];
			$responseDatarefundeddate = $responseData['refunded_at'];
			$responseDataamountrefunded = $responseData['amount_refunded'];
			$responseDataamountrefunded = wc_format_decimal($responseDataamountrefunded / 100);
			
			$order->add_order_note('Refund amount: '.$responseDataamountrefunded.', reason: '.$reason);
			$order->add_order_note('Message from PayJustNow: ('.$responseDatareason.')');
								
			return true;					

		} 
	}
}


} // End Class
?>
