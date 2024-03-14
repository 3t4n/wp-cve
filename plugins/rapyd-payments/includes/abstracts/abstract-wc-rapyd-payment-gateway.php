<?php
if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . './../consts/rapyd-consts.php';
require_once dirname(__FILE__) . './../categories/rapyd-categories.php';

/**
 * Abstract class that will be inherited by all payment methods.
 */

abstract class WC_Rapyd_Payment_Gateway extends WC_Payment_Gateway {


	abstract public function getCategory();

	public function constructor_helper() {

		// Load the form fields.
		$this->init_form_fields();

		//Load the settings.
		$this->init_settings();

		$this->rapyd_access_key_prod = $this->get_option('rapyd_access_key_prod');
		$this->rapyd_secret_key_prod = $this->get_option('rapyd_secret_key_prod');
		$this->rapyd_access_key_test = $this->get_option('rapyd_access_key_test');
		$this->rapyd_secret_key_test = $this->get_option('rapyd_secret_key_test');
		$this->rapyd_test_mode_enabled = $this->get_option('rapyd_test_mode_enabled');
		$this->enabled = $this->get_option('enabled');
		$this->title = $this->rapyd_get_title();
		$this->description = $this->rapyd_get_description();


		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this , 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'rapyd_wc_receipt_toolkit_page' ) );
		add_action( 'woocommerce_api_' . $this->id . '_payment_callback', array( $this , 'rapyd_wc_callback_handler' ) );
		add_action( 'woocommerce_api_' . $this->id . '_refund_callback', array( $this , 'rapyd_wc_refund_handler' ) );
		if ('yes' === $this->enabled) {
			add_filter( 'the_title' , array( $this , 'rapyd_order_received_title' ), 10, 2 );
			add_filter( 'woocommerce_thankyou_order_received_text' , array( $this , 'rapyd_order_received_text' ), 10, 2 );
		}
	}

	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		$subscriptions_ids = wcs_get_subscriptions_for_order( $renewal_order->get_id(), array( 'order_type' => 'any' ) );
		// We get the related subscription for this order
		$payment_method = 'NOT_FOUND';
		$payment_token = 'NOT_FOUND';
		foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
			$payment_method = get_post_meta($subscription_id, 'payment_method', true);
			$payment_token = get_post_meta($subscription_id, 'payment_token', true);
			if (!empty($payment_method) && 'NOT_FOUND'!=$payment_method) {
				break;
			}
		}
		$order = new WC_Order($renewal_order->get_id());
		$body = array(
			'amount' => $this->encode_string($amount_to_charge),
			'order_id' => $this->encode_string($renewal_order->get_id()),
			'payment_method' => $this->encode_string($payment_method),
			'payment_token' => $this->encode_string($payment_token),
			'currency_code' => $this->encode_string($order->get_currency()),
			'webhook_url' => $this->encode_string(WC()->api_request_url($this->id . '_payment_callback' ) ),
			'refund_url' => $this->encode_string(WC()->api_request_url($this->id . '_refund_callback' ) ),
		);
		
		$response = $this->send_request_to_rapyd($body, RAPYD_SUBSCRIPTIONS_PAYMENTS_PATH);
		
		if ( empty( $response ) || empty( $response->woo_status ) || ( empty( $response->woo_order_note ) ) ) {
			return false;
		}
		$renewal_order->update_status(sanitize_text_field($response->woo_status), 'order_note');
		$renewal_order->add_order_note(sanitize_text_field($response->woo_order_note));
		return true;
	}

	public function rapyd_order_received_title( $title, $id = null ) { 
		if ( is_order_received_page() && get_the_ID() === $id && 'Checkout' != $title ) { 
			global $wp;

			// Get the order.
			$order_id = apply_filters('woocommerce_thankyou_order_id', absint($wp->query_vars['order-received']));
			$order_key = apply_filters('woocommerce_thankyou_order_key', empty($_GET['key']) ? '' : wc_clean($_GET['key']));

			if ($order_id > 0) {
				$order = wc_get_order($order_id);
				if ($order->get_order_key() != $order_key) {
					$order = false;
				}
			}

			if (isset ($order) && $this->id === $order->get_payment_method()) {

				$order_status = $order->get_status();
				if ('processing' == $order_status || 'completed' == $order_status) {
					$title = esc_html(__('Your payment was successful', 'rapyd-payments-plugin-for-woocommerce'));
				} else if ('failed' == $order_status) {
					$title = esc_html(__('There is an issue with your payment', 'rapyd-payments-plugin-for-woocommerce'));
				} else if ('cancelled' == $order_status) {
					$title = esc_html(__('There is an issue with your payment', 'rapyd-payments-plugin-for-woocommerce'));
				} else {
					$title = esc_html(__('Your payment is on hold', 'rapyd-payments-plugin-for-woocommerce'));
				}
			}
		}
		return $title;
	}

	public function rapyd_order_received_text( $text, $order ) { 
		if ($order && $this->id === $order->get_payment_method()) { 
			$order_status = $order->get_status();
			if ('processing' == $order_status || 'completed' == $order_status) {
				return esc_html(__('Thank you for your order.', 'rapyd-payments-plugin-for-woocommerce'));
			} else if ('failed' == $order_status) {
				return esc_html(__('Sorry, something is wrong with your payment. Please go back to recheck payment information and try again.', 'rapyd-payments-plugin-for-woocommerce'));
			} else if ('cancelled' == $order_status) {
				return esc_html(__('Your payment was cancelled.', 'rapyd-payments-plugin-for-woocommerce'));
			} else {
				return esc_html(__('Final confirmation for your payment will be arriving soon. Look for updates regarding your payment status.', 'rapyd-payments-plugin-for-woocommerce'));
			}
		}

		return $text;
	}

	public function init_form_fields() {
		$this->form_fields = require(dirname(__FILE__) . '/../admin/rapyd-settings.php');
	}

	/**
	 * Checks if gateway should be available to use.
	 */
	public function is_available() {
		if ('yes' != $this->enabled) { //check if enabled on main settings of admin panel
			return false;
		}
		$instance = SingletonCategories::getInstance();
		if (empty( $instance->getCategories() )) {
			$instance->setCategories( $this->send_get_request_to_rapyd(RAPYD_CATEGORIES_PATH) );
		}

		if ( !( $instance->getCategories() ) || ( !empty( $instance->getCategories()->status ) && 'ERROR' == $instance->getCategories()->status->status ) ) {
			return false;
		}
		foreach ( $instance->getCategories() as $value ) {
			if ($value == $this->getCategory()) {
				return true;
			}
		}
		return false;
	}

	public function getNameOfClass() {
		return static::class;
	}

	public function rapyd_wc_receipt_toolkit_page( $order_id ) {
		//added headers to disable cache
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
		header('Pragma: no-cache'); // HTTP 1.0.
		header('Expires: 0 '); // Proxies.
		$order = new WC_Order($order_id);
		
		$page_type = 'collection';
		if ( $order->get_total()==0 ) {
			$page_type = 'card_token';
		}
		echo '<script>var RAPYD_CLASS_NAME = "' . esc_js($this->getNameOfClass()) . '"</script>';
		echo '<script>var RAPYD_ORDER_ID = "' . esc_js($order_id) . '"</script>';
		echo '<script>var RAPYD_TOOLKIT_ORDER_PLACED="' . esc_js(__( 'Your order has been placed, but is not complete', 'rapyd-payments-plugin-for-woocommerce' )) . '"</script>';
		echo '<script>var RAPYD_TOOLKIT_THANK_YOU="' . esc_js(__( 'Thank you for your order', 'rapyd-payments-plugin-for-woocommerce' )) . '"</script>';
		echo '<script>var RAPYD_TOOLKIT_COMPLETE="' . esc_js(__( 'To complete your payment, please follow the steps below:', 'rapyd-payments-plugin-for-woocommerce' )) . '"</script>';

		echo '<script>var RAPYD_TOOLKIT_CLICK_TEXT="' . esc_js(__( 'Click to pay', 'rapyd-payments-plugin-for-woocommerce' )) . '"</script>';
		echo '<script>var RAPYD_PAYMENT_TOOLKIT_ERROR="' . esc_js(__( 'Sorry, something is wrong with your payment. Please go back to recheck payment information and try again.', 'rapyd-payments-plugin-for-woocommerce' )) . '"</script>';
		echo '<script>var RAPYD_PAYMENT_TOOLKIT_PAGE_TYPE="' . esc_js($page_type) . '"</script>';
		echo '<div id="rapyd_div">';
		$this->rapyd_get_test_mode_toolkit_notice();
		echo '<img id="rapyd_loader_div" style="width:50px;height:50px;display:block; margin:0 auto;position: relative;top: 200px;z-index: 2;" src="' . esc_url(LOADER_URL) . '"></img>';
		echo '<script>var ERROR_MESSAGE="' . esc_js(__( 'Sorry, something is wrong with your payment. Please go back to recheck payment information and try again.', 'rapyd-payments-plugin-for-woocommerce' )) . '"</script>';
		echo '<script>var SUCCESS_URL="' . esc_url($this->get_return_url($order)) . '"</script>';
		echo '<div id="rapyd-checkout" style="min-height: 400px;width:100%;z-index: 1;margin: 0 auto;display: block;"></div>';
		echo '</div>';
	}

	public function rapyd_generate_token( $order_id ) {
		$order = new WC_Order($order_id);

		$body = array(
			'amount' => $this->encode_string($order->get_total()),
			'currency_code' => $this->encode_string($order->get_currency()),
			'reference_id' => $this->encode_string($order->get_id()),
			'user_id' => $this->encode_string($order->get_user_id()),
			'webhook_url' => $this->encode_string(WC()->api_request_url($this->id . '_payment_callback' ) ),
			'refund_url' => $this->encode_string(WC()->api_request_url($this->id . '_refund_callback' ) ),
			'complete_payment_url' => $this->encode_string($this->get_return_url($order)),
			'error_payment_url' => $this->encode_string($this->get_return_url($order)),
			'order_id' => $this->encode_string($order->get_id()),
			'receipt_email' => $this->encode_string($order->get_billing_email()),
			'country_code' => $this->encode_string($order->get_billing_country()),
			'customer_first_name' => $this->encode_string($order->get_billing_first_name()),
			'customer_last_name' => $this->encode_string($order->get_billing_last_name()),
			'customer_phone' => $this->encode_string($order->get_billing_phone()),
			'shipping_address' => array(
				'line1' => $this->encode_string($order->get_shipping_address_1()),
				'line2' => $this->encode_string($order->get_shipping_address_2()),
				'city' => $this->encode_string($order->get_shipping_city()),
				'state' => $this->encode_string($order->get_shipping_state()),
				'phone_number' => $this->encode_string(''),
				'country' => $this->encode_string($order->get_shipping_country()),
				'zip' => $this->encode_string($order->get_shipping_postcode())
			),
			'billing_address' => array(
				'line1' => $this->encode_string($order->get_billing_address_1()),
				'line2' => $this->encode_string($order->get_billing_address_2()),
				'city' => $this->encode_string($order->get_billing_city()),
				'state' => $this->encode_string($order->get_billing_state()),
				'phone_number' => $this->encode_string($order->get_billing_phone()),
				'country' => $this->encode_string($order->get_billing_country()),
				'zip' => $this->encode_string($order->get_billing_postcode())
			),
			'lang'=>$this->encode_string(get_bloginfo('language')),
			'category' => $this->encode_string($this->getCategory()),
			'cart' => $this->encode_string($this->buildItemsJson($order)),
			'version'=> $this->encode_string(RAPYD_PLUGIN_VERSION),

		);
		$path = RAPYD_TOOLKIT_PATH;
		if ( $order->get_total()==0 ) {
			$path = RAPYD_TOKENIZATION_PATH;
		}
		$response = $this->send_request_to_rapyd($body, $path);
		$body = array(
			'status' => 'failed',
			'token' => '',
			'message' => ''
		);
		if ( empty( $response ) ) {
			$body['message'] = __('Sorry, something is wrong with your payment. Please go back to recheck payment information and try again.', 'rapyd-payments-plugin-for-woocommerce');
		} else if (!empty($response) && empty($response->token)) {
			$body['message'] = $response;
		} else {
			$body['status'] = 'success';
			$body['token'] = $response->token;
		}
		return $body;
	}

	public function buildItemsJson( $order ) {
		$cart=[];
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$item_name = $cart_item['data']->get_title();
			$quantity = $cart_item['quantity'];
			$price = $cart_item['data']->get_price();
			$id = $cart_item['data']->get_id();
			$productDetail = wc_get_product($id);
			$image = $productDetail->get_image();
			$src = $this->getSrc($image);
			$cart_item = $this->buildCartItem( $item_name, $price, $quantity, $src );
			$cart[] = $cart_item;
		}
		$cart = $this->addFees( $order, $cart );
		$cart = $this->addTaxes( $order, $cart );
		$cart = $this->addShipping( $order, $cart );
		return json_encode($cart, JSON_UNESCAPED_SLASHES);
	}
	
	public function addFees( $order, $cart ) {
		foreach ( $order->get_items('fee') as $item_id => $item_fee ) {
			// The fee name
			$fee_name = $item_fee->get_name();
			// The fee total amount
			$fee_total = $item_fee->get_total();
			if ( $fee_total > 0 ) {
				$cart_item = $this->buildCartItem( $fee_name, $fee_total, 1, '' );
				$cart[] = $cart_item;
			}
		}
		return $cart;
	}
	
	public function addTaxes( $order, $cart ) {
		// Loop through order tax items
		foreach ( $order->get_items('tax') as $item ) {
			$name        = $item->get_name(); // Get rate code name (item title)
			$tax_total   = $item->get_tax_total(); // Get tax total amount (for this rate)
			if ( $tax_total > 0 ) {
				$cart_item = $this->buildCartItem( $name, $tax_total, 1, '' );
				$cart[] = $cart_item;
			}
		}
		return $cart;
	}
	
	public function addShipping( $order, $cart ) {
		$shipping_total = $order->get_shipping_total();
		if ( $shipping_total>0 ) {
			$cart_item = $this->buildCartItem( 'Shipping', $shipping_total, 1, '' );
			$cart[] = $cart_item;
		}
		return $cart;
	}
	
	public function buildCartItem( $name, $amount, $quantity, $image ) {
		return array(
			'name' => $name,
			'amount' => $amount,
			'quantity' => $quantity,
			'image' => $image
		);
	}

	public function getSrc( $str ) {
		$splitted = explode(' ', $str );
		$src_str = null;
		foreach ( $splitted as $key => $value ) {
			$small_split = explode('=', $value);
			if ( 'src' == $small_split[0] ) {
				$src_str = str_replace('"', '', $small_split[1] );
				break;
			}
		}
		return $src_str;
	}
	
	public function encode_string( $str ) { 
		$str = utf8_encode($str);
		return base64_encode($str);
	}

	public function send_request_to_rapyd( $body, $path ) {  
		$http_method = 'post';
		$salt = mt_rand(10000000, 99999999);
		$date = new DateTime();
		$timestamp = $date->getTimestamp();
		$access_key = $this->rapyd_get_access_key();
		$secret_key = $this->rapyd_get_secret_key();

		$body_string = json_encode($body, JSON_UNESCAPED_SLASHES);

		$sig_string = $http_method . $path . $salt . $timestamp . $access_key . $secret_key . $body_string;

		$hash_sig_string = hash_hmac('sha256', $sig_string, $secret_key);

		$signature = base64_encode($hash_sig_string);

		$response = wp_remote_post($this->rapyd_get_api_url() . $path, array(
				'method' => 'POST',
				'timeout' => 120,
				'headers' => array(
					'Content-Type' => 'application/json',
					'access_key' => $access_key,
					'salt' => $salt,
					'timestamp' => $timestamp,
					'signature' => $signature,
					'test_mode' => $this->rapyd_get_test_mode()
				),
				'body' => $body_string
			)
		);

		$body = wp_remote_retrieve_body($response);
		return json_decode($body);
	}

	public function send_get_request_to_rapyd( $path ) { 
		$http_method = 'get';
		$salt = mt_rand(10000000, 99999999);
		$date = new DateTime();
		$timestamp = $date->getTimestamp();
		$access_key = $this->rapyd_get_access_key();
		$secret_key = $this->rapyd_get_secret_key();

		$sig_string = $http_method . $path . $salt . $timestamp . $access_key . $secret_key;

		$hash_sig_string = hash_hmac('sha256', $sig_string, $secret_key);

		$signature = base64_encode($hash_sig_string);

		$response = wp_remote_get($this->rapyd_get_api_url() . $path, array(
				'method' => 'GET',
				'timeout' => 120,
				'headers' => array(
					'Content-Type' => 'application/json',
					'access_key' => $access_key,
					'test' => $access_key,
					'salt' => $salt,
					'timestamp' => $timestamp,
					'signature' => $signature,
					'test_mode' => $this->rapyd_get_test_mode()
				)
			)
		);
		$body = wp_remote_retrieve_body($response);
		return json_decode($body);
	}

	public function process_payment( $order_id ) { 
		global $woocommerce;
		$order = new WC_Order($order_id);
		
		$is_subscription = $this->is_subscription_in_cart();
		
		if ( $order->get_total()==0 && !$is_subscription) {
			wc_add_notice( 'Payments with amount 0 are not supported.', 'error' );
			return array( 'result' => 'failure' );
		}

		return array(
			'result' => 'success',
			'redirect' => $order->get_checkout_payment_url(true)
		);
	}

	public function rapyd_wc_callback_handler() {
		global $woocommerce;
		if (!$this->is_signature_valid()) {
			status_header(400);
			exit;
		}
		$body = file_get_contents('php://input');
		$body = json_decode($body);
		$order = new WC_Order(sanitize_text_field($body->order_id));
		$woo_status = sanitize_text_field($body->woo_status);
		$woo_order_note = sanitize_text_field($body->woo_order_note);

		update_post_meta($order->get_id(), 'payment_token', sanitize_text_field($body->payment_token));
		
		if ( isset($body->payment_method) && class_exists('WC_Subscriptions') ) {
			$order_id = $order->get_id();
			$subscriptions = wcs_get_subscriptions_for_order($order_id, array( 'order_type' => 'any' ));
			foreach ( $subscriptions as $subscription_id => $subscription_obj ) {
				update_post_meta( $subscription_id, 'payment_method', sanitize_text_field($body->payment_method) );
				update_post_meta( $subscription_id, 'payment_token', sanitize_text_field($body->payment_token) );
			}
		}

		if ('processing' == $woo_status) {
			//close the payment (it's a notification on admin)
			$order->payment_complete();
			$woocommerce->cart->empty_cart();
		} else if ('on-hold' == $woo_status) {
			$order->update_status($woo_status, 'order_note');
			$woocommerce->cart->empty_cart();
		} else {
			$order->update_status($woo_status, 'order_note');
		}
		$order->add_order_note($woo_order_note);
		status_header(200);
		exit;

	}

	public function rapyd_wc_refund_handler() {
		if (!$this->is_signature_valid()) {
			status_header(400);
			exit;
		}
		$body = file_get_contents('php://input');
		$body = json_decode($body);
		$order_id = sanitize_text_field($body->order_id);
		$amount = sanitize_text_field($body->amount);
		$reason = sanitize_text_field($body->reason);

		// Create the refund.
		$refund = wc_create_refund(
			array(
				'order_id' => $order_id,
				'amount' => $amount,
				'reason' => $reason,
			)
		);

		if (is_wp_error($refund)) {
			status_header(400);
			exit;
		}

		$order = new WC_Order(sanitize_text_field($body->order_id));
		update_post_meta($order->get_id(), 'refund_token', sanitize_text_field($body->refund_token));

		status_header(200);
		exit;
	}

	public function is_signature_valid() {
		if (empty($_SERVER) || empty($_SERVER['HTTP_SALT']) || empty($_SERVER['HTTP_TIMESTAMP']) || empty($_SERVER['HTTP_ACCESSKEY']) || empty($_SERVER['HTTP_SIGNATURE'])) {
			return false;
		}
		$body = file_get_contents('php://input');
		$body = json_decode($body);
		$http_method = 'post';
		$path = RAPYD_TOOLKIT_PATH;
		$salt = sanitize_text_field($_SERVER['HTTP_SALT']);
		$timestamp = sanitize_text_field($_SERVER['HTTP_TIMESTAMP']);
		$access_key = sanitize_text_field($_SERVER['HTTP_ACCESSKEY']);
		$secret_key = $this->rapyd_get_secret_key();

		$body_string = json_encode($body, JSON_UNESCAPED_SLASHES);

		$sig_string = $http_method . $path . $salt . $timestamp . $access_key . $secret_key . $body_string;

		$hash_sig_string = hash_hmac('sha256', $sig_string, $secret_key);

		$signature = base64_encode($hash_sig_string);

		if (sanitize_text_field($_SERVER['HTTP_SIGNATURE']) == $signature) {
			return true;
		}
		return false;
	}

	public function rapyd_get_access_key() {
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return $this->rapyd_access_key_test;
		}
		return $this->rapyd_access_key_prod;
	}

	public function rapyd_get_secret_key() {
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return $this->rapyd_secret_key_test;
		}
		return $this->rapyd_secret_key_prod;
	}

	public function rapyd_get_api_url() {
		$api_url = getenv('RAPYD_PLUGIN_URL_TEST');
		if (!empty($api_url) && $api_url) {
			return $api_url;
		}
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return RAPYD_PLUGIN_URL_TEST;
		}
		return RAPYD_PLUGIN_URL_PROD;
	}

	public function rapyd_get_test_mode() {
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return 'true';
		}
		return 'false';
	}

	public function rapyd_get_toolkit_url() {
		$toolkit_url = getenv('RAPYD_TOOLKIT_JS_URL_TEST');
		if (!empty($toolkit_url) && $toolkit_url) {
			return $toolkit_url;
		}
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return RAPYD_TOOLKIT_JS_URL_TEST;
		}
		return RAPYD_TOOLKIT_JS_URL_PROD;
	}

	public function process_admin_options() {
		parent::process_admin_options();
		$categories_ids = array(RAPYD_EWALLET_ID, RAPYD_CASH_ID, RAPYD_CARD_ID, RAPYD_BANK_ID, RAPYD_COMMON_ID);
		$arrlength = count($categories_ids);
		for ($i = 0; $i < $arrlength; $i++) {
			$settings = get_option('woocommerce_' . $categories_ids[$i] . '_settings');
			$settings['rapyd_access_key_prod'] = $this->get_option( 'rapyd_access_key_prod' );
			$settings['rapyd_secret_key_prod'] = $this->get_option( 'rapyd_secret_key_prod' );
			$settings['rapyd_access_key_test'] = $this->get_option( 'rapyd_access_key_test' );
			$settings['rapyd_secret_key_test'] = $this->get_option( 'rapyd_secret_key_test' );
			$settings['rapyd_test_mode_enabled'] = $this->get_option( 'rapyd_test_mode_enabled' );
			update_option('woocommerce_' . $categories_ids[$i] . '_settings', $settings);
		}
	}

	public function enableCategories() {
		$categories_ids = array(RAPYD_EWALLET_ID, RAPYD_CASH_ID, RAPYD_CARD_ID, RAPYD_BANK_ID);
		$arrlength = count($categories_ids);
		for ($i = 0; $i < $arrlength; $i++) {
			$settings = get_option('woocommerce_' . $categories_ids[$i] . '_settings');
			$settings['enabled'] = 'yes';
			update_option('woocommerce_' . $categories_ids[$i] . '_settings', $settings);
		}
	}

	public function rapyd_get_title() {
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return $this->title . __( ' - Test Mode', 'rapyd-payments-plugin-for-woocommerce' );
		}
		return $this->title;
	}

	public function rapyd_get_description() {
		if ('yes' == $this->rapyd_test_mode_enabled) {
			return __( 'Please note: test mode does not support real transactions.', 'rapyd-payments-plugin-for-woocommerce' );
		}
		return $this->description;
	}

	public function rapyd_get_test_mode_toolkit_notice() {
		if ('yes' == $this->rapyd_test_mode_enabled) {
			echo '<div style="color: red;margin-bottom: 15px;">' . esc_html(__( 'Please contact store support. Our checkout is still in test mode and we can’t complete your transaction.', 'rapyd-payments-plugin-for-woocommerce' )) . '</div>';
		}
	}
	
	public function is_subscription_in_cart() {
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$id = $cart_item['data']->get_id();
			$product = wc_get_product($id);
			if ( $this->is_product_subscription( $product ) ) {
				return true;
			}
		}
		return false;
	}
	
	public function is_product_subscription( $product ) {
		if ( class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Product::is_subscription( $product ) ) {
			return true;
		} else {
			return false;
		}
	}
}
