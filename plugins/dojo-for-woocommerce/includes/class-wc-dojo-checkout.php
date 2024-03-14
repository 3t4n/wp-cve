<?php

/**
 * Dojo Checkout Method
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
	exit();
}

if (!class_exists('WC_Dojo')) {

	require_once __DIR__ . '/class-wc-dojo-logger.php';
	require_once __DIR__ . '/class-wc-dojo-apiclient.php';
	require_once __DIR__ . '/class-wc-dojo-telemetry-apiclient.php';
	require_once __DIR__ . '/class-wc-dojo-webhooks-processor.php';
	require_once __DIR__ . '/class-wc-dojo-utils.php';
	require_once __DIR__ . '/models/class-wc-dojo-payment-intent.php';
	require_once __DIR__ . '/models/class-wc-dojo-problem-details-exception.php';

	/**
	 * WC_Dojo class.
	 */
	class WC_Dojo extends WC_Payment_Gateway
	{
		/**
		 * Shopping cart platform name
		 */
		const PLATFORM_NAME = 'WooCommerce';

		/**
		 * Payment gateway name
		 */
		const GATEWAY_NAME = 'Dojo Checkout';

		/**
		 * DOJO URLs
		 */
		const URL_TEST_CARDS  = 'https://docs.dojo.tech/payments/development-resources/testing';
		const URL_NEW_API_KEY = 'https://developer.dojo.tech/';

		/**
		 * CSS Class Names
		 */
		const SUCCESS_CLASS_NAME = 'notice notice-success';
		const WARNING_CLASS_NAME = 'notice notice-warning';
		const ERROR_CLASS_NAME   = 'notice notice-error';

		/**
		 * Message Types for admin information
		 */
		const MESSAGE_TYPE_ADMIN_SETTINGS         = 'settings';
		const MESSAGE_TYPE_ADMIN_ENVIRONMENT      = 'environment';
		const MESSAGE_TYPE_ADMIN_NEW_KEY          = 'newkey';
		const MESSAGE_TYPE_MISSING_WEBHOOK_SECRET = 'webhooksecret_missing';
		const MESSAGE_TYPE_MISSING_ORDER_PREFIX   = 'order_prefix_missing';
		const MESSAGE_TYPE_MISSING_TITLE          = 'title_missing';

		/**
		 * Plugin templates
		 */
		const TEMPLATE_SETTINGS = 'dojo-settings.php';
		const TEMPLATE_ERROR    = 'dojo-error.php';
		const TEMPLATE_MESSAGE  = 'dojo-message.php';

		/**
		 * Content types for module information
		 */
		const MINFO_TYPE_APPLICATION_JSON = 'application/json';
		const MINFO_TYPE_TEXT_PLAIN       = 'text/plain';

		/**
		 * Payment method ID
		 *
		 * @var string
		 */
		public $id = 'dojo';

		/**
		 * Payment title
		 *
		 * @var string
		 */
		public $title = '';

		/**
		 * Payment method title
		 *
		 * @var string
		 */
		public $method_title = 'Dojo Checkout';

		/**
		 * Custom checkout title. If not specified, trading name is used on 
		 * the hosted page.
		 *
		 * @var string
		 */
		public $custom_checkout_title = null;

		/**
		 * Payment method description
		 *
		 * @var string
		 */
		public $method_description = 'Accept payments from credit/debit cards through Dojo';

		/**
		 * Specifies whether the payment method shows fields on the checkout
		 *
		 * @var bool
		 */
		public $has_fields = false;

		/**
		 * Payment secret API key
		 *
		 * @var string
		 */
		protected $secret_key;

		/**
		 * Webhook secret
		 *
		 * @var string
		 */
		protected $webhook_secret;

		/**
		 * Capture mode
		 *
		 * @var string
		 */
		protected $capture_mode;

		/**
		 * Wallet enabled
		 *
		 * @var string
		 */
		protected $wallet_enabled;

		/**
		 * Itemlines enabled
		 *
		 * @var string
		 */
		protected $itemlines_enabled;

		/**
		 * Order prefix
		 *
		 * @var string
		 */
		protected $order_prefix;

		/**
		 * Plugin data
		 *
		 * @var array
		 */
		protected $plugin_data = [];

		/**
		 * Supported content types of the output of the module information
		 *
		 * @var array
		 */
		protected $content_types = [
			'json' => self::MINFO_TYPE_APPLICATION_JSON,
			'text' => self::MINFO_TYPE_TEXT_PLAIN,
		];

		/**
		 * API Client
		 *
		 * @var WC_Dojo_ApiClient
		 */
		private $api_client;

		/**
		 * WebHooks processor
		 *
		 * @var WC_Dojo_Webhooks_Processor
		 */
		private $webhooks_processor;

		/**
		 * Dojo logger
		 *
		 * @var WC_Dojo_Logger
		 */
		private $logger;

		/**
		 * Dojo class constructor
		 */
		public function __construct()
		{
			$this->init_form_fields();
			$this->load_configuration();
			$this->add_refund_support();
			$this->add_hooks();
			$this->retrieve_plugin_data();

			$this->api_client = new WC_Dojo_ApiClient();
			$this->webhooks_processor = new WC_Dojo_Webhooks_Processor($this->api_client);
			$this->logger = new WC_Dojo_Logger();
		}

		/**
		 * Initialises settings form fields
		 *
		 * Overrides wc settings api class method
		 */
		public function init_form_fields()
		{
			$this->form_fields = include __DIR__ . '/settings-wc-dojo.php';
		}

		/**
		 * Receipt page
		 *
		 * @param int $order_id Order ID.
		 */
		public function receipt_page($order_id)
		{
			if ($this->is_valid_for_use()) {
				$this->show_payment_form($order_id);
			} elseif (!$this->is_connection_over_https()) {
				$warning = 'The Dojo Checkout payment method requires an encrypted connection';
				$this->logger->log(
					"Warning",
					"receipt_page",
					$warning,
					$this->secret_key
				);
				$this->show_message(
					__($warning, 'woocommerce-dojo') . ' ' .
						__('Please contact customer support.', 'woocommerce-dojo')
				);
			} else {
				$warning = 'The Dojo Checkout payment method is not configured.';
				$this->logger->log(
					"Warning",
					"receipt_page",
					$warning,
					$this->secret_key
				);
				$this->show_message(
					__($warning, 'woocommerce-dojo') . ' ' .
						__('Please contact customer support.', 'woocommerce-dojo')
				);
			}
		}

		/**
		 * Processes the payment
		 *
		 * Overrides wc payment gateway class method
		 *
		 * @param int $order_id WooCommerce Order ID.
		 *
		 * @return array
		 */
		public function process_payment($order_id)
		{
			$order = new WC_Order($order_id);
			return [
				'result'   => 'success',
				'redirect' => sanitize_url($order->get_checkout_payment_url(true)),
			];
		}

		/**
		 * Processes the requests sent to the plugin through the WooCommerce API callback
		 */
		public function process_gateway_response()
		{
			switch (true) {
				case $this->is_info_request():
					$this->process_info_request();
					break;
				case $this->is_checksums_request():
					$this->process_checksums_request();
					break;
				case $this->is_admin_info_request():
					$this->process_admin_info_request();
					break;
				case $this->is_payment_notification_request():
					$this->process_payment_notification_request();
					break;
				default:
					$this->process_customer_redirect();
			}
		}

		/**
		 * Processes the payment notification callback requests triggered on update of the payment intent status
		 */
		public function process_payment_notification_request()
		{
			if (!$this->is_valid_for_use()) {
				wp_send_json(["status" => "400", "title" => "invalid request"], 400);
			}

			return $this->webhooks_processor->process_webhook_call($this->webhook_secret, $this->secret_key);
		}

		/**
		 * Processes the payment gateway response
		 */
		public function process_customer_redirect()
		{
			if (!$this->is_valid_for_use()) {
				$this->show_error(
					__('The Dojo Checkout payment method is not configured.', 'woocommerce-dojo') . ' ' .
						__('Please contact customer support.', 'woocommerce-dojo')
				);
			}

			$payment_intent_id = $this->get_http_var('id', '', 'GET');

			if (empty($payment_intent_id)) {
				$this->show_error(
					__('Payment intent is empty.', 'woocommerce-dojo') . ' ' .
						__('Please contact customer support.', 'woocommerce-dojo')
				);
			}

			$order = WC_Dojo_Utils::get_order_by_payment_intent_id($payment_intent_id);

			if (!(isset($order) && ($order instanceof WC_Order) && (!empty($order->get_id())))) {
				$this->logger->log(
					"Warning",
					"process_customer_redirect", 
					"Order ID is invalid. Id:" . $payment_intent_id,
					$this->secret_key
				);
				$this->show_error(
					__('Order ID is invalid.', 'woocommerce-dojo') . ' ' .
						__('Please contact customer support.', 'woocommerce-dojo')
				);
			}

			switch ($order->get_status()) {
				case 'failed':
				case 'pending':
					try {
						$payment_intent = $this->api_client->get_payment_intent($payment_intent_id, $this->secret_key);
						switch ($payment_intent->payment_status) {
							case WC_Dojo_Payment_Intent::PAYMENT_STATUS_CODE_SUCCESS:
								$order->payment_complete();
								$order_note = sprintf(
									// Translators: %s - payment message.
									__('Payment processed successfully with message: %s.', 'woocommerce-dojo'),
									$payment_intent->message
								);

								$this->logger->log(
									"Info",
									"process_customer_redirect",
									'Payment processed successfully',
									$this->secret_key
								);

								WC()->cart->empty_cart();
								$location = sanitize_url($order->get_checkout_order_received_url());
								break;
							case WC_Dojo_Payment_Intent::PAYMENT_STATUS_CODE_UNKNOWN:
							default:
								$order_note_text = 'Payment status is unknown because of a communication error or unknown/unsupported payment status.';
								$order_note = __(
									$order_note_text,
									'woocommerce-dojo'
								);

								$this->logger->log(
									"Error",
									"process_customer_redirect",
									$order_note_text,
									$this->secret_key
								);

								wc_add_notice(
									sprintf(
										// Translators: %s - order ID.
										__('Unfortunately your payment cannot be confirmed at this time. Please contact customer support quoting your order #%s and do not retry the payment for this order unless you are instructed to do so.', 'woocommerce-dojo'),
										$order_id
									),
									'error'
								);
								$location = sanitize_url($order->get_checkout_payment_url());
						}
						$order->add_order_note($order_note);
					} catch (Exception $exception) {
						$this->logger->log(
							"Error",
							"process_customer_redirect",
							$exception->getMessage(),
					 		$this->secret_key
						);

						wc_add_notice(
							sprintf(
								// Translators: %1$s - order number, %2$s - error message.
								__('An error occurred while processing order#%1$s. Error message: %2$s', 'woocommerce-dojo'),
								$order_id,
								$exception->getMessage()
							),
							'error'
						);
						$location = sanitize_url($order->get_checkout_payment_url());
					}
					break;
				case 'processing':
				case 'completed':
					WC()->cart->empty_cart();
					$location = sanitize_url($order->get_checkout_order_received_url());
					break;
				default:
					wc_add_notice(
						sprintf(
							// Translators: %s - order ID.
							__('Unfortunately your payment cannot be confirmed at this time. Please contact customer support quoting your order #%s and do not retry the payment for this order unless you are instructed to do so.', 'woocommerce-dojo'),
							$order_id
						),
						'error'
					);

					$this->logger->log(
						"Error",
						"process_customer_redirect",
						"Unfortunately your payment cannot be confirmed at this time.",
						$this->secret_key
					);
					$location = sanitize_url($order->get_checkout_payment_url());
			}

			wp_safe_redirect($location);
			exit;
		}

		/**
		 * Outputs the payment method settings in the admin panel
		 *
		 * Overrides wc payment gateway class method
		 */
		public function admin_options()
		{
			$this->show_output(
				self::TEMPLATE_SETTINGS,
				[
					'this_'          => $this,
					'title'          => esc_textarea($this->get_method_title()),
					'description'    => esc_textarea($this->get_method_description()),
					'admin_info_url' => $this->get_admin_info_url(),
					'plugin_version' => esc_textarea($this->get_module_name() . ' ' . $this->get_module_installed_version()),
					'error_message'  => $this->is_connection_over_https()
						? ''
						: __('This payment method requires an encrypted connection. ', 'woocommerce-dojo') .
						__('Please enable SSL/TLS.', 'woocommerce-dojo'),
				]
			);
		}

		/**
		 * Processes refunds.
		 *
		 * @param int    $order_id Order ID.
		 * @param float  $amount   Refund amount.
		 * @param string $reason   Refund Reason.
		 *
		 * @return bool|WP_Error
		 */
		public function process_refund($order_id, $amount = null, $reason = '')
		{
			try {
				$order = new WC_Order($order_id);

				$idempotency_key = sha1(rand());
				$pi 			 = $order->get_meta('payment_intent_id');
				$post_fields     = $this->build_request_create_refund($order_id, $amount, $reason);

				$this->api_client->refund($pi, $post_fields, $idempotency_key, $this->secret_key);
				
				if ($order->get_id()) {
					$order_note = sprintf(
						// Translators: %1$s - order amount, %2$s - currency.
						__('Refund for %1$.2f %2$s processed successfully.', 'woocommerce-dojo'),
						$amount,
						get_woocommerce_currency()
					);
					$order->add_order_note($order_note);
				}

				return true;
			} catch (Exception $ex) {
				$this->logger->log(
					"Error",
					"process_refund",
					$ex->getMessage(),
					$this->secret_key
				);
				$message = sprintf(
					// Translators: %s - diagnostic message.
					__('Refund was declined. %s ', 'woocommerce-dojo'),
					$ex->getMessage()
				);

				return new WP_Error('refund_error', $message);
			}
		}

		/**
		 * Validates payment fields on the frontend. 
		 * Happens when 'PLACE ORDER' button is clicked.
		 *
		 * Overrides parent wc payment gateway class method
		 *
		 * @return bool
		 */
		public function validate_fields()
		{
			if (!$this->is_connection_over_https()) {
				$this->logger->log(
					"Error",
					"validate_fields",
					"This payment method requires an encrypted connection",
					$this->secret_key
				);
				wc_add_notice(
					__('This payment method requires an encrypted connection. ', 'woocommerce-dojo')
						. __('Please enable SSL/TLS.', 'woocommerce-dojo'),
					'error'
				);
				return false;
			}
			return true;
		}

		/**
		 * Determines if the payment method is available
		 *
		 * Checks whether the connection is secure and whether the secret API key is set
		 *
		 * @return bool
		 */
		public function is_valid_for_use()
		{
			return (
				$this->is_connection_over_https() &&
				!empty($this->secret_key)
			);
		}

		/**
		 * Checks whether the request is a payment notification
		 *
		 * @return bool
		 */
		protected function is_payment_notification_request()
		{
			return wp_is_json_request();
		}

		/**
		 * Checks whether the request is for plugin information.
		 * NOTE: This request is not used in the plugin flows, it is only used by
		 * Dojo Module Information Extractor (MIE).
		 * @return bool 
		 */
		protected function is_info_request()
		{
			return 'info' === $this->get_http_var('action', '');
		}

		/**
		 * Checks whether the request is for file checksums
		 * NOTE: This request is not used in the plugin flows, it is only used by
		 * Dojo Module Information Extractor (MIE).
		 *
		 * @return bool
		 */
		protected function is_checksums_request()
		{
			return 'checksums' === $this->get_http_var('action', '', 'GET');
		}

		/**
		 * Checks whether the request is for admin information
		 *
		 * @return bool
		 */
		protected function is_admin_info_request()
		{
			return 'admin_info' === $this->get_http_var('action', '', 'GET');
		}

		/**
		 * Determines whether the store is configured to use HTTPS
		 *
		 * @return bool
		 */
		protected function is_connection_over_https()
		{
			return is_ssl() || sanitize_text_field($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https';
		}

		/**
		 * Loads the configuration
		 */
		protected function load_configuration()
		{
			$options = [
				'enabled',
				'title',
				'custom_checkout_title',
				'description',
				'order_prefix',
				'secret_key',
				'capture_mode',
				'wallet_enabled',
				'webhook_secret',
				'itemlines_enabled'
			];
			foreach ($options as $option) {
				$this->$option = $this->get_option($option);
			}
		}

		/**
		 * Adds support for refunds
		 */
		protected function add_refund_support()
		{
			$this->supports[] = 'refunds';
		}

		/**
		 * Adds hooks
		 */
		protected function add_hooks()
		{
			add_action(
				'woocommerce_update_options_payment_gateways_' . $this->id,
				[$this, 'process_admin_options']
			);
			add_action(
				'woocommerce_receipt_' . $this->id,
				[$this, 'receipt_page']
			);
			add_action(
				'woocommerce_api_wc_' . $this->id,
				[$this, 'process_gateway_response']
			);
		}

		/**
		 * Event happens when "Save changes" button clicked inside admin section (settings)
		 */
		public function process_admin_options()
		{
			return parent::process_admin_options();
		}

		/**
		 * Gets the value of an HTTP variable based on the requested method or the default value if the variable does not exist
		 *
		 * @param string $field   HTTP POST/GET variable.
		 * @param string $default Default value.
		 * @param string $method  Request method.
		 *
		 * @return string
		 */
		protected function get_http_var($field, $default = '', $method = '')
		{
			// @codingStandardsIgnoreStart
			if (empty($method)) {
				$method = sanitize_text_field($_SERVER['REQUEST_METHOD']);
			}
			switch ($method) {
				case 'GET':
					return array_key_exists($field, $_GET)
						? (is_string($_GET[$field]) ? sanitize_text_field($_GET[$field]) : $_GET[$field])
						: $default;
				case 'POST':
					return array_key_exists($field, $_POST)
						? (is_string($_POST[$field]) ? sanitize_text_field($_POST[$field]) : $_POST[$field])
						: $default;
				default:
					return $default;
			}
			// @codingStandardsIgnoreEnd
		}

		/**
		 * Gets the module name
		 *
		 * @return string
		 */
		protected function get_module_name()
		{
			return array_key_exists('Name', $this->plugin_data)
				? sanitize_text_field($this->plugin_data['Name'])
				: '';
		}

		/**
		 * Gets the module installed version
		 *
		 * @return string
		 */
		protected function get_module_installed_version()
		{
			return WC_DOJO_VERSION;
		}

		/**
		 * Gets the WordPress version
		 *
		 * @return string
		 */
		protected function get_wp_version()
		{
			return get_bloginfo('version');
		}

		/**
		 * Gets WooCommerce version
		 *
		 * @return string
		 */
		protected function get_wc_version()
		{
			return WC()->version;
		}

		/**
		 * Gets shopping cart platform URL
		 *
		 * @return string
		 */
		protected function get_cart_url()
		{
			return sanitize_url(site_url());
		}

		/**
		 * Gets shopping cart platform name
		 *
		 * @return string
		 */
		protected function get_cart_platform_name()
		{
			return sanitize_text_field(self::PLATFORM_NAME);
		}

		/**
		 * Gets gateway name
		 *
		 * @return string
		 */
		protected function get_gateway_name()
		{
			return sanitize_text_field(self::GATEWAY_NAME);
		}

		/**
		 * Gets the status of the wallet
		 *
		 * @return string
		 */
		protected function get_wallet_status()
		{
			return $this->is_wallet_enabled() ? 'Enabled' : 'Disabled';
		}
		
		/**
		 * Checks whether the wallet is enabled
		 *
		 * @return bool
		 */
		protected function is_wallet_enabled()
		{
			return 'yes' === $this->wallet_enabled;
		}

		/**
		 * Gets the status of item lines
		 *
		 * @return string
		 */
		protected function get_itemlines_status()
		{
			return $this->is_itemlines_enabled() ? 'Enabled' : 'Disabled';
		}

		/**
		 * Checks whether item lines are enabled
		 *
		 * @return bool
		 */
		protected function is_itemlines_enabled()
		{
			return 'yes' === $this->itemlines_enabled;
		}

		/**
		 * Gets the PHP version
		 *
		 * @return string
		 */
		protected function get_php_version()
		{
			return phpversion();
		}

		/**
		 * Gets the environment name
		 *
		 * @return string
		 */
		protected function get_environment_name()
		{
			return $this->using_sandbox_key() ? 'Test' : 'Production';
		}

		/**
		 * Determines whether the secret API key is for the sandbox environment
		 */
		protected function using_sandbox_key()
		{
			return 0 === strpos($this->secret_key, 'sk_sandbox_');
		}

		/**
		 * Gets the message indicating whether the secret API key is valid
		 *
		 * @param bool $text_format Specifies whether the format of the message is text.
		 *
		 * @return array
		 */
		protected function get_secret_key_message($text_format)
		{
			$isValid = $this->api_client->validate_api_key($this->secret_key);

			if ($isValid) {
				$result = $this->build_success_message(
					__(
						'The secret API key is valid.',
						'woocommerce-dojo'
					),
					self::MESSAGE_TYPE_ADMIN_SETTINGS
				);
			} else {
				$result = $this->build_error_message(
					__(
						'The secret API key is invalid.',
						'woocommerce-dojo'
					),
					self::MESSAGE_TYPE_ADMIN_SETTINGS
				);
			}

			if ($text_format) {
				$result = $this->getSettingsTextMessage($result);
			}

			return $result;
		}

		/**
		 * Gets a message indicating whether the secret API key is for the test environment
		 *
		 * @param bool $text_format Specifies whether the format of the message is text.
		 *
		 * @return array
		 */
		protected function get_test_environment_message($text_format)
		{
			$result = [];
			if ($this->using_sandbox_key()) {
				$result = $this->build_warning_message(
					sprintf(
						// Translators: %s - URL.
						__(
							'The configured secret API key is for the TEST Dojo environment. This API key can only be used for TEST transactions by using our <a href="%s" target="_blank">test cards</a>.',
							'woocommerce-dojo'
						),
						self::URL_TEST_CARDS
					),
					self::MESSAGE_TYPE_ADMIN_ENVIRONMENT
				);
			}

			if ($text_format) {
				$result = $this->getSettingsTextMessage($result);
			}
			return $result;
		}

		/**
		 * Gets a message offering a new API key
		 *
		 * @return array
		 */
		protected function get_new_api_key_message()
		{
			return $this->build_warning_message(
				sprintf(
					// Translators: %s - URL.
					__(
						'If you need any API key or Webhook secret please click <a href="%s" target="_blank">here</a>.',
						'woocommerce-dojo'
					),
					self::URL_NEW_API_KEY
				),
				self::MESSAGE_TYPE_ADMIN_NEW_KEY
			);
		}

		/**
		 * Gets a message about missing webhook secret.
		 *
		 * @return array
		 */
		protected function get_missing_webhook_secret_error()
		{
			$result = [];

			if (empty($this->webhook_secret)) {
				return $this->build_error_message(
					__(
						'Webhook secret is not specified.',
						'woocommerce-dojo'
					),
					self::MESSAGE_TYPE_MISSING_WEBHOOK_SECRET
				);
			}

			return $result;
		}

		/**
		 * Gets a message about missing order prefix.
		 *
		 * @return array
		 */
		protected function get_missing_order_prefix_error()
		{
			$result = [];

			if (empty($this->order_prefix)) {
				return $this->build_error_message(
					__(
						'Order prefix is not specified.',
						'woocommerce-dojo'
					),
					self::MESSAGE_TYPE_MISSING_ORDER_PREFIX
				);
			}

			return $result;
		}

		/**
		 * Gets a message about missing title.
		 *
		 * @return array
		 */
		protected function get_missing_title_error()
		{
			$result = [];

			if (empty($this->title)) {
				return $this->build_error_message(
					__(
						'Title is not specified.',
						'woocommerce-dojo'
					),
					self::MESSAGE_TYPE_MISSING_TITLE
				);
			}

			return $result;
		}


		/**
		 * Builds a localised success message
		 *
		 * @param  string $text         Text message.
		 * @param  string $message_type Message type.
		 *
		 * @return array
		 */
		protected function build_success_message($text, $message_type)
		{
			$this->logger->log("Debug", "process_admin_info_request", $text, $this->secret_key);
			return $this->build_message(
				$text,
				self::SUCCESS_CLASS_NAME,
				$message_type
			);
		}

		/**
		 * Builds a localised warning message
		 *
		 * @param  string $text         Text message.
		 * @param  string $message_type Message type.
		 *
		 * @return array
		 */
		protected function build_warning_message($text, $message_type)
		{
			$this->logger->log("Warning", "process_admin_info_request", $text, $this->secret_key);
			return $this->build_message(
				$text,
				self::WARNING_CLASS_NAME,
				$message_type
			);
		}

		/**
		 * Builds a localised error message
		 *
		 * @param  string $text         Text message.
		 * @param  string $message_type Message type.
		 *
		 * @return array
		 */
		protected function build_error_message($text, $message_type)
		{
			$this->logger->log("Error", "process_admin_info_request", $text, $this->secret_key);
			return $this->build_message(
				$text,
				self::ERROR_CLASS_NAME,
				$message_type
			);
		}

		/**
		 * Builds the message shown on the admin area
		 *
		 * @param  string $text         Text message.
		 * @param  string $class_name   CSS class name.
		 * @param  string $message_type Diagnostic message type.
		 *
		 * @return array
		 */
		protected function build_message($text, $class_name, $message_type)
		{
			return array(
				$message_type => array(
					'text'  => $text,
					'class' => $class_name,
				),
			);
		}

		/**
		 * Gets the text message from a settings message
		 *
		 * @param  array $arr Diagnostic Message.
		 *
		 * @return string
		 */
		protected function getSettingsTextMessage($arr)
		{
			return esc_textarea($arr[self::MESSAGE_TYPE_ADMIN_SETTINGS]['text']);
		}

		/**
		 * Gets the URL handling the reporting of the information shown at the plugin configuration page
		 *
		 * @return string
		 */
		protected function get_admin_info_url()
		{
			return site_url(
				'/?wc-api=' . get_class($this) . '&action=admin_info&output=json',
				$this->is_connection_over_https() ? 'https' : 'http'
			);
		}

		/**
		 * Gets the file checksums
		 *
		 * @return array
		 */
		protected function get_file_checksums()
		{
			$result    = [];
			$root_path = realpath(__DIR__ . '/../../../..');
			$file_list = $this->get_http_var('data', '', 'POST');
			if (is_array($file_list)) {
				foreach ($file_list as $key => $file) {
					$filename       = sanitize_file_name($root_path . '/' . $file);
					$result[$key] = is_file($filename)
						? sha1_file($filename)
						: null;
				}
			}
			return $result;
		}

		/**
		 * Builds the fields for the create payment intent request
		 *
		 * @param WC_Order $order WooCommerce order object.
		 *
		 * @return array An associative array containing the fields for the request
		 */
		protected function build_request_create_payment_intent($order)
		{
			// Calculate the expiry time.
			$expire_time = new DateTime();
			$expire_time->modify('+60 minutes');
			$expire_at_formatted = $expire_time->format(DateTime::ATOM);

			return [
				'amount'          => [
					'value'        => sanitize_text_field(WC_Dojo::convert_to_pence(($order->get_total()))),
					'currencyCode' => get_woocommerce_currency(),
				],
				'reference'       => sanitize_text_field((string) $order->get_id()),
				'description'     => sanitize_text_field($this->order_prefix . $order->get_id()),
				'itemLines'       => $this->is_itemlines_enabled() ? $this->get_order_items($order) : [],
				'taxLines'		  => $this->get_tax($order),
				'paymentMethods'  => $this->get_payment_methods(),
				'expireAt'        => $expire_at_formatted,
				'billingAddress'  => [
					'address1'    => sanitize_text_field($order->get_billing_address_1()),
					'address2'    => sanitize_text_field($order->get_billing_address_2()),
					'city'        => sanitize_text_field($order->get_billing_city()),
					'state'       => sanitize_text_field($order->get_billing_state()),
					'postcode'    => sanitize_text_field($order->get_billing_postcode()),
					'countryCode' => sanitize_text_field($order->get_billing_country()),
				],
				'shippingDetails' => $this->get_shipping_address($order),
				'config'          => [
					'redirectUrl' => sanitize_url(WC()->api_request_url("wc_" . $this->id, $this->is_connection_over_https())),
					'cancelUrl'   => sanitize_url($order->get_cancel_order_url()),
					'title'       => sanitize_text_field($this->custom_checkout_title) ?: null,
					'details'     => [
						'showTotal'   => true,
					],
				],
				'paymentSource'   => 'plugin',
				'metadata'        => $this->build_meta_data(),
			];
		}

		/**
		 * Gets the order items
		 *
		 * @param WC_Order $order WooCommerce order object.
		 *
		 * @return array
		 */
		protected function get_order_items($order)
		{
			$result = [];
			foreach ($order->get_items() as $item) {
				$result[] = [
					'id'          => sanitize_text_field($item->get_product_id()),
					'quantity'    => sanitize_text_field($item->get_quantity()),
					'caption'     => substr(sanitize_text_field($item->get_product()->get_name()), 0, 100),
					'amountTotal' => [
						'value'        => sanitize_text_field((WC_Dojo::convert_to_pence($item->get_product()->get_price() * $item->get_quantity()))),
						'currencyCode' => get_woocommerce_currency(),
					],
				];
			}
			if ($order->get_total_shipping() > 0) {
				$result[] = [
					'id'          => sanitize_text_field("1"),
					'quantity'    => sanitize_text_field("1"),
					'caption'     => sanitize_text_field("Shipping"),
					'amountTotal' => [
						'value'        => sanitize_text_field((WC_Dojo::convert_to_pence($order->get_total_shipping()))),
						'currencyCode' => get_woocommerce_currency(),
					],
				];
			}
			return $result;
		}

		/**
		 * Gets total tax for the current order
		 */
		protected function get_tax($order)
		{
			$tax_amount = $order->get_total_tax();
			if ($tax_amount <= 0) {
				return array();
			}

			$tax[] = [
				'id' => sanitize_text_field("Tax"),
				'caption' => sanitize_text_field("Tax"),
				'subCaption' => null,
				'amountTotal' => [
					'value'        => WC_Dojo::convert_to_pence($order->get_total_tax()),
					'currencyCode' => get_woocommerce_currency(),
				]
			];
			return $tax;
		}
		/**
		 * Converts an ammount to pence so the gateway can process it, returns an interger with the correct rounding
		 */
		protected static function convert_to_pence($amount)
		{
			$amount = ((int) (round($amount * 100)));

			return $amount;
		}


		/**
		 * Gets the payment methods
		 *
		 * @return array
		 */
		protected function get_payment_methods()
		{
			$result = ['Card'];
			if ($this->is_wallet_enabled()) {
				$result[] = 'Wallet';
			}
			return $result;
		}

		/**
		 * Builds the fields for the refund request
		 *
		 * @param int    $order_id      Order ID.
		 * @param float  $amount        Amount of the refund.
		 * @param string $refund_reason Reason of the refund.
		 *
		 * @return array An associative array containing the fields for the request
		 */
		protected function build_request_create_refund($order_id, $amount, $refund_reason)
		{
			return [
				'amount'       => sanitize_text_field(WC_Dojo::convert_to_pence($amount)),
				'refundReason' => sanitize_text_field($this->order_prefix . $order_id . ' ' . $refund_reason),
			];
		}

		/**
		 * Builds the meta data
		 *
		 * @return array An associative array containing the meta data
		 */
		protected function build_meta_data()
		{
			return [
				'shoppingCartUrl'      => $this->get_cart_url(),
				'shoppingCartPlatform' => $this->get_cart_platform_name(),
				'shoppingCartVersion'  => $this->get_wc_version(),
				'WordPressVersion'     => $this->get_wp_version(),
				'PHPVersion'           => $this->get_php_version(),
				'shoppingCartGateway'  => $this->get_gateway_name(),
				'pluginVersion'        => $this->get_module_installed_version(),
				'wallet'               => $this->get_wallet_status(),
			];
		}

		/**
		 * Processes the request for plugin information
		 */
		protected function process_info_request()
		{
			$info = [
				'Module Name'              => $this->get_module_name(),
				'Module Installed Version' => $this->get_module_installed_version(),
			];

			if ('true' === $this->get_http_var('extended_info', '')) {
				$extended_info = [
					'WordPress Version'   => $this->get_wp_version(),
					'WooCommerce Version' => $this->get_wc_version(),
					'PHP Version'         => $this->get_php_version(),
					'Secret API key'      => $this->get_secret_key_message(true),
					'Environment'         => $this->get_environment_name(),
					'Wallet'              => $this->get_wallet_status(),
				];

				$info = array_merge($info, $extended_info);
			}

			$this->output_info($info);
		}

		/**
		 * Processes the request for file checksums
		 */
		protected function process_checksums_request()
		{
			$info = [
				'Checksums' => $this->get_file_checksums(),
			];

			$this->output_info($info);
		}

		/**
		 * Retrieves the plugin data
		 */
		protected function retrieve_plugin_data()
		{
			if ((!function_exists('get_plugin_data')) &&
				is_file(ABSPATH . 'wp-admin/includes/plugin.php')
			) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if (function_exists('get_plugin_data')) {
				$ps_plugin_file    = '/dojo-for-woocommerce/dojo-for-woocommerce.php';
				$this->plugin_data = get_plugin_data(WP_PLUGIN_DIR . $ps_plugin_file);
			}
		}

		/**
		 * Processes the request for admin information
		 */
		protected function process_admin_info_request()
		{
			$this->output_info(
				array_merge(
					$this->get_missing_webhook_secret_error(),
					$this->get_missing_order_prefix_error(),
					$this->get_missing_title_error(),
					$this->get_secret_key_message(false),
					$this->get_test_environment_message(false),
					$this->get_new_api_key_message()
				)
			);
		}

		/**
		 * Outputs plugin information
		 *
		 * @param array $info Module information.
		 */
		protected function output_info($info)
		{
			$output       = $this->get_http_var('output', 'text', 'GET');
			$content_type = array_key_exists($output, $this->content_types)
				? $this->content_types[$output]
				: self::MINFO_TYPE_TEXT_PLAIN;

			switch ($content_type) {
				case self::MINFO_TYPE_APPLICATION_JSON:
					wp_send_json($info);
					break;
				case self::MINFO_TYPE_TEXT_PLAIN:
				default:
					$body = WC_Dojo_Utils::convert_array_to_string($info);
					break;
			}
			// @codingStandardsIgnoreStart
			@header('Cache-Control: max-age=0, must-revalidate, no-cache, no-store', true);
			@header('Pragma: no-cache', true);
			@header('Content-Type: ' . $content_type, true);
			echo esc_html($body);
			// @codingStandardsIgnoreEnd
			exit;
		}

		/**
		 * Shows the payment form.
		 *
		 * @param int $order_id WooCommerce Order ID.
		 */
		protected function show_payment_form($order_id)
		{
			$order = new WC_Order($order_id);

			try {
				$order->update_status(
					'pending',
					__('Pending payment', 'woocommerce-dojo')
				);
				
				$post_fields = $this->build_request_create_payment_intent($order);
				$payment_intent = $this->api_client->create_payment_intent($post_fields, $this->secret_key);
				$order->update_meta_data('payment_intent_id', $payment_intent->id);
				$order->save();

				// Redirect to hosted page and die
				wp_safe_redirect($payment_intent->get_payment_hosted_page_url());
				exit;
			} catch (Exception $ex) {
				$this->logger->log(
					"Error",
					"show_payment_form",
					$ex->getMessage(),
					$this->secret_key);
				$order->add_order_note($ex->getMessage());

				$message = __('An unexpected error has occurred. ', 'woocommerce-dojo') . ' ' .
					sprintf(
						// Translators: #%s - order number.
						__('Please contact customer support quoting your order #%s.', 'woocommerce-dojo'),
						$order_id
					);

				$extra_params = ['cancel_url' => $order->get_cancel_order_url()];

				$this->show_message($message, $extra_params);
			}
		}

		/**
		 * Outputs an inline message
		 *
		 * @param string $message      The message.
		 * @param array  $extra_params Extra parameters.
		 */
		protected function show_message($message, $extra_params = [])
		{
			$params = [
				'message' => esc_textarea($message),
			];
			if (!empty($extra_params)) {
				$params = array_merge($params, $extra_params);
			}
			$this->show_output(
				self::TEMPLATE_MESSAGE,
				$params
			);
		}

		/**
		 * Outputs an error message on a dedicated error page.
		 *
		 * @param string $message The message.
		 */
		protected function show_error($message)
		{
			$this->show_output(
				self::TEMPLATE_ERROR,
				[
					'title'   => __('An unexpected error has occurred. ', 'woocommerce-dojo'),
					'message' => esc_textarea($message),
				]
			);
			exit;
		}

		/**
		 * Generates output using a template
		 *
		 * @param string $template_name Template filename.
		 * @param array  $args          Template arguments.
		 */
		protected function show_output($template_name, $args = [])
		{
			$templates_path = dirname(plugin_dir_path(__FILE__)) . '/templates/';
			wc_get_template($template_name, $args, '', $templates_path);
		}

		/**
		 * Indicates that the order contains only virtual items, e.g. the ones that can't be shipped.
		 */
		private function is_virtual_order($order)
		{
			$items = $order->get_items();
			$isVirtual = true;
			foreach ($items as $item) {
				$product = $item->get_product();
				$isVirtual = $product->is_virtual();
				if (!$isVirtual) {
					break;
				}
			}

			return $isVirtual;
		}

		private function get_shipping_address($order)
		{

			// Do not add shipping details, if shipping method is not set or pure virtual products
			$shippingMethodAdded = !WC_Dojo_Utils::is_null_or_empty($order->get_shipping_method());
			$isVirtual = $this->is_virtual_order($order);

			$addShippingDetails = $shippingMethodAdded && !$isVirtual;

			return $addShippingDetails ? [
				'name'    => sanitize_text_field($order->get_shipping_first_name() . ' ' .
					$order->get_shipping_last_name()),
				'address' => [
					'address1'    => sanitize_text_field($order->get_shipping_address_1()),
					'address2'    => sanitize_text_field($order->get_shipping_address_2()),
					'city'        => sanitize_text_field($order->get_shipping_city()),
					'state'       => sanitize_text_field($order->get_shipping_state()),
					'postcode'    => sanitize_text_field($order->get_shipping_postcode()),
					'countryCode' => sanitize_text_field($order->get_shipping_country()),
				],
			] : null;
		}
	}
}
