<?php

use Automattic\WooCommerce\Admin\Overrides\Order;

if (!defined('ABSPATH')) {
	exit;
}

function mypos_check_pending_payment_orders_statuses()
{
	(new WC_Gateway_Mypos)->check_pending_payment_orders_statuses();
}

add_action('mypos_check_payment_status', 'mypos_check_pending_payment_orders_statuses');

/**
 * WC_Gateway_Mypos class
 *
 * @author myPOS Europe LTD
 * @package WooCommerce Mypos Payments Gateway
 * @since 1.3.30
 */
class WC_Gateway_Mypos extends WC_Payment_Gateway
{
	public const PAYMENT_METHOD_CARD = '1';
	public const PAYMENT_METHOD_IDEAL = '2';
	public const PAYMENT_METHOD_BOTH = '3';
	public const PENDING_PAYMENT_DB_TABLE_NAME = 'mypos_pending_payments_schedule';
	public const WAITING_CONFIRMATION_PERIOD_HOURS = '8';
	public const WAITING_CONFIRMATION_DEADLINE_HOURS = '24'; //Hours after the order change his status as canceled

	public static $log;
	protected $line_items;
	public static $log_enabled;
	public $test;
	public $debug;
	public $version;
	public $sid;
	public $wallet_number;
	public $private_key;
	public $public_certificate;
	public $keyindex;
	public $url;
	public $paymentParametersRequired;
	public $paymentMethod;
	public $merchant_wallet_number;
	public $test_prefix;
	public $merchant_send_money_reason;
	public $hasDeadlinePendingOrder;
	public $notify_url;

	/**
	 * Logging method
	 * @param string $message
	 */
	public static function log(string $message)
	{
		if (self::$log_enabled) {
			if (empty(self::$log)) {
				self::$log = new WC_Logger();
			}
			self::$log->add('mypos_virtual', $message);
		}
	}

	public function __construct()
	{
		$this->id = 'mypos_virtual';
		$this->icon = '';
		$this->has_fields = false;
		$this->supports = array(
			'products',
			'refunds'
		);
		$this->method_title = _x('myPOS Checkout', 'myPOS payment method', 'woocommerce-gateway-mypos');
		$this->method_description = __(
			'myPOS Checkout works by sending customers to myPOS Checkout where they can enter their payment information.<br/>To use this payment option you need to <a href="https://mypos.com/en/register/" target="_blank">sign up</a> for a myPOS account.',
			'woocommerce-gateway-mypos'
		);

		$this->version = "1.4";

		//Load fields and settings.
		$this->init_form_fields();
		$this->init_settings();

		//Define user set variables.
		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');
		$this->test = 'yes' === $this->get_option('test', 'no');
		$this->debug = 'yes' === $this->get_option('debug', 'no');

		$test_prefix = $this->get_option('test_prefix');
		if (empty($test_prefix)) {
			if (empty($this->settings)) {
				$this->init_settings();
			}

			$this->settings['test_prefix'] = uniqid() . '_';
			update_option(
				$this->get_option_key(),
				apply_filters('woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings),
				'yes'
			);
		}

		$this->test_prefix = $this->get_option('test_prefix');

		$this->force_tld();

		if (!$this->test) {
			$packageData = json_decode(base64_decode($this->get_option('production_package')), true);

			$this->sid = !empty($packageData['sid']) ? $packageData['sid'] : $this->get_option('production_sid');
			$this->wallet_number = !empty($packageData['cn']) ? $packageData['cn'] : $this->get_option(
				'production_wallet_number'
			);
			$this->private_key = !empty($packageData['pk']) ? $packageData['pk'] : $this->get_option(
				'production_private_key'
			);
			$this->public_certificate = !empty($packageData['pc']) ? $packageData['pc'] : $this->get_option(
				'production_public_certificate'
			);
			$this->keyindex = !empty($packageData['idx']) ? $packageData['idx'] : $this->get_option(
				'production_keyindex'
			);
			$this->url = $this->get_option('production_url');
			$this->paymentParametersRequired = $this->get_option('production_ppr');
			$this->paymentMethod = $this->get_option('production_payment_method');
		} else {
			$packageData = json_decode(base64_decode($this->get_option('developer_package')), true);

			$this->sid = !empty($packageData['sid']) ? $packageData['sid'] : $this->get_option('developer_sid');
			$this->wallet_number = !empty($packageData['cn']) ? $packageData['cn'] : $this->get_option(
				'developer_wallet_number'
			);
			$this->private_key = !empty($packageData['pk']) ? $packageData['pk'] : $this->get_option(
				'developer_private_key'
			);
			$this->public_certificate = !empty($packageData['pc']) ? $packageData['pc'] : $this->get_option(
				'developer_public_certificate'
			);
			$this->keyindex = !empty($packageData['idx']) ? $packageData['idx'] : $this->get_option(
				'developer_keyindex'
			);
			$this->url = $this->get_option('developer_url');
			$this->paymentParametersRequired = $this->get_option('developer_ppr');
			$this->paymentMethod = $this->get_option('developer_payment_method');
		}

		$this->merchant_wallet_number = $this->get_option('merchant_wallet_number');
		$this->merchant_send_money_reason = $this->get_option('merchant_send_money_reason');
		$this->hasDeadlinePendingOrder = 'yes' === $this->get_option('use_deadline_for_orders', 'no');
		$this->notify_url = str_replace('http:', 'https:', home_url('?wc-api=' . strtolower(__CLASS__)));

		self::$log_enabled = $this->debug;

		$this->init_cron_hook();

		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_api_' . strtolower(__CLASS__), array($this, 'check_ipc_response'));
		add_action('woocommerce_order_item_add_action_buttons', array($this, 'check_payment_status_view'));
		add_action('verify_notify_post', array($this, 'verify_notify_post'));


		if (!empty($this->merchant_wallet_number)) {
			add_action('woocommerce_before_order_object_save', array($this, 'capture_payment_complete'));
		}

		if (!$this->is_valid_for_use()) {
			$this->enabled = 'no';
		} else {
			add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
		}

		self::init_db();
	}

	public function init_form_fields()
	{
		$this->form_fields = include('settings-ipc.php');
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment($order_id)
	{
		$order = wc_get_order($order_id);

		return array(
			'result' => 'success',
			'redirect' => $order->get_checkout_payment_url(true),
		);
	}

	/**
	 * Checks if the store is valid for using plugin
	 * @return bool
	 */
	public function is_valid_for_use()
	{
		return in_array(
			get_woocommerce_currency(),
			apply_filters(
				'woocommerce_ipc_supported_currencies',
				array(
					'BGN',
					'USD',
					'EUR',
					'GBP',
					'CHF',
					'JPY',
					'RON',
					'HRK',
					'NOK',
					'SEK',
					'CZK',
					'HUF',
					'PLN',
					'DKK',
					'ISK'
				)
			)
		);
	}

	/**
	 * Checks if the store is valid for iDeal payments
	 * @return bool
	 */
	public function is_valid_for_ideal()
	{
		return in_array(
			get_woocommerce_currency(),
			apply_filters('woocommerce_ipc_supported_currencies', array('EUR'))
		);
	}

	/**
	 * @return void
	 */
	public function force_tld()
	{
		$productionUrl = $this->get_option('production_url');

		if (empty($productionUrl) || false !== stripos((parse_url($productionUrl)['host']), 'mypos.eu')) {
			$this->update_option('production_url', 'https://mypos.com/vmp/checkout');
		}

		$developerUrl = $this->get_option('developer_url');

		if (empty($developerUrl) || false !== stripos((parse_url($developerUrl)['host']), 'mypos.eu')) {
			$this->update_option('developer_url', 'https://mypos.com/vmp/checkout-test');
		}
	}

	public static function init_db()
	{
		global $wpdb;
		$pendingPaymentConfirmTable = $wpdb->prefix . self::PENDING_PAYMENT_DB_TABLE_NAME;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if ($wpdb->get_var("show tables like '$pendingPaymentConfirmTable'") !== $pendingPaymentConfirmTable) {
			$sql = "
                CREATE TABLE " . $pendingPaymentConfirmTable . " (
                id int(11) NOT NULL AUTO_INCREMENT,
                order_id varchar(255) NOT NULL,
                last_check int NULL,
                expired_time int NOT NULL,
                test_environment boolean NOT NULL,
                UNIQUE KEY id (id)
            );";
			dbDelta($sql);
		}
	}

	public function init_cron_hook()
	{
		$nextScheduledTime = wp_next_scheduled('mypos_check_payment_status');
		if (!$nextScheduledTime) {
			wp_schedule_event(time(), 'hourly', 'mypos_check_payment_status');
		}
	}

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 */
	public function admin_options()
	{
		add_action('verify_notify', array($this, 'verify_notify_view'));
		if (!has_action('verify_notify', 'verify_notify_view')) {
			do_action('verify_notify', array($this, 'verify_notify_view'));
		}
		do_action('verify_notify_post', array($this, 'verify_notify_post'));
		if ($this->is_valid_for_use()) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error"><p><strong><?php
						_e('Gateway Disabled', 'woocommerce'); ?></strong>: <?php
					_e('myPOS Checkout does not support your store currency.', 'woocommerce'); ?></p></div>
			<?php
		}
	}

	public function get_source()
	{
		return 'sc_wp_woocommerce 1.3.30 ' . PHP_VERSION . ' ' . get_bloginfo('version');
	}

	public function receipt_page($order)
	{
		echo $this->generate_ipc_form($order);
	}

	/**
	 * Check for valid ipc response
	 **/
	function check_ipc_response()
	{
		self::log("Notify url request.");

		global $woocommerce;

		$post = $_POST;

		if ($this->is_valid_signature($post)) {
			if ($post['IPCmethod'] === 'IPCSignatureVerify') {
				echo 'OK';
				exit;
			}

			if ($this->test) {
				$post['OrderID'] = str_replace($this->test_prefix, '', $post['OrderID']);
			}

			$order = new WC_Order($post['OrderID']);

			if ($post['IPCmethod'] === 'IPCPurchaseNotify') {
				self::log("IPCPurchaseNotify request for order: " . $order->get_order_number());
				$order->payment_complete($post['IPC_Trnref']);
				$order->add_order_note(
					'Gateway has authorized payment.<br/>Transaction Number: ' . $post['IPC_Trnref']
				);
				$woocommerce->cart->empty_cart();
				$this->remove_from_pending_payments_schedule($order->get_id());
				ob_get_clean();
				echo 'OK';
				exit;
			}

			if ($post['IPCmethod'] === 'IPCPurchaseRollback') {
				self::log("IPCPurchaseRollback request for order: " . $order->get_order_number());

				$order->update_status('failed');
				$order->add_order_note('Gateway has declined payment.');
				$woocommerce->cart->empty_cart();
				ob_get_clean();
				echo 'OK';
				exit;
			}

			if ($post['IPCmethod'] === 'IPCPurchaseCancel') {
				self::log("IPCPurchaseCancel request for order: " . $order->get_order_number());
				$this->remove_from_pending_payments_schedule($order->get_id());
				$order->update_status('cancelled');
				$order->add_order_note('User canceled the order.');

				$redirect_url = $order->get_cancel_order_url();
				wp_redirect($redirect_url);
				exit;
			}

			if ($post['IPCmethod'] === 'IPCPurchaseOK') {
				self::log("IPCPurchaseOK request for order: " . $order->get_order_number());
				$woocommerce->cart->empty_cart();

				$redirect_url = $order->get_checkout_order_received_url();
				wp_redirect($redirect_url);
				exit;
			}

			echo 'INVALID METHOD';
			exit;
		}

		echo 'INVALID SIGNATURE';
		exit;
	}

	/**
	 * Generate myPOS Checkout form
	 * @param $order_id
	 * @return string
	 * @throws Exception
	 */
	public function generate_ipc_form($order_id)
	{
		global $woocommerce;
		$order = new WC_Order($order_id);
		$this->add_to_pending_payments_schedule($order->get_id());

		$post = $this->create_post($order);
		$post_array = array();

		foreach ($post as $key => $value) {
			$value = htmlspecialchars($value, ENT_QUOTES);
			$post_array[] = "<input type='hidden' name='$key' value='$value'/>";
		}

		self::log("Show payment form for order: " . $order_id);

		if (!empty($post['PaymentMethod']) && $post['PaymentMethod'] === '2') {
			$woocommerce->cart->empty_cart();
		}

		return '<div style="position: fixed; top: 0; left: 0; bottom: 0; right: 0; z-index: 9999; width: 100%; height: 100%; background-color: white;"></div>
                <style>* { display: none; }</style><form action="' . $this->url . '" method="post" name="mypos_virtual">
               ' . implode('', $post_array) . '
                    <button type="submit">' . __('Pay', 'woocommerce') . '</button>
                </form>
                <script>document.mypos_virtual.submit();</script>';
	}

	public function create_post($order)
	{
		self::log("Create post data for order: " . $order->get_order_number());

		$post = array();

		$countries = include("countries.php");

		$post['IPCmethod'] = 'IPCPurchase';
		$post['IPCVersion'] = $this->version;
		$post['IPCLanguage'] = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : substr(get_locale(), 0, 2);
		$post['WalletNumber'] = $this->wallet_number;
		$post['SID'] = $this->sid;
		$post['keyindex'] = $this->keyindex;
		$post['Source'] = $this->get_source();

		$post['Amount'] = number_format($order->get_total(), 2, '.', '');
		$post['Currency'] = $order->get_currency();
		$post['OrderID'] = ($this->test ? $this->test_prefix : '') . $order->get_id();
		$post['URL_OK'] = $this->notify_url;
		$post['URL_CANCEL'] = $this->notify_url;
		$post['URL_Notify'] = $this->notify_url;
		$post['CustomerIP'] = $_SERVER['REMOTE_ADDR'];
		$post['CustomerEmail'] = $order->get_billing_email();
		$post['CustomerFirstNames'] = $order->get_billing_first_name();
		$post['CustomerFamilyName'] = $order->get_billing_last_name();
		$post['CustomerCountry'] = $countries[$order->get_billing_country()];
		$post['CustomerCity'] = $order->get_billing_city();
		$post['CustomerZIPCode'] = $order->get_billing_postcode();
		$post['CustomerAddress'] = $order->get_billing_address_1();
		$post['CustomerPhone'] = $order->get_billing_phone();
		$post['Note'] = 'myPOS Checkout WooCommerce Extension. Order Number: ' . $order->get_order_number();

		$post['CardTokenRequest'] = 0;
		$post['PaymentParametersRequired'] = $this->paymentParametersRequired;
		$post['PaymentMethod'] = $this->paymentMethod;

		$index = 1;

		$this->line_items = $this->get_line_item_args($order);

		while (true) {
			if (isset($this->line_items['item_name_' . $index])) {
				$post['Article_' . $index] = $this->escape_string(
					do_shortcode($this->line_items['item_name_' . $index])
				);
				$post['Quantity_' . $index] = $this->line_items['quantity_' . $index];
				$post['Price_' . $index] = $this->line_items['amount_' . $index];
				$post['Amount_' . $index] = $this->number_format(
					$this->line_items['amount_' . $index] * $this->line_items['quantity_' . $index],
					$order
				);
				$post['Currency_' . $index] = $post['Currency'];
			} else {
				break;
			}

			$index++;
		}

		if (isset($this->line_items['tax_cart']) && $this->line_items['tax_cart'] != 0) {
			$post['Article_' . $index] = 'Tax';
			$post['Quantity_' . $index] = 1;
			$post['Price_' . $index] = $this->line_items['tax_cart'];
			$post['Amount_' . $index] = $this->line_items['tax_cart'];
			$post['Currency_' . $index] = $post['Currency'];

			$index++;
		}

		if (isset($this->line_items['discount_amount_cart']) && $this->line_items['discount_amount_cart'] != 0) {
			$post['Article_' . $index] = 'Discount';
			$post['Quantity_' . $index] = 1;
			$post['Price_' . $index] = -$this->line_items['discount_amount_cart'];
			$post['Amount_' . $index] = -$this->line_items['discount_amount_cart'];
			$post['Currency_' . $index] = $post['Currency'];

			$index++;
		}

		$post['CartItems'] = $index - 1;
		$post['trp-form-language'] = $post['IPCLanguage'];

		$post['Signature'] = $this->create_signature($post);

		return $post;
	}

	/**
	 * @param $order_id
	 * @return void
	 * @throws Exception
	 */
	private function add_to_pending_payments_schedule($order_id)
	{
		global $wpdb;
		$pay_order_id = ($this->test ? $this->test_prefix : '') . $order_id;
		$mypos_table = $wpdb->prefix . self::PENDING_PAYMENT_DB_TABLE_NAME;

		if ($wpdb->get_var("show tables like '$mypos_table'") === $mypos_table) {
			$exists = $wpdb->get_var(
				"SELECT exists(SELECT * FROM " . $mypos_table
				. " WHERE order_id='" . $pay_order_id . "')"
			);

			if ('0' === $exists) {
				$confirmationDate = new DateTime();
				$period = 'PT' . self::WAITING_CONFIRMATION_PERIOD_HOURS . 'H';
				$confirmationDate->add(new DateInterval($period));

				$wpdb->insert(
					$mypos_table,
					array(
						'order_id' => $pay_order_id,
						'expired_time' => $confirmationDate->getTimestamp(),
						'test_environment' => $this->test
					)
				);
			}
		}
	}

	/**
	 * Remove order from pending status
	 *
	 * @param $order_id
	 * @return void
	 */
	private function remove_from_pending_payments_schedule($order_id)
	{
		global $wpdb;
		$mypos_table = $wpdb->prefix . self::PENDING_PAYMENT_DB_TABLE_NAME;
		if ($wpdb->get_var("show tables like '$mypos_table'") == $mypos_table) {
			$wpdb->delete(
				$mypos_table,
				array(
					'order_id' => ($this->test ? $this->test_prefix : '') . $order_id,
				)
			);
		}
	}

	/**
	 * Signature creation
	 *
	 * @param $post
	 * @return string
	 */
	private function create_signature($post)
	{
		if (array_key_exists('OrderID', $post)) {
			self::log("Create signature for order: " . $post['OrderID']);
		} else {
			self::log("Create signature (no order)");
		}

		$concData = base64_encode(implode('-', $post));
		$privKeyObj = openssl_pkey_get_private($this->private_key);
		openssl_sign($concData, $signature, $privKeyObj, OPENSSL_ALGO_SHA256);
		return base64_encode($signature);
	}

	/**
	 * Check signature validation
	 * @param $post
	 * @return bool
	 */
	public function is_valid_signature($post)
	{
		// Save signature
		if (isset($post['Signature'])) {
			$signature = $post['Signature'];
			// Remove signature from POST data array
			unset($post['Signature']);

			// Concatenate all values
			$concData = base64_encode(implode('-', $post));


			// Extract public key from certificate
			$pubKeyId = openssl_get_publickey($this->public_certificate);

			// Verify signature
			$result = openssl_verify($concData, base64_decode($signature), $pubKeyId, OPENSSL_ALGO_SHA256);

			//Free key resource
			unset($pubKeyId);

			if ($result == 1) {
				return true;
			} else {
				self::log('Invalid signature. ' . (isset($post['OrderID']) ? 'Order: ' . $post['OrderID'] : ''));
				return false;
			}
		}

		return false;
	}

	/**
	 * Can the order be refunded via mypos virtual?
	 * @param WC_Order $order
	 * @return bool
	 */
	public function can_refund_order($order)
	{
		return $order && $order->get_transaction_id();
	}

	/**
	 * Process a refund if supported
	 *
	 * @param int $order_id
	 * @param float $amount
	 * @param string $reason
	 * @return  boolean True or false based on success, or a WP_Error object
	 */
	public function process_refund($order_id, $amount = null, $reason = '')
	{
		$order = wc_get_order($order_id);

		if (!$this->can_refund_order($order)) {
			return false;
		}

		$post = $this->create_refund_data($order, $amount);

		//open connection
		$ch = curl_init($this->url);

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // Timeout on connect (2 minutes)

		//execute post
		$result = curl_exec($ch);
		curl_close($ch);

		self::log('Execute IPCRefund for order : ' . $order->get_order_number());

		// Parse xml
		$post = $this->xml_to_post($result);
		
		if ($this->is_valid_signature($post)) {
			if ($post['Status'] != 0) {
				self::log('Refund failed for order: ' . $order->get_order_number() . '. Status: ' . $post['Status']);
				$order->add_order_note(sprintf(__('Refunded failed. Status: %s', 'woocommerce'), $post['Status']));
				return false;
			} else {
				self::log('Refund succeeded for order: ' . $order->get_order_number());
				$order->add_order_note(
					sprintf(
						__('Refunded %s - Refund ID: %s', 'woocommerce'),
						$post['Amount'],
						$post['IPC_Trnref'] . '-' . time()
					)
				);
				return true;
			}
		} else {
			self::log('Refund failed for order: ' . $order->get_order_number() . '. Invalid signature.');
			$order->add_order_note(sprintf(__('Refunded failed. Invalid signature.', 'woocommerce')));
			return false;
		}
	}

	/**
	 * @param WC_Order $order
	 * @return bool
	 */
	public function is_order_expired($order)
	{
		$order_expired_timestamp = (int)self::WAITING_CONFIRMATION_DEADLINE_HOURS * 60 * 60;
		$order_created_timestamp = $order->get_date_created()->getTimestamp();
		$current_timestamp = (new DateTime())->getTimestamp();

		return $current_timestamp - $order_created_timestamp >= $order_expired_timestamp;
	}

	public function check_payment_status_view($order)
	{
		if (empty($_SESSION[$order->get_id()])) {
			echo '<button type="submit" class="button mypos-check-payment-status" value="1" id="' . $order->get_id() . '">
			Check Payment Status
		</button>';
		}
		add_action('save_post', array($this, 'check_payment_status_post'));
		$_SESSION[$order->get_id()] = true;
	}

	public function verify_notify_view()
	{
		?>
		<button type="submit" name="mypos-verify-notify" class="button button-primary mypos-verify-notify" value="1"
				style="float:right">
			Test setup
		</button>
		<?php
	}

	public function verify_notify_post()
	{
		if (array_key_exists('mypos-verify-notify', $_POST) && $_POST['mypos-verify-notify']) {
			$this->verify_notify();
		}
	}

	public function check_payment_status_post($order_id)
	{
		if (array_key_exists('mypos-check-payment-status', $_POST) && $_POST['mypos-check-payment-status']) {
			$this->check_payment_status($order_id);
		}
	}


	public function check_payment_status($order_id)
	{
		/** @var Automattic\WooCommerce\Admin\Overrides\Order $order */
		$order = wc_get_order($order_id);

		if ($this->hasDeadlinePendingOrder && $this->id === $order->get_payment_method() && $order->has_status(
				'pending'
			) && $this->is_order_expired($order)) {
			$this->remove_from_pending_payments_schedule($order->get_id());
			$order->update_status('cancelled');
			$order->add_order_note('myPOS Checkout has cancelled  the order. Reason: expired.');
		}


		if (null === $order) {
			$this->remove_from_pending_payments_schedule($order_id);
			return false;
		}

		$this->line_items = $this->get_line_item_args($order);

		$data = $this->create_check_status_data($order->get_id());

		//open connection
		$ch = curl_init($this->url);

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // Timeout on connect (2 minutes)

		//execute post
		$result = curl_exec($ch);
		curl_close($ch);

		// Parse xml
		$post = $this->xml_to_post($result);

		if ($this->is_valid_signature($post) && array_key_exists('PaymentStatus', $post)
			&& array_key_exists('Amount', $post) && $post['Amount'] == number_format($order->get_total(), 2, '.', '')
			&& array_key_exists('Currency', $post) && $post['Currency'] == $order->get_currency()) {
			switch ($post['PaymentStatus']) {
				case 1:
					if (!$order->is_paid()) {
						$order->payment_complete($post['IPC_Trnref']);
						$order->add_order_note(
							'Gateway has authorized payment.<br/>Transaction Number: ' . $post['IPC_Trnref']
						);
						$this->remove_from_pending_payments_schedule($order->get_id());
					}
					break;
				case 3:
					if ($order->get_status() !== 'failed') {
						$order->update_status('failed');
						$order->add_order_note('Gateway has authorized payment as error.');
						$this->remove_from_pending_payments_schedule($order->get_id());
					}
					break;
			}
		}
	}

	/**
	 * Verify notify - check your settings before real purchase
	 *
	 * @return void
	 */
	public function verify_notify()
	{
		$data = $this->create_verify_notify_data();

		if (!$data['Signature']) {
			echo '<div class="notice notice-warning is-dismissible">
             <p>Fill fields before doing test.</p>
         </div>';
			return;
		}
		//open connection
		$ch = curl_init($this->url);

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // Timeout on connect (2 minutes)
		//execute post
		$result = curl_exec($ch);

		curl_close($ch);

		// Parse xml
		$post = $this->xml_to_post($result);

		if ($post !== null && $this->is_valid_signature($post)) {
			echo '<div class="notice notice-success is-dismissible">
            <p>Your configuration is successfull!</p>
            </div>';
		} else {
			echo '<div class="notice notice-warning is-dismissible">
             <p>Error! Wrong settings, please change it and try again.</p>
         </div>';
		}
		unset($_POST['mypos-verify-notify']);
	}

	/**
	 * Capture when payment completed
	 *
	 * @param WC_Order $order
	 * @return WC_Order
	 */
	public function capture_payment_complete($order)
	{
		if ($order->get_payment_method() != 'mypos_virtual') {
			return $order;
		}

		if (empty($order->get_transaction_id())) {
			return $order;
		}

		$changes = $order->get_changes();

		if (empty($changes['status']) || $changes['status'] !== 'completed') {
			return $order;
		}

		$walletCapture = get_post_meta($order->get_id(), 'order_wallet_captured');

		if (empty($walletCapture) || empty(reset($walletCapture))) {
			$data = $this->create_send_money_data($order);
			$result = $this->process_curl_checkout_post($data);
			if (isset($result['Status']) && $result['Status'] == 0) {
				update_post_meta($order->get_id(), 'order_wallet_captured', $this->merchant_wallet_number);
				$order->add_order_note(
					'Money has been sent successfully to merchant with client number: ' . $this->merchant_wallet_number
				);
			} else {
				$order->set_status(
					get_post_status($order->get_id()),
					'Error occurred trying to send money to client number: ' . $this->merchant_wallet_number
				);
				throw new RuntimeException('Number: ' . $order->get_id());
			}
		} else {
			$order->add_order_note('Money already sent to merchant with client number: ' . reset($walletCapture));
		}

		return $order;
	}

	/**
	 * @return void
	 */
	public function check_pending_payment_orders_statuses()
	{
		global $wpdb;

		$mypos_table = $wpdb->prefix . self::PENDING_PAYMENT_DB_TABLE_NAME;
		if ($wpdb->get_var("show tables like '$mypos_table'") == $mypos_table) {
			$currentDate = new DateTime();
			$currentTime = $currentDate->getTimestamp();

			$orders = $wpdb->get_results(
				"SELECT order_id, expired_time FROM " . $mypos_table
				. " WHERE test_environment = " . (int)$this->test . " ORDER BY last_check ASC LIMIT 0, 10"
			);

			if (!empty($orders)) {
				foreach ($orders as $order) {
					$order_id = $this->test ? str_replace($this->test_prefix, '', $order->order_id) : $order->order_id;
					$this->check_payment_status($order_id);
				}

				$ordersRaw = implode(',', array_map(function ($order) {
					return $order->order_id;
				}, $orders));

				if (!empty($ordersRaw)) {
					$wpdb->query(
						"UPDATE {$mypos_table} SET last_check = {$currentTime} WHERE order_id IN ({$ordersRaw})"
					);
				}

				if (!$this->hasDeadlinePendingOrder) {
					$wpdb->query("DELETE FROM {$mypos_table} WHERE expired_time < {$currentTime}");
				} else {
					$wpdb->query("DELETE FROM {$mypos_table} WHERE expired_time + 86400 < {$currentTime}");
				}
			}
		}
	}

	/**
	 * IPCRefund - Refund method
	 *
	 * @param WC_Order $order
	 * @param $amount
	 * @return array
	 */
	private function create_refund_data($order, $amount)
	{
		self::log('Create refund data for order: ' . $order->get_order_number());

		$post = array();
		$post['IPCmethod'] = 'IPCRefund';
		$post['IPCVersion'] = $this->version;
		$post['IPCLanguage'] = 'en';
		$post['WalletNumber'] = $this->wallet_number;
		$post['SID'] = $this->sid;
		$post['keyindex'] = $this->keyindex;
		$post['Source'] = $this->get_source();

		$post['IPC_Trnref'] = $order->get_transaction_id();
		$post['OrderID'] = $order->get_id();
		$post['Amount'] = number_format((float)$amount, 2, '.', '');
		$post['Currency'] = $order->get_currency();
		$post['OutputFormat'] = 'xml';

		$post['Signature'] = $this->create_signature($post);

		return $post;
	}

	/**
	 * IPCGetPaymentStatus - Check payment status in the system
	 *
	 * @param $order_id
	 * @return array
	 */
	private function create_check_status_data($order_id)
	{
		$post = array();
		$post['IPCmethod'] = 'IPCGetPaymentStatus';
		$post['IPCVersion'] = $this->version;
		$post['IPCLanguage'] = 'en';
		$post['WalletNumber'] = $this->wallet_number;
		$post['SID'] = $this->sid;
		$post['keyindex'] = $this->keyindex;
		$post['Source'] = $this->get_source();
		$post['OrderID'] = $order_id;
		$post['OutputFormat'] = 'xml';
		$post['Signature'] = $this->create_signature($post);

		return $post;
	}

	/**
	 * Verify signature with IPCSignatureVerify method
	 *
	 * @return array
	 */
	private function create_verify_notify_data()
	{
		$post = array();
		$post['IPCmethod'] = 'IPCSignatureVerify';
		$post['IPCVersion'] = $this->version;
		$post['WalletNumber'] = $this->wallet_number;
		$post['SID'] = $this->sid;
		$post['keyindex'] = $this->keyindex;
		$post['Source'] = $this->get_source();
		$post['url_notify'] = site_url() . '/?wc-api=wc_gateway_mypos';
		$post['OutputFormat'] = 'xml';

		if ($post['SID'] && $post['keyindex']) {
			$post['Signature'] = $this->create_signature($post);
		} else {
			$post['Signature'] = null;
		}

		return $post;
	}

	/**
	 * IPCSendMoney method
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function create_send_money_data($order)
	{
		$post = array();
		$post['IPCmethod'] = 'IPCSendMoney';
		$post['IPCVersion'] = $this->version;
		$post['IPCLanguage'] = 'en';
		$post['WalletNumber'] = $this->wallet_number;
		$post['CustomerWalletNumber'] = $this->merchant_wallet_number;
		$post['SID'] = $this->sid;
		$post['keyindex'] = $this->keyindex;
		$post['Amount'] = $order->get_total();
		$post['Currency'] = $order->get_currency();
		$post['Reason'] = $this->merchant_send_money_reason . ' - ' . $order->get_order_number(
			) . ', ' . $order->get_total() . ' ' . $order->get_currency();

		$post['OutputFormat'] = 'xml';

		$post['Signature'] = $this->create_signature($post);

		return $post;
	}

	/**
	 * Convert xml into array (for POST request)
	 *
	 * @param $xml
	 * @return array|null
	 */
	public function xml_to_post($xml)
	{
		if (empty($xml)) {
			return null;
		}

		$xml = simplexml_load_string($xml);

		$post = array();

		/** @var \SimpleXMLElement $child */
		foreach ($xml->children() as $child) {
			$post[$child->getName()] = (string)$child;
		}

		return $post;
	}

	/**
	 * Get gateway icon.
	 *
	 * @return string
	 */
	public function get_icon()
	{
		if ($this->paymentMethod == self::PAYMENT_METHOD_BOTH && $this->is_valid_for_ideal()) {
			$image_name = 'card_schemes_ideal_no_bg.png';
		} elseif ($this->paymentMethod == self::PAYMENT_METHOD_IDEAL && $this->is_valid_for_ideal()) {
			$image_name = 'mypos_ideal_no_bg.png';
		} else {
			$image_name = 'card_schemes_no_bg.png';
		}

		$icon = WC_HTTPS::force_https_url(
			plugins_url() . '/mypos-virtual-for-woocommerce/assets/images/' . $image_name
		);
		$icon_html = '<img src="' . $icon . '" alt="mypos_checkout_logo" />';

		return apply_filters('woocommerce_gateway_icon', $icon_html, $this->id);
	}

	/**
	 * Get line item args for mypos request.
	 * @param WC_Order $order
	 * @return array
	 */
	protected function get_line_item_args($order)
	{
		if (null === $order) {
			return array();
		}

		/**
		 * Try passing a line item per product if supported.
		 */
		if ((!wc_tax_enabled() || !wc_prices_include_tax()) && $this->prepare_line_items($order)) {
			$line_item_args = array();
			$line_item_args['tax_cart'] = $this->number_format($order->get_total_tax(), $order);

			if ($order->get_total_discount() > 0) {
				$line_item_args['discount_amount_cart'] = $this->number_format(
					$this->round($order->get_total_discount(), $order),
					$order
				);
			}

			if ($order->get_shipping_total() > 0) {
				$this->add_line_item(
					sprintf(__('Shipping via %s', 'woocommerce'), $order->get_shipping_method()),
					1,
					$this->number_format($order->get_shipping_total(), $order)
				);
			}

			$line_item_args = array_merge($line_item_args, $this->get_line_items());
			/**
			 * Send order as a single item.
			 */
		} else {
			$this->delete_line_items();

			$line_item_args = array();
			$all_items_name = $this->get_order_item_names($order);
			$this->add_line_item(
				$all_items_name ?: __('Order', 'woocommerce'),
				1,
				$this->number_format(
					$order->get_total() - $this->round(
						$order->get_shipping_total() + $order->get_shipping_tax(),
						$order
					),
					$order
				),
				$order->get_order_number()
			);

			if ($order->get_shipping_total() > 0) {
				$this->add_line_item(
					sprintf(__('Shipping via %s', 'woocommerce'), $order->get_shipping_method()),
					1,
					$this->number_format($order->get_shipping_total() + $order->get_shipping_tax(), $order)
				);
			}

			$line_item_args = array_merge($line_item_args, $this->get_line_items());
		}

		return $line_item_args;
	}


	/**
	 * Get order item names as a string.
	 * @param WC_Order $order
	 * @return string
	 */
	protected function get_order_item_names($order)
	{
		if (empty($order)) {
			return '';
		}

		$item_names = array();

		foreach ($order->get_items() as $item) {
			$item_names[] = $item['name'] . ' x ' . $item['qty'];
		}

		return implode(', ', $item_names);
	}

	/**
	 * Get cart item name from order
	 *
	 * @param $order
	 * @param $item
	 * @return string
	 */
	protected function get_order_item_name($order, $item)
	{
		$item_name = $item['name'];
		$item_meta = new WC_Order_Item_Product($item);

		//Fix for wrong meta type object
		$formatted_meta = $item_meta->get_formatted_meta_data('_');
		if (!empty($item_meta)) {
			foreach ($formatted_meta as $meta) {
				if ($meta->label || $meta->value) {
					return $item_name;
				}
			}
		}

		/*if ( $meta = $item_meta->get_meta_data() ) {
			$item_name .= ' ( ' . $meta . ' )';
		}*/

		return $item_name;
	}

	/**
	 * Return all line items.
	 */
	protected function get_line_items()
	{
		return $this->line_items;
	}

	/**
	 * Remove all line items.
	 */
	protected function delete_line_items()
	{
		$this->line_items = array();
	}

	/**
	 * Get line items to send to mypos virtual.
	 * @param WC_Order $order
	 * @return bool
	 */
	protected function prepare_line_items($order)
	{
		$this->delete_line_items();
		$calculated_total = 0;

		// Products
		foreach ($order->get_items(array('line_item', 'fee')) as $item) {
			if ('fee' === $item['type']) {
				$item_line_total = $this->number_format($item['line_total'], $order);
				$line_item = $this->add_line_item($item['name'], 1, $item_line_total);
				$calculated_total += $item_line_total;
			} else {
				/** @var WC_Product|bool $product */
				$product = is_callable(array($item, 'get_product')) ? $item->get_product() : false;
				$sku = $product ? $product->get_sku() : '';
				$item_line_total = $this->number_format($order->get_item_subtotal($item, false), $order);
				$line_item = $this->add_line_item(
					$this->get_order_item_name($order, $item),
					$item['qty'],
					$item_line_total,
					$sku
				);
				$calculated_total += $item_line_total * $item['qty'];
			}

			if (!$line_item) {
				return false;
			}
		}

		// Check for mismatched totals.
		if ($this->number_format(
				$calculated_total + $order->get_total_tax() + $this->round(
					$order->get_shipping_total(),
					$order
				) - $this->round($order->get_total_discount(), $order),
				$order
			) != $this->number_format($order->get_total(), $order)) {
			return false;
		}

		return true;
	}

	/**
	 * Add Line Item.
	 * @param string $item_name
	 * @param int $quantity
	 * @param float $amount
	 * @param string $item_number
	 * @return bool successfully added or not
	 */
	protected function add_line_item($item_name, $quantity = 1, $amount = 0.0, $item_number = '')
	{
		$index = (sizeof($this->line_items) / 4) + 1;

		$this->line_items['item_name_' . $index] = html_entity_decode(
			wc_trim_string($item_name ?: __('Item', 'woocommerce'), 127),
			ENT_NOQUOTES,
			'UTF-8'
		);
		$this->line_items['quantity_' . $index] = (int)$quantity;
		$this->line_items['amount_' . $index] = (float)$amount;
		$this->line_items['item_number_' . $index] = $item_number;

		return true;
	}

	/**
	 * Check if currency has decimals.
	 * @param string $currency
	 * @return bool
	 */
	protected function currency_has_decimals(string $currency)
	{
		if (in_array($currency, array('HUF', 'JPY', 'TWD'))) {
			return false;
		}

		return true;
	}

	/**
	 * Round prices.
	 * @param double $price
	 * @param WC_Order $order
	 * @return double
	 */
	protected function round($price, $order)
	{
		$precision = 2;

		if (!$this->currency_has_decimals($order->get_currency())) {
			$precision = 0;
		}

		return round($price, $precision);
	}

	/**
	 * Format prices.
	 * @param float|int $price
	 * @param WC_Order $order
	 * @return string
	 */
	protected function number_format($price, $order)
	{
		$decimals = 2;

		if (!$this->currency_has_decimals($order->get_currency())) {
			$decimals = 0;
		}

		return number_format($price, $decimals, '.', '');
	}

	protected function escape_string($string)
	{
		$string = strip_tags(htmlspecialchars_decode(str_replace("\r", "", str_replace("\n", "", $string))));
		return preg_replace('/#!trpst#(.*?)!trpen#/i', '', $string); //Remove TransPress Logic fix
	}

	protected function process_curl_checkout_post($data)
	{
		//open connection
		$ch = curl_init($this->url);

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // Timeout on connect (2 minutes)

		//execute post
		$result = curl_exec($ch);
		curl_close($ch);

		return $this->xml_to_post($result);
	}
}
