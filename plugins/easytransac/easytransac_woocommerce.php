<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

/**
 * Plugin Name: EasyTransac for WooCommerce
 * Plugin URI: https://www.easytransac.com
 * Description: Payment Gateway for EasyTransac. Create your account on <a href="https://www.easytransac.com">www.easytransac.com</a> to get your application key (API key) by following the steps on <a href="https://fr.wordpress.org/plugins/easytransac/installation/">the installation guide</a> and configure the settings.<strong>EasyTransac needs the Woocomerce plugin.</strong>
 * Version: 2.9
 *
 * Text Domain: easytransac_woocommerce
 * Domain Path: /i18n/languages/
 * WC requires at least: 5.6.0
 * WC tested up to: 8.4
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Requirements errors messages
function easytransac__curl_error() {
	$class = 'notice notice-error';
	$message = 'Easytransac: PHP cURL extension missing';
	printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
}

function easytransac__openssl_error() {
	$message = 'EasyTransac: OpenSSL version not supported "' . OPENSSL_VERSION_TEXT . '" < 1.0.1';
	$class = 'notice notice-error';
	printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
}

function use_jquery() {
	wp_enqueue_script('jquery');
}

/**
 * Cancel unpaid orders after WooCommerce settings timeout.
 */
function easytransac_cancel_unpaid_orders() {

	$held_duration = get_option( 'woocommerce_hold_stock_minutes' );
	
	if ( $held_duration < 1 || 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
		file_put_contents('easylog', 'abandon 1'."\n",  FILE_APPEND);
		return;
	}

	wp_clear_scheduled_hook( 'woocommerce_cancel_unpaid_orders' );

	$data_store    = WC_Data_Store::load( 'order' );
	$unpaid_orders = $data_store->get_unpaid_orders( strtotime( '-' . ((absint( $held_duration )*60)-30) . ' SECONDS', current_time( 'timestamp' ) ) );

	if ( $unpaid_orders ) {
		foreach ( $unpaid_orders as $unpaid_order ) {

			$order = wc_get_order( $unpaid_order );

			if ( apply_filters( 'woocommerce_cancel_unpaid_order', 'checkout' === $order->get_created_via(), $order ) ) {
			
				if ($order->get_payment_method() == 'easytransac' ) {

					// Restock products unstocked by EasyTransac plugin
					foreach ($order->get_items() as $item_id => $item) {
						// Get an instance of corresponding the WC_Product object
						$product = $item->get_product();
						$qty = $item->get_quantity(); // Get the item quantity
						wc_update_product_stock($product, $qty, 'increase');
					}
				}

				$order->update_status( 'cancelled', __( 'Unpaid order cancelled - time limit reached.', 'woocommerce' ) );
			}
		}
	}

	// Reschedule event.
	wp_clear_scheduled_hook( 'easytransac_cancel_unpaid_orders_event' );
	$cancel_unpaid_interval = apply_filters( 'woocommerce_cancel_unpaid_orders_interval_minutes', absint( $held_duration ) );
	
	# Seconds before WooCommerce's timeout.
	$delta = (absint( $cancel_unpaid_interval ) * 60) - 30;

	if($delta < 30){
		$delta = 60; // 1 minute default
	}

	wp_schedule_single_event( time() + $delta, 'easytransac_cancel_unpaid_orders_event' );
}

add_action( 'easytransac_cancel_unpaid_orders_event', 'easytransac_cancel_unpaid_orders', 10, 0 );

# 
class EeasytransacLocalLogger extends EasyTransac\Core\Logger{

	public function setLogName($value)
	{
			$this->logName = $value;
			return $this;
	}

	public static function getInstance()
	{
			if (self::$instance == null) {
					self::$instance = new self();
			}

			return self::$instance;
	}

}

function init_easytransac_gateway() {

	if(!class_exists('WC_Payment_Gateway')) return;

	class EasyTransacGateway extends WC_Payment_Gateway {

		function __construct() {

			$this->id = 'easytransac';
			$this->icon = '';
			$this->has_fields = false;
			// juste ET
			$this->method_title = __('EasyTransac', 'easytransac_woocommerce');
			$this->method_description = __('EasyTransac online payment service', 'easytransac_woocommerce');
			$this->description = __('Use your credit card to pay with <a target="_blank" href="https://www.easytransac.com/en">EasyTransac</a>.', 'easytransac_woocommerce');
			$this->init_form_fields();
			$this->init_settings();
			// $this->settings['notifurl'] = get_site_url() . '/wc-api/easytransac';
			$this->supports = array(
							'products',
							'subscriptions',
							'subscription_cancellation',
							'refunds',
							'subscription_amount_changes'
						);

			$this->title = $this->get_option('title');

			// Settings JQuery
			add_action('wp_enqueue_scripts', 'use_jquery');

			// Settings save hook
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);

			// Register EasyTransac callback handler
			add_action('woocommerce_api_easytransac', [$this, 'check_callback_response']);

			// Requirements.
			$openssl_version_supported = OPENSSL_VERSION_NUMBER >= 0x10001000;
			$curl_activated = function_exists('curl_version');

			if (!$openssl_version_supported) {
				add_action('admin_notices', 'easytransac__openssl_error');
			}

			if (!$curl_activated) {
				add_action('admin_notices', 'easytransac__curl_error');
			}

			add_action('woocommerce_subscription_status_pending-cancel', [$this, 'easytransac_subscription_cancelled']);


			// Scheduling unpaid EasyTransac orders cancel timeout.
			if($this->get_option('disable_stock') != 'yes'){

				// Supplant WooCommerce event.
				wp_clear_scheduled_hook( 'woocommerce_cancel_unpaid_orders' );

				if(false === wp_next_scheduled('easytransac_cancel_unpaid_orders_event')){

					$held_duration = get_option( 'woocommerce_hold_stock_minutes' );
					$cancel_unpaid_interval = apply_filters( 'woocommerce_cancel_unpaid_orders_interval_minutes', absint( $held_duration ) );

					# Seconds before WooCommerce's timeout.
					$delta = (absint( $cancel_unpaid_interval ) * 60) - 30;

					if($delta < 30){
						$delta = 60; // 1 minute default
					}

					wp_schedule_single_event( time() + $delta, 'easytransac_cancel_unpaid_orders_event' );
				}
			}elseif(false !== wp_next_scheduled('easytransac_cancel_unpaid_orders_event')){
				wp_clear_scheduled_hook( 'easytransac_cancel_unpaid_orders_event' );
			}

			# Plugin API uri.
			$this->api_url = add_query_arg('wc-api', 'easytransac', home_url('/'));

			# Plugin log folder @setFilePath.
			$this->log_folder = __DIR__ . DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR;
			$this->log_file = sha1('Fit,duG-wiC-t8'.home_url('/').$this->get_option('api_key')).'.log';

			# Clean up earlier versions.
			$cleanpath = __DIR__ . DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'easytransac-sdk.txt';
			if(file_exists($cleanpath)){
				@unlink($cleanpath);
			}

			$cleanpath = __DIR__ . DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'.htaccess';
			if(file_exists($cleanpath)){
				@unlink($cleanpath);
			}

		}
		// Settings form
		function init_form_fields() {

			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'easytransac_woocommerce') ,
					'type' => 'checkbox',
					'label' => __('Enable EasyTransac payment', 'easytransac_woocommerce') ,
					'default' => 'yes',
					'desc_tip' => true,
				) ,
				'title' => array(
					'title' => __('Title', 'easytransac_woocommerce') ,
					'type' => 'text',
					'description' => __('This controls the title which the client sees during checkout.', 'easytransac_woocommerce') ,
					'default' => __('Credit card', 'easytransac_woocommerce') ,
					'desc_tip' => true,
				) ,
				'api_key' => array(
					'title' => __('API Key', 'easytransac_woocommerce') ,
					'description' => __('Your Easytransac application API Key is available in your back office, by editing <a target="_blank" href="https://www.easytransac.com/en/login/application/all">your application</a>.', 'easytransac_woocommerce') ,
					'type' => 'text',
					// infobulle avec point partout
					// Votre clé d'API Easytransac, disponible sur votre espace client dans E-commerce > Applications
					'default' => '',
					'desc_tip' => false,
					'css' => 'width: 800px;',
				),

				'oneclick' => array(
					'description' => __('The credit card is stored for future payments.', 'easytransac_woocommerce'),
					
					'title' => __('One Click payments', 'easytransac_woocommerce') ,
					'type' => 'checkbox',


					'label' => __('Enable', 'easytransac_woocommerce') ,
					'default' => 'no',

					'desc_tip' => true,

				) ,

				'title1'           => array(
					'title' => __('Notification URL', 'easytransac_woocommerce') ,
					'type'        => 'title',
					'description' => __('Enter this notification URL when editing <a target="_blank" href="https://www.easytransac.com/en/login/application/all">your application</a> : ', 'easytransac_woocommerce')
													 .'</br><code>'.get_site_url() . '/wc-api/easytransac</code>',
				),

				'title2'           => array(
					'title'       => __( 'Advanced settings', 'easytransac_woocommerce' ),
					'type'        => 'title',
				),

				'disable_stock' => array(
					'title' => __('Disable order stock level reduce', 'easytransac_woocommerce') ,
					'type' => 'checkbox',
					'description' => __('Makes orders paid via Easytransac not reduce stock level.', 'easytransac_woocommerce') ,
					'label' => __('Disable', 'easytransac_woocommerce') ,
					'default' => 'no',
					'desc_tip' => true,
				) ,

				// Tout en bas  avec Mode debug
				'notifemail' => array(
					'title' => __('E-mail notification', 'easytransac_woocommerce') ,
					'type' => 'text',
					// Notification email
				// Alerter par email les numéros de commande manquants lorsque cela se produit lors d'un paiement par virement bancaire.
					'description' => __('Comma separated e-mail list to notify when an Easytransac notification references a missing order ID, useful with bank transfers.', 'easytransac_woocommerce') ,
					'default' => '',
					'desc_tip' => true,	
				) ,

				'debug_mode' => array(
					'title' => __('Debug', 'easytransac_woocommerce') ,
					'description' => __('Save the transaction log for debugging purpose which will be stored in /wp-content/plugins/easytransac/logs.', 'easytransac_woocommerce'),
					'type' => 'checkbox',
					'label' => __('Enable', 'easytransac_woocommerce') ,
					'default' => 'no',
					'desc_tip' => true,

				),

			);
		}

		/**
		* Returns Easytransac's ClientId.
		* @return string
		*/
		function getClientId() {
			return get_user_meta(get_current_user_id(), 'easytransac-clientid', 1);
		}

		/**
		 * WooCommerce subscription status change action callback.
		 */
		public function easytransac_subscription_cancelled($subscription){
			$order_id = $subscription->order->id;

			$easytransac_tid = get_post_meta($order_id, 'ET_Tid', true);

			// If Debug Mode is enabled


			EeasytransacLocalLogger::getInstance()->setActive($this->get_option('debug_mode')=='yes');
			EeasytransacLocalLogger::getInstance()->setFilePath($this->log_folder);
			EeasytransacLocalLogger::getInstance()->setLogName($this->log_file);
			EeasytransacLocalLogger::getInstance()->write(
				sprintf('Cancellation Subscription id %d - Order id: %d ET Tid: %s',
						$subscription->id, $order_id, $easytransac_tid)
			);

			try {

				$api_key = $this->get_option('api_key');
				EasyTransac\Core\Services::getInstance()->provideAPIKey($api_key);
				$cancel_entity = (new EasyTransac\Entities\Cancellation())
									->setTid($easytransac_tid);
				
				$cancel_request = new EasyTransac\Requests\Cancellation();

				$response = $cancel_request->execute($cancel_entity);

				EeasytransacLocalLogger::getInstance()->write(json_encode($response));
			}catch(Exception $exc) {
				EeasytransacLocalLogger::getInstance()->write('Cancellation Exception: ' . $exc->getMessage());
			}

		}


		/**
		* Process payment.
		* @param int $order_id
		*/
		function process_payment($order_id) {
			
			$order = wc_get_order( $order_id );
			
			if (!$order) {
				// Payment failed : Show error to end user.
				wc_add_notice(__('Payment failed: ', 'easytransac_woocommerce') . 'order not found', 'error');

				return array(
					'result' => 'error',
				);
			}

			$total_subscription = 0;
			// Iterating through each "line" items in the order
			// Count the total price of subscription product
			$subscriptions_counter = 0;
			$normal_item_counter = 0;
			foreach ($order->get_items() as $item_id => $item_data) {

				// Get an instance of corresponding the WC_Product object
				$product = $item_data->get_product();
				$product_type = $product->get_type(); // Get the type of product
				$item_quantity = $item_data->get_quantity(); // Get the item quantity
				$item_total = $item_data->get_total(); // Get the item line total

				// If the product is a subscription product, add to the total
				if ($product_type == 'subscription') {
					$total_subscription += $item_total;
					$product_subscription = WC_Subscriptions_Product::get_period($product);

					// print_r($product_subscription);
					// echo "\r\n - Price:";
					// print_r(WC_Subscriptions_Product::get_price($product));
					// echo "\r\n -  Regular price: ";
					// print_r(WC_Subscriptions_Product::get_regular_price($product));
					// echo "\r\n - Sale price:";
					// print_r(WC_Subscriptions_Product::get_sale_price($product));
					// echo "\r\n";
					// echo "\r\n - Length:";
					// print_r(WC_Subscriptions_Product::get_length($product));
					// echo "\r\n";
					// echo "\r\n - Sign up fee:";
					// print_r(WC_Subscriptions_Product::get_sign_up_fee($product));
					$subscriptions_counter += $item_quantity;
					
					if(WC_Subscriptions_Product::get_trial_length($product) >0)
					{
						wc_add_notice(__('Payment failed: ', 'easytransac_woocommerce') . 'free trial not handled', 'error');
						return array(
							'result' => 'error',
						);
					}
				} else {
					$normal_item_counter++;
				}
			}

			// Coupons recurring percent discount for subscriptions.
			$discount_type = null;
			$recurring_discount_amount = 0;

			try {
				foreach( $order->get_coupon_codes() as $coupon_code ){
					// Retrieving the coupon ID.
					$coupon_post_obj = get_page_by_title($coupon_code, OBJECT, 'shop_coupon');
					$coupon_id       = $coupon_post_obj->ID;
					
					// Get an instance of WC_Coupon object in an array(necessary to use WC_Coupon methods)
					$coupon = new WC_Coupon($coupon_id);
					
					$discount_type = $coupon->get_discount_type();
					// error_log('Coupon debug: '.$coupon_post_obj->ID.' discount type: '.$discount_type);
				}
				
				// Get the Coupon discount amounts in the order
				if($discount_type == 'recurring_percent'){
					$recurring_discount_amount = $order->get_discount_total();
					$recurring_discount_tax = $order->get_discount_tax();
					$get_total = $order->get_total();
					// $msg_debug = sprintf("Discount TYPE: %s - DISCOUNT [ %s ] - DISCOUNT TAX [ %s ]  - ORDER TOTAL [ %s ]",
					// 						$discount_type,
					// 						$recurring_discount_amount,
					// 						$recurring_discount_tax,
					// 						$get_total);
					// error_log('DEBUG: '.$msg_debug);
					$recurring_discount_amount += $recurring_discount_tax;
				} elseif($discount_type == 'recurring_fee'){
					$recurring_discount_amount = $order->get_discount_total();
					$recurring_discount_tax = $order->get_discount_tax();
					$get_total = $order->get_total();
					// $msg_debug = sprintf("Discount TYPE: %s - DISCOUNT [ %s ] - DISCOUNT TAX [ %s ]  - ORDER TOTAL [ %s ]",
					// 						$discount_type,
					// 						$recurring_discount_amount,
					// 						$recurring_discount_tax,
					// 						$get_total);
					// error_log('DEBUG: '.$msg_debug);
					$recurring_discount_amount += $recurring_discount_tax;
				}
			} catch (Exception $exc) {
				$discount_type = null;
				$recurring_discount_amount = 0;
				error_log('Easytransac discount exception: '.$exc->getMessage());
			}

			// -----------------------------------

			if(($subscriptions_counter > 1) || ($subscriptions_counter > 0 && $normal_item_counter > 0))
			{
				wc_add_notice(__('Only one subscription handled at a time.', 'easytransac_woocommerce'), 'error');
				return array(
					'result' => 'error',
				);
			}

			// If OneClick button has been clicked && the order isn't a subscription order.
			$is_oneclick = isset($_POST['is_oneclick']) && !empty($_POST['oneclick_alias']) && (!function_exists('wcs_order_contains_subscription') || !wcs_order_contains_subscription($order));

			$api_key = $this->get_option('api_key');
			$dsecure3 = true;

			$address = $order->get_address();

			$return_url = add_query_arg('wc-api', 'easytransac', home_url('/'));
			$cancel_url = wc_get_cart_url();

			// Requirements.
			$curl_info_string = function_exists('curl_version') ? 'enabled' : 'not found';
			$openssl_info_string = OPENSSL_VERSION_NUMBER >= 0x10001000 ? 'TLSv1.2' : 'OpenSSL version deprecated';
			$https_info_string = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'S' : '';

			$version_string = sprintf('WooCommerce 2.70 [cURL %s, OpenSSL %s, HTTP%s]', $curl_info_string, $openssl_info_string, $https_info_string);
			$language = get_locale() == 'fr_FR' ? 'FRE' : 'ENG';

			// If Debug Mode is enabled
			EeasytransacLocalLogger::getInstance()->setActive($this->get_option('debug_mode')=='yes');
			EeasytransacLocalLogger::getInstance()->setFilePath($this->log_folder);
			EeasytransacLocalLogger::getInstance()->setLogName($this->log_file);

			EasyTransac\Core\Services::getInstance()->provideAPIKey($api_key);

			if ($is_oneclick) {
				// SDK OneClick
				$transaction = (new EasyTransac\Entities\OneClickTransaction())
				->setAlias(strip_tags($_POST['oneclick_alias']))
				->setAmount(100 * $order->get_total())
				->setOrderId($order_id)
				->setClientId($this->getClientId());


				$dp = new EasyTransac\Requests\OneClickPayment();

				try {
					$response = $dp->execute($transaction);
				}
				catch(Exception $exc) {
					EeasytransacLocalLogger::getInstance()->write('Payment Exception: ' . $exc->getMessage());
				}

				if ($response->isSuccess()) {
					/* @var $doneTransaction \EasyTransac\Entities\DoneTransaction */
					$doneTransaction = $response->getContent();

					$this->process_response($doneTransaction);

					if (in_array($doneTransaction->getStatus(), array('captured', 'pending'))) {
						// Payment is processed / captured
						return array(
							'result' => 'success',
							'redirect' => $this->get_return_url($order),
							);
					} else {
						// Log error
						EeasytransacLocalLogger::getInstance()->write('Payment failed: ' . $response->getErrorCode() . ' - ' . $response->getErrorMessage());

						// Payment failed : Show error to end user.
						wc_add_notice(__('Payment failed: ', 'easytransac_woocommerce') . $response->getContent()->getError(), 'error');

						return array(
							'result' => 'error',
							);
					}
				} else {
					// Log error
					EeasytransacLocalLogger::getInstance()->write('Payment failed: ' . $response->getErrorCode() . ' - ' . $response->getErrorMessage());

					// Payment failed : Show error to end user.
					wc_add_notice(__('Payment failed: ', 'easytransac_woocommerce') . $response->getErrorMessage(), 'error');

					return array(
						'result' => 'error',
						);
				}
			} else {
				// Phone number traitement of '+'
				if (!preg_match("/^[0-9]{7,15}$/", $address['phone'])) {
					$address['phone'] = str_replace("+", "00", $address['phone']);
					if (!preg_match("/^[0-9]{7,15}$/", $address['phone'])) {
						$address['phone'] = '';
						// return wc_add_notice(__('Billing phone is not valid phone number.', 'easytransac_woocommerce'), 'error');
					}

					if(empty(intval($address['phone']))) {
						$address['phone'] = '';
					}

				}

				$country = 'FRA';
				if(isset($address['country'])){
					$country = $this->iso2to3($address['country']);
				}

				// SDK Payment Page
				$customer = (new EasyTransac\Entities\Customer())
					->setEmail($address['email'])
					->setUid($order->get_user_id())
					->setFirstname($address['first_name'])
					->setLastname($address['last_name'])
					->setAddress($address['address_1'] . ' ' . $address['address_2'])
					->setZipCode($address['postcode'])
					->setCity($address['city'])
					->setCountry($country)
					->setBirthDate('')
					->setNationality('')
					->setCallingCode('')
					->setPhone($address['phone']);

				// If the order contains a subscription product.
				if (function_exists('wcs_order_contains_subscription') && wcs_order_contains_subscription($order)) {

					$transaction = (new EasyTransac\Entities\PaymentPageTransaction())
						->setRebill(WC_Subscriptions_Product::get_length($product) == 0 ? 'yes' : 'no')// If expire date is never (0) = yes
						->setCustomer($customer)
						->setOrderId($order_id)
						->setReturnUrl($return_url)
						->setCancelUrl($cancel_url)
						->setSecure($dsecure3)
						->setVersion($version_string)
						->setLanguage($language);

					if(!empty($discount_type)){
						$discount_description = sprintf('Commande %d - Réduction récurrente: %d', 
														$order_id,
														$recurring_discount_amount);
						$transaction->setDescription($discount_description);
					}

					// EU VAT assistant.
					$has_vat_number = false;
					try{
						$vn = get_post_meta($order->get_id(), 'vat_number', true);
						$has_vat_number = !empty($vn);
						unset($vn);
					}catch(Exception $e){
						error_log('Easytransac vat_number exception: '.$e->getMessage());
					}

					# Subscription product price.
					if( ! $has_vat_number){
						$product_price = wc_get_price_including_tax($product);
					}else{
						$product_price = wc_get_price_excluding_tax($product);
					}

					# Fee
					$signup_fee_inc_tax = wc_get_price_including_tax($product, ['price' =>  WC_Subscriptions_Product::get_sign_up_fee($product)] );
					$signup_fee_exc_tax = wc_get_price_excluding_tax($product, ['price' =>  WC_Subscriptions_Product::get_sign_up_fee($product)] );
					if( ! $has_vat_number){
						$signup_fee = $signup_fee_inc_tax;
					}else{
						$signup_fee = $signup_fee_exc_tax;
					}

					// Validation if multiple payments limited to 12 times.
					if(WC_Subscriptions_Product::get_length($product) > 0) {
						
						if(!in_array($product_subscription, ['day', 'week', 'month'])){
							return wc_add_notice(__('EasyTransac only accepts billing periods of months, weeks or days.', 'easytransac_woocommerce'), 'error');
						}

						if(WC_Subscriptions_Product::get_length($product) > 12){
							return wc_add_notice(__('Billing periods over 12 times are not supported by EasyTransac.', 'easytransac_woocommerce'), 'error');
						}
					}

					if(WC_Subscriptions_Product::get_length($product) > 0)
					{
						$transaction->setMultiplePayments(WC_Subscriptions_Product::get_length($product) > 0 ? 'yes' : 'no');
						// If expire date is a number (value>0) of days = yes

						$amount = 100 * ($product_price - $recurring_discount_amount)
								  * WC_Subscriptions_Product::get_length($product) 
								  + (100 * $signup_fee);
						$transaction->setAmount($amount);

						$transaction->setMultiplePaymentsRepeat(WC_Subscriptions_Product::get_length($product));

						# Minimum initial payment is 1 euro since v2.68.
						# Not setting down payment in order to let EasyTransac set it.
						// $initial = intval(ceil(0.20 * $amount));
						// if($initial > ($amount / WC_Subscriptions_Product::get_length($product) )){
						// 	$transaction->setDownPayment($initial);
						// }
					}
					else
					{
						$transaction->setRebill('yes');
						$transaction->setAmount( 100 * 
												 ($product_price - $recurring_discount_amount));
												 // Amount per period
						if(WC_Subscriptions_Product::get_sign_up_fee($product) > 0)
						{
							// Subscription fee added on firstpayment
							$transaction
							->setDownPayment(100 * ($product_price - $recurring_discount_amount + $signup_fee));
						}
					}

					switch ($product_subscription) {
						case 'day':
							$transaction->setRecurrence('daily');
						break;
						case 'week':
							$transaction->setRecurrence('weekly');
						break;
						case 'month':
							$transaction->setRecurrence('monthly');
						break;
						case 'year':
							return wc_add_notice(__('EasyTransac only accepts billing periods of months, weeks or days.', 'easytransac_woocommerce'), 'error');
						break;
						case '':
							return wc_add_notice(__('EasyTransac only accepts billing periods of months, weeks or days.', 'easytransac_woocommerce'), 'error');
						break;
					}
				} else {
					// If the order contains only "normal" products.
					$transaction = (new EasyTransac\Entities\PaymentPageTransaction())
						->setAmount(100 * $order->get_total())
						->setCustomer($customer)
						->setOrderId($order_id)
						->setReturnUrl($return_url)
						->setCancelUrl($cancel_url)
						->setSecure($dsecure3)
						->setVersion($version_string)
						->setLanguage($language);
				}

				/* @var $response \EasyTransac\Entities\PaymentPageInfos */
				try {
					$request = new EasyTransac\Requests\PaymentPage();
					$response = $request->execute($transaction);
				}
				catch (Exception $exc) {
					EeasytransacLocalLogger::getInstance()->write('Payment Exception: ' . $exc->getMessage());
				}
			}

			$_SESSION['easytransac_order_id'] = $order_id;

			if (!$response->isSuccess()) {
				// Log error
				EeasytransacLocalLogger::getInstance()->write('Payment error: ' . $response->getErrorCode() . ' - ' . $response->getErrorMessage());

				// Show error to end user.
				wc_add_notice(__('Payment error:', 'easytransac_woocommerce') . ' ' . $response->getErrorCode() . ' - ' .$response->getErrorMessage(), 'error');

				// Returns error.
				return array(
					'result' => 'error',
					);
			}

			// Reduce stock levels if not disabled by option.
			
			if($this->get_option('disable_stock') == 'no'){
				if (function_exists('wc_reduce_stock_levels')) {
					// WooCommerce v3
					wc_reduce_stock_levels($order);
				} else {
					$order->reduce_order_stock();
				}
			}

			// Redirect to EasyTransac Payment page
			return array(
				'result' => 'success',
				'redirect' => $response->getContent()->getPageUrl(),
			);
		}

		/**
		* Listcards AJAX callback.
		*/
		function listcards() {
			$clientId = $this->getClientId();
			if (!$clientId || empty($clientId))
				die(json_encode(array()));

			EasyTransac\Core\Services::getInstance()->provideAPIKey($this->get_option('api_key'));
			$customer = (new EasyTransac\Entities\Customer())->setClientId($clientId);

			$request = new EasyTransac\Requests\CreditCardsList();
			$response = $request->execute($customer);

			if ($response->isSuccess()) {
				$buffer = array();
				foreach ($response->getContent()->getCreditCards() as $cc) {
					/* @var $cc EasyTransac\Entities\CreditCard */
					$year = substr($cc->getYear(), -2, 2);
					$buffer[] = array('Alias' => $cc->getAlias(), 'CardNumber' => $cc->getNumber(), 'Month' => $cc->getMonth(), 'Year' => $year);
				}
				$output = array('status' => !empty($buffer), 'packet' => $buffer);
				echo json_encode($output);
			}
		}

		/**
		* Debug function.
		*/
		function _debug($var) {
			file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'dump', $var);
		}

		/**
		* EasyTransac's callback handler // OneClick handler.
		*
		* Example: http://yoursite.com/wc-api/easytransac
		*/
		function check_callback_response() {
			EeasytransacLocalLogger::getInstance()->setActive($this->get_option('debug_mode')=='yes');
			EeasytransacLocalLogger::getInstance()->setFilePath($this->log_folder);
			EeasytransacLocalLogger::getInstance()->setLogName($this->log_file);

			// OneClick handlers.
			if (isset($_GET['listcards'])) {
				$this->listcards();
				die;
			}
			$received_data = array_map('stripslashes_deep', $_POST);

			$api_key = $this->get_option('api_key');
			if (empty($api_key)) {
				header('Location: ' . home_url('/'));
				exit;
			}

			$is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

			if(isset($received_data['data']))
				unset($received_data['data']);
			
			EeasytransacLocalLogger::getInstance()->write('Received POST: ' . var_export($received_data, true));
			
			if($is_https || (!$is_https && !empty($received_data))) {
				// FIX : HTTPS return or notification + HTTP api call
				try {
					$response = \EasyTransac\Core\PaymentNotification::getContent($received_data, $api_key);

					if(!$response) throw new Exception ('empty response');
				}
				catch (Exception $exc) {
					// Log error
					EeasytransacLocalLogger::getInstance()->write('Payment error: ' . $exc->getCode() . ' (' . $exc->getMessage().') ');

					error_log('EasyTransac error: ' . $exc->getMessage().' debug: '.$this->get_option('debug_mode'));
					header('Location: ' . home_url('/'));
					die;
				}
			}

			// On non-HTTPS sites, simply redirects and wait for the notification the update the status.
			if (empty($received_data) && !$is_https) {
				// FIX : On HTTP sites received_data must be empty or its the API call.
				header('Location: ' . $this->get_return_url());
				exit;
			}

			if (empty($received_data)) {
				header('Location: ' . home_url('/'));
				exit;
			}

			$notificationMessages = [];

			$invalidOrderIdFormat = false;
			
			// Bank transfer notification
			if( $response->getOperationType() == 'credit' ){

				$invalidOrderIdFormat = true;

				// Extract possible order id from description.
				// $text = $response->getDescription();// TODO SDK caveat
				$text = '';

				if(!empty($received_data['Description'])){
					$text = $received_data['Description'];
				}

				if(empty($text)){
					error_log('EasyTransac debug: error : missing description for credit decode');
				}

				preg_match_all("/[0-9]+/", $text, $matches);

				if(!empty($matches)){
					$matches = end($matches);
				}

				foreach($matches as $possible_id){
					try {
						$possible_id = intval($possible_id);

						$order = new WC_Order($possible_id);

						if($order && $order->get_total() > 0){

							// Check amount match.

							if($response->getAmount() == $order->get_total()){
								$invalidOrderIdFormat = false;
								$response->setOrderId($possible_id);

								if($response->getOrderId() != $possible_id){
									error_log('EasyTransac debug: error : set id doesnt match the new id: '.$response->getOrderId().' != '.$possible_id);
								}

								break;
							}
						}
					} catch (\Throwable $th) {
					}
				}
			}elseif(preg_match('/ /', $response->getOrderId())){
				// $invalidOrderIdFormat = true;
				error_log('EasyTransac debug: invalid order id containing a space format that is not a credit type'.$response->getOrderId());
			}

			$order_id_info = $response->getOrderId();
			if($response->getOperationType() == 'credit' && !empty($received_data['Description'])){
				$order_id_info = $received_data['Description'];
			}
			if($invalidOrderIdFormat || ! ($order = new WC_Order($response->getOrderId())) || 0)
			{
				$notificationMessages[] =  
					sprintf('La commande "%s" de %s EUR pour laquelle un %s a été reçu sur EasyTransac n\'a pas été trouvée.',
						$order_id_info, 
						$response->getAmount(),
						$response->getOperationType() === 'payment' ? 'paiement' : 'virement'
					);

				$errMsg = 'EasyTransac: Order ID not found: '.$order_id_info;
				error_log($errMsg);
				EeasytransacLocalLogger::getInstance()->write('Order ID missing: '. $errMsg);
			}

			if (function_exists('wcs_order_contains_subscription') 
				&& wcs_order_contains_subscription($order)
				){

				# Subscription payment handling.
				EeasytransacLocalLogger::getInstance()->write(
					'Subscription payment notification for Order: '. $order->id);

				$subscriptions_ids = wcs_get_subscriptions_for_order( 
										$order, ['order_type' => 'any']);

				$found_subscription = array_filter($subscriptions_ids, 
					function($subscription) use($order){
						return $subscription->order->id == $order->id;
				});

				if(empty($found_subscription)){
					$errMsg = 'Unknown subscription.';
					die($errMsg);
				}

				$subscription = array_shift($found_subscription);

				if ( ! is_object( $subscription ) ||
				     ! is_a( $subscription, 'WC_Subscription' ) ) {
					$errMsg = 'Invalid subscription.';
					die($errMsg);
				}

				# If pending goes through normal process.
				if( ! $subscription->has_status( 'pending' )){

					if ( ! $subscription->has_status( 'active' ) ) {
						$errMsg = 'The subscription for the payment notification is expired.';
						error_log('EasyTransac: '.$errMsg);
						EeasytransacLocalLogger::getInstance()->write($errMsg);
						die($errMsg);
					}
					
					$related = $subscription->get_related_orders('ids', ['parent', 'renewal'] );

					$found_order_to_update = null;

					foreach ($related as $related_id) {
						if( ! ($related_order = new WC_Order($related_id))){
							continue;
						}
						$related_order_tid = get_post_meta($related_id, 'ET_Tid', true);
						if($related_order_tid == $response->getTid()
							&& ( $related_order->get_status() == 'processing' 
								|| $response->getStatus() == $related_order->get_status())
						){
							// $errMsg = 'Duplicate subscription Tid received.';
							header('Location: ' . $this->get_return_url($order));
							return;
							// error_log('EasyTransac: '.$errMsg);
							// EeasytransacLocalLogger::getInstance()->write('Subscription payment: '. $errMsg);
							// die($errMsg);

						}elseif($related_order_tid == $response->getTid()
								&& $related_order->get_status() != 'processing' 
								&& $response->getStatus() != $related_order->get_status()){
						
							# Continue to order update.
							$found_order_to_update = $related_order;
						}
					}
					unset($related);
					unset($related_id);

					if(is_null($found_order_to_update)){

						# Create renewal order.
						$renewal_order = wcs_create_renewal_order( $subscription );
						$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
						$renewal_order->set_payment_method( $available_gateways['easytransac'] );
	
						switch ($response->getStatus()) {
							case 'failed':
								EeasytransacLocalLogger::getInstance()->write(
									'Subscription payment error: ' 
									. $response->getError() . ' - '
									. $response->getMessage());
								$renewal_order->update_status('failed', $response->getMessage());
								wc_add_notice(__('Payment error:', 'easytransac_woocommerce') . $response->getMessage(), 'error');
							break;
							
							case 'captured':
								$renewal_order->payment_complete( $response->getTid() );
								// Empty cart
								global $woocommerce;
								$woocommerce->cart->empty_cart();
							break;
							
							case 'pending':
								// Nothing to do
							break;
							
							case 'refunded':
								// $renewal_order->update_status('refunded', $response->getMessage());
							break;
						}
	
						update_post_meta($renewal_order->get_id(), 'ET_Tid', $response->getTid());
						$renewal_order->save();
	
						# EMS response.
						die('Api payment status received');
					}else{
						# Continue to order update.
						$order = $found_order_to_update;
					}

				}
				
			}elseif($response->getAmount() != $order->get_total() || 0){
				$notificationMessages[] =  
				sprintf('La commande "%s" de %s EUR ne correspond pas au %s de %s EUR reçu par EasyTransac.',
					$order_id_info, 
					$order->get_total(),
					$response->getOperationType() === 'payment' ? 'paiement' : 'virement',
					$response->getAmount()
				);

				$order->add_order_note( end($notificationMessages) );

				$errMsg = 'EasyTransac: amounts mismatch for order: '.$order_id_info;
				error_log($errMsg);
				EeasytransacLocalLogger::getInstance()->write('Amounts mismatch: '. $errMsg);
			}

			if(!empty($notificationMessages)){

				if(empty($this->get_option('notifemail'))){
					die('Integrity error but no notification mail set.');
				}
				if(!isset($_GET['wc-api'])){
					// E-mail notification triggered by EMS only.
					$subject = 'EasyTransac notification';
					$message = implode("\n", $notificationMessages);
					$emails = preg_split('/[,;]/', $this->get_option('notifemail'));
					$emails = array_filter($emails);
					
					foreach ($emails as $destEmail) {
						$destEmail = trim($destEmail);
						if(filter_var($destEmail, FILTER_VALIDATE_EMAIL)){
							wp_mail( $destEmail, $subject, $message );
						}
					}
					die('Order missing or amount mismatch. Notification sent.');
				}
				header('Location: ' . $this->get_return_url());
				exit;
			}


			if(!isset($_GET['wc-api']) && $order->get_status() == 'processing'){
				// EMS response.
				die('Order status already processing no status change');
			}

			// Save transaction ID
			
			if($order->get_status() != 'processing'){
				// Not changing processing status.
				update_post_meta($response->getOrderId(), 'ET_Tid', $response->getTid());
				switch ($response->getStatus()) {
					case 'failed':
						EeasytransacLocalLogger::getInstance()->write('Payment error: ' . $response->getError() . ' - ' . $response->getMessage());
						$order->update_status('failed', $response->getMessage());
						wc_add_notice(__('Payment error:', 'easytransac_woocommerce') . $response->getMessage(), 'error');
					break;
					
					case 'captured':
					// Saves ClientId
					if($response->getClient())
						add_user_meta($order->get_user_id(), 'easytransac-clientid', $response->getClient()->getId());
					$order->payment_complete();
					// Empty cart
					global $woocommerce;
					$woocommerce->cart->empty_cart();
					break;
					
					case 'pending':
						// Nothing to do
					break;
					
					case 'refunded':
						$order->update_status('refunded', $response->getMessage());
					break;
				}
			}
			if(!isset($_GET['wc-api'])){
				// EMS response.
				die('Order status received');
			}

			header('Location: ' . $this->get_return_url($order));
		}

		/**
		* Process EasyTransac response and saves order only used by oneclick response yet.
		*
		* @global type $woocommerce
		* @param EasyTransac\Entities\DoneTransaction $received_data
		*
		* @todo Use in check_callback_response() which is payment-page-logic only.
		*/
		function process_response($received_data) {
			$order = new WC_Order($received_data);

			if($order->get_status() == 'processing'){
				return;
			}
			// Saves transaction ID in the order object.
			update_post_meta($received_data->getOrderId(), 'ET_Tid', $received_data->getTid());

			switch ($received_data->getStatus()) {
				case 'failed':
					$order->update_status('failed', $received_data->getMessage());
				break;

				case 'captured':
					add_user_meta($order->get_user_id(), 'easytransac-clientid', $received_data->getClient()->getId());
					$order->payment_complete();
					// Empty cart
					global $woocommerce;
					$woocommerce->cart->empty_cart();
				break;

				case 'pending':
					// Waiting
				break;

				case 'refunded':
					$order->update_status('refunded', $received_data->getMessage());
				break;
			}
		}

		/**
		* Refund process
		*
		* @param int $order_id
		* @param float $amount
		* @param string $reason
		* @return bool|WP_Error True or false based on success, or a WP_Error object.
		*
		* @deprecated since version 1.3 because EasyTransac API doesn't support partial refund nor WooCommerce supports full refund only.
		*/
		public function process_refund($order_id, $amount = null, $reason = '')
		{
			EeasytransacLocalLogger::getInstance()->setActive($this->get_option('debug_mode')=='yes');
			EeasytransacLocalLogger::getInstance()->setFilePath($this->log_folder);
			EeasytransacLocalLogger::getInstance()->setLogName($this->log_file);

			$api_key = $this->get_option('api_key');
			EasyTransac\Core\Services::getInstance()->provideAPIKey($api_key);

			$order = wc_get_order($order_id);

			// if ($order->get_total() != $amount) {
			// 	return new WP_Error('easytransac-refunds', __('EasyTransac support full refund only.', 'easytransac_woocommerce'));
			// }
			$refund = (new \EasyTransac\Entities\Refund)
					->setTid(get_post_meta($order_id, 'ET_Tid', true))
					->setAmount(100 * $amount);

			$request = (new EasyTransac\Requests\PaymentRefund);
			$response = $request->execute($refund);

			if (empty($response)) {
				return new WP_Error('easytransac-refunds', __('Empty Response', 'easytransac_woocommerce'));
			}
			else if (!$response->isSuccess()) {
				return new WP_Error('easytransac-refunds', $response->getErrorMessage());
			}
			else {
				return true;
			}
		}

		/**
		* Get gateway icon.
		* @return string
		*/
		public function get_icon() {
			$icon_url = plugin_dir_url(__FILE__) . '/includes/icon.png';
			$icon_html = "<script type=\"text/javascript\">function usingGateway(){\"easytransac\"==jQuery('form[name=\"checkout\"] input[name=\"payment_method\"]:checked').val()?document.getElementById(\"easytransac-icon\").style.visibility=\"visible\":document.getElementById(\"easytransac-icon\").style.visibility=\"hidden\"}jQuery(function(){jQuery(\"body\").on(\"updated_checkout\",function(){usingGateway(),jQuery('input[name=\"payment_method\"]').change(function(){usingGateway()})})});</script>";
			$icon_html .= '<img id="easytransac-icon" src="' . esc_attr($icon_url) . '" alt="' . esc_attr__('EasyTransac', 'easytransac_woocommerce') . '" />';
			// Injects OneClick if enabled.
			$oneclick = $this->get_option('oneclick');
			if($oneclick == 'yes') {
				$icon_html .= '<script type="text/javascript">var loadingMsg = "';
				$icon_html .= __('Loading in progress...', 'easytransac_woocommerce');
				$icon_html .= '";var chooseCard = "';
				$icon_html .= __('Choose a card : ', 'easytransac_woocommerce');
				$icon_html .= '"; var payNow = "';
				$icon_html .= __('Pay using this credit card', 'easytransac_woocommerce') . '";</script>';
				$icon_html .= '<script type="text/javascript" src="' . plugin_dir_url(__FILE__) . '/includes/oneclick.js"></script>';
			}
			return apply_filters('woocommerce_gateway_icon', $icon_html, $this->id);
		}

		public function iso2to3($iso2){
			$countries = array(
				"AF" => "AFG",
				"AL" => "ALB",
				"DZ" => "DZA",
				"AS" => "ASM",
				"AD" => "AND",
				"AO" => "AGO",
				"AI" => "AIA",
				"AQ" => "ATA",
				"AG" => "ATG",
				"AR" => "ARG",
				"AM" => "ARM",
				"AX" => "ALA",
				"AW" => "ABW",
				"AU" => "AUS",
				"AT" => "AUT",
				"AZ" => "AZE",
				"BQ" => "BES",
				"BS" => "BHS",
				"BH" => "BHR",
				"BD" => "BGD",
				"BB" => "BRB",
				"BY" => "BLR",
				"BE" => "BEL",
				"BZ" => "BLZ",
				"BL" => "BLM",
				"BJ" => "BEN",
				"BM" => "BMU",
				"BT" => "BTN",
				"BO" => "BOL",
				"BA" => "BIH",
				"BW" => "BWA",
				"BV" => "BVT",
				"BR" => "BRA",
				"IO" => "IOT",
				"BN" => "BRN",
				"BG" => "BGR",
				"BF" => "BFA",
				"BI" => "BDI",
				"KH" => "KHM",
				"CM" => "CMR",
				"CA" => "CAN",
				"CV" => "CPV",
				"KY" => "CYM",
				"CF" => "CAF",
				"TD" => "TCD",
				"CL" => "CHL",
				"CN" => "CHN",
				"CX" => "CXR",
				"CC" => "CCK",
				"CO" => "COL",
				"KM" => "COM",
				"CG" => "COG",
				"CD" => "COD",
				"CK" => "COK",
				"CR" => "CRI",
				"CI" => "CIV",
				"HR" => "HRV",
				"CU" => "CUB",
				"CY" => "CYP",
				"CZ" => "CZE",
				"DK" => "DNK",
				"DJ" => "DJI",
				"DM" => "DMA",
				"DO" => "DOM",
				"TP" => "TMP",
				"EC" => "ECU",
				"EG" => "EGY",
				"SV" => "SLV",
				"GQ" => "GNQ",
				"ER" => "ERI",
				"EE" => "EST",
				"ET" => "ETH",
				"FK" => "FLK",
				"FO" => "FRO",
				"FJ" => "FJI",
				"FI" => "FIN",
				"FR" => "FRA",
				"FX" => "FXX",
				"GF" => "GUF",
				"PF" => "PYF",
				"TF" => "ATF",
				"GA" => "GAB",
				"GM" => "GMB",
				"GE" => "GEO",
				"DE" => "DEU",
				"GG" => "GGY",
				"GH" => "GHA",
				"GI" => "GIB",
				"GR" => "GRC",
				"GL" => "GRL",
				"GD" => "GRD",
				"GP" => "GLP",
				"GU" => "GUM",
				"GT" => "GTM",
				"GN" => "GIN",
				"GW" => "GNB",
				"GY" => "GUY",
				"HT" => "HTI",
				"HM" => "HMD",
				"VA" => "VAT",
				"HN" => "HND",
				"HK" => "HKG",
				"HU" => "HUN",
				"IM" => "IMN",
				"IS" => "ISL",
				"IN" => "IND",
				"ID" => "IDN",
				"IR" => "IRN",
				"IQ" => "IRQ",
				"IE" => "IRL",
				"IL" => "ISR",
				"IT" => "ITA",
				"JM" => "JAM",
				"JE" => "JEY",
				"JP" => "JPN",
				"JO" => "JOR",
				"KZ" => "KAZ",
				"KE" => "KEN",
				"KI" => "KIR",
				"KP" => "PRK",
				"KR" => "KOR",
				"KW" => "KWT",
				"KG" => "KGZ",
				"LA" => "LAO",
				"LV" => "LVA",
				"LB" => "LBN",
				"LS" => "LSO",
				"LR" => "LBR",
				"LY" => "LBY",
				"LI" => "LIE",
				"LT" => "LTU",
				"LU" => "LUX",
				"MO" => "MAC",
				"MF" => "MAF",
				"MK" => "MKD",
				"MG" => "MDG",
				"MW" => "MWI",
				"MY" => "MYS",
				"MV" => "MDV",
				"ML" => "MLI",
				"MT" => "MLT",
				"MH" => "MHL",
				"MQ" => "MTQ",
				"MR" => "MRT",
				"MU" => "MUS",
				"YT" => "MYT",
				"MX" => "MEX",
				"FM" => "FSM",
				"MD" => "MDA",
				"MC" => "MCO",
				"MN" => "MNG",
				"ME" => "MNE",
				"MS" => "MSR",
				"MA" => "MAR",
				"MZ" => "MOZ",
				"MM" => "MMR",
				"NA" => "NAM",
				"NR" => "NRU",
				"NP" => "NPL",
				"NL" => "NLD",
				"AN" => "ANT",
				"NC" => "NCL",
				"NZ" => "NZL",
				"NI" => "NIC",
				"NE" => "NER",
				"NG" => "NGA",
				"NU" => "NIU",
				"NF" => "NFK",
				"MP" => "MNP",
				"NO" => "NOR",
				"OM" => "OMN",
				"PK" => "PAK",
				"PW" => "PLW",
				"PA" => "PAN",
				"PG" => "PNG",
				"PY" => "PRY",
				"PE" => "PER",
				"PH" => "PHL",
				"PN" => "PCN",
				"PL" => "POL",
				"PS" => "PSE",
				"PT" => "PRT",
				"PR" => "PRI",
				"QA" => "QAT",
				"QZ" => "QZZ",
				"RE" => "REU",
				"RO" => "ROU",
				"RU" => "RUS",
				"RW" => "RWA",
				"KN" => "KNA",
				"LC" => "LCA",
				"VC" => "VCT",
				"WS" => "WSM",
				"SM" => "SMR",
				"ST" => "STP",
				"SA" => "SAU",
				"SN" => "SEN",
				"RS" => "SRB",
				"SC" => "SYC",
				"SL" => "SLE",
				"SG" => "SGP",
				"SK" => "SVK",
				"SI" => "SVN",
				"SB" => "SLB",
				"SO" => "SOM",
				"ZA" => "ZAF",
				"SS" => "SSD",
				"GS" => "SGS",
				"ES" => "ESP",
				"LK" => "LKA",
				"SH" => "SHN",
				"PM" => "SPM",
				"SD" => "SDN",
				"SR" => "SUR",
				"SX" => "SXM",
				"SJ" => "SJM",
				"SZ" => "SWZ",
				"SE" => "SWE",
				"CH" => "CHE",
				"SY" => "SYR",
				"TW" => "TWN",
				"TJ" => "TJK",
				"TZ" => "TZA",
				"TH" => "THA",
				"TG" => "TGO",
				"TK" => "TKL",
				"TL" => "TLS",
				"TO" => "TON",
				"TT" => "TTO",
				"TN" => "TUN",
				"TR" => "TUR",
				"TM" => "TKM",
				"TC" => "TCA",
				"TV" => "TUV",
				"UG" => "UGA",
				"UA" => "UKR",
				"AE" => "ARE",
				"GB" => "GBR",
				"US" => "USA",
				"UM" => "UMI",
				"UY" => "URY",
				"UZ" => "UZB",
				"VU" => "VUT",
				"VE" => "VEN",
				"VN" => "VNM",
				"VG" => "VGB",
				"VI" => "VIR",
				"WF" => "WLF",
				"EH" => "ESH",
				"YE" => "YEM",
				"ZM" => "ZMB",
				"ZW" => "ZWE",
				"CW" => "CUW",
				"KS" => "RKS",
				"ZZ" => "ZZZ"
			);
			if(isset($countries[$iso2])){
				return $countries[$iso2];
			}else{
				return 'FRA';
			}
		}
	}
}

// Load plugin
add_action('plugins_loaded', 'init_easytransac_gateway');

function add_easytransac_gateway($methods) {
	$methods[] = 'EasyTransacGateway';
	return $methods;
}

// Register gateway in WooCommerce
add_filter('woocommerce_payment_gateways', 'add_easytransac_gateway');

// Internationalization
load_plugin_textdomain('easytransac_woocommerce', false, dirname(plugin_basename(__FILE__)) . DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR.'languages'.DIRECTORY_SEPARATOR);

// Settings quick link.
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
function add_action_links ( $links ) {
	$mylinks = array(
	'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=easytransacgateway' ) . '">'.__('Settings').'</a>',
	);
   return array_merge( $links, $mylinks );
}

// Stock level reduce option.
// function processing_easytransac_stock_not_reduced( $reduce_stock, $order ) {
//     if ($order->get_payment_method() == 'easytransac' ) {
// 		if(($options = get_option('woocommerce_easytransac_settings'))){
// 			if(isset($options['disable_stock']) && $options['disable_stock'] == 'yes'){
// 				return false;
// 			}
// 		}
//     }
//     return $reduce_stock;
// }
// add_filter('woocommerce_can_reduce_order_stock', 'processing_easytransac_stock_not_reduced', 20, 2 );

