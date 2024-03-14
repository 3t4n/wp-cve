<?php

/**
 *Woocommerce Customer  Add Field .
 *
 * @package  Payamito
 * @category Integration
 */

namespace Payamito\Woocommerce\Field;

use Payamito\Woocommerce\Funtions\Functions;
use Payamito_OTP;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Field')) :

	class Field
	{

		protected static $instance = null;

		private $OTP = null;
		public $phone_number = null;

		/**
		 * Start the Class when called
		 *
		 * @since   1.0.0
		 */
		public static function get_instance()
		{
			// If the single instance hasn't been set, set it now.
			if (null == self::$instance) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		function __construct()
		{

			if (self::check_once()) {
				global $pwoo_otp_options;

				if ($pwoo_otp_options == false || $pwoo_otp_options['active'] == false) {
					return;
				}

				add_action('woocommerce_after_checkout_validation', [$this, 'checkout_validation'], 999, 2);
				add_action("woocommerce_checkout_order_created",    [$this, "order_created"], 999, );
			}


			add_action('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts']);
			add_action('wp_ajax_payamito_woocommerce', [$this, 'ajax']);
			add_action('wp_ajax_nopriv_payamito_woocommerce', [$this, 'ajax']);
		}

		public function checkout_validation($data, $errors)
		{
			global $pwoo_otp_options;
			if ($pwoo_otp_options['active'] == false) {
				return $errors;
			}
			$phone_number = '';
			foreach (['billing_phone','billing-phone', 'phone' ] as $field_id) {
				if (isset($_REQUEST[$field_id])) {
					$phone_number = sanitize_text_field($_REQUEST[$field_id]);
					$this->phone_number = $phone_number;
					break;
				}
			}


			$otp = $_REQUEST['otp'] ? sanitize_text_field($_REQUEST['otp']) : '';

			$otp = payamito_to_english_number($otp);

			$phone_number = payamito_to_english_number($phone_number);

			$this->check_phone_number($phone_number, $errors);

			$this->check_otp($phone_number, $otp, $errors);

			return $errors;
		}

		public function check_phone_number($phone_number, $errors)
		{
			if (!payamito_verify_moblie_number($phone_number)) {
				return $errors->add('validation', __('Please Enter a valide phone number', 'payamito-woocommerce'));
			}
		}

		public function check_otp($phone_number, $otp, $errors)
		{
			if (!Payamito_OTP::payamito_validation_session($phone_number, $otp, 'payamito_wc')) {
				return $errors->add('validation', __('We cannot validate your phone number.please enter currect otp', 'payamito-woocommerce'));
			}
		}

		/**
		 * enqueue scripts
		 *
		 * @since   1.0.0
		 */
		public function wp_enqueue_scripts()
		{
			if (!function_exists('is_checkout') || is_checkout() == false) {
				return;
			}
			global $pwoo_otp_options;
			wp_enqueue_script("payamito-woocommerce-front-app-js", PAYAMITO_WC_URL . "assets/js/app.js", ['jquery'], false, true);
			$meta_key ='phone|billing-phone|billing_phone' ;
			wp_localize_script("payamito-woocommerce-front-app-js", "PAYAMITO_WC_OTP_CONFIG", [
				'configs' => [
					'resend_time' =>     $pwoo_otp_options['again_send_time_otp'] ?? 60,
					'phone_field_id' =>  $meta_key,
					'otp_field_config' => [
						'title' =>        $pwoo_otp_options['otp_title'],
						'placeholder' =>  $pwoo_otp_options['otp_placeholder'],
						'send_btn_text' => esc_html__('Send OTP', 'payamito-woocommerce'),
					],
				]
			]);

			wp_enqueue_script("payamito-woocommerce-front-notification-js", PAYAMITO_WC_URL . "assets/js/notification.js", ['jquery'], false, true);

			wp_localize_script("payamito-woocommerce-front-notification-js", "general", [
				'ajaxurl'     => admin_url('admin-ajax.php'),
				"OTP_Success" => __("Send OTP success", "payamito-woocommerce"),
				"OTP_Fail"    => __("Send OTP failed", "payamito-woocommerce"),
				'Send'        => __("Send request failed please contact with support team ", "payamito-woocommerce"),
				'OTP_Correct' => __("OTP is wrong", "payamito-woocommerce"),
				'invalid'     => __("phone_number number is incorrect", "payamito-woocommerce"),
				'error'       => __("Error", "payamito-woocommerce"),
				'success'     => __("Success", "payamito-woocommerce"),
				"warning"     => __("Warning", "payamito-woocommerce"),
				'enter'       => __('Enter OTP number ', 'payamito-woocommerce'),
				'second'      => __('Second', 'payamito-woocommerce'),
			]);

			wp_enqueue_script("payamito-woocommerce-front-spinner-js", PAYAMITO_WC_URL . "assets/js/spinner.js", ['jquery'], false, true);

			////////style///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			wp_enqueue_style("payamito-woocommerce-front-app-css", PAYAMITO_WC_URL . "assets/css/app.css");

			wp_enqueue_style("payamito-woocommerce-front-notification-css", PAYAMITO_WC_URL . "assets/css/notification.css");

			wp_enqueue_style("payamito-woocommerce-front-spinner-css", PAYAMITO_WC_URL . "assets/css/spinner.css");
		}

		/**
		 * handling OTP ajax request
		 *
		 * @return void
		 * @since 1.0.0
		 */

		public function ajax()
		{
			if (!payamito_wc()->functions::is_request("ajax")) {
				wp_die();
			}
			$phone_number = payamito_to_english_number(sanitize_text_field($_REQUEST['phone_number']));

			if (!payamito_verify_moblie_number($phone_number)) {
				return $this->ajax_response(-1, esc_html__('Enter valid phone number', 'payamito-woocommerce'));
			}
			$this->phone_number_confirmation($phone_number);
		}

		/**
		 * phone_number number validation and sending SMS
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function phone_number_confirmation($phone_number)
		{
			global $pwoo_otp_options;

			$options = $pwoo_otp_options;


			if (!payamito_verify_moblie_number($phone_number)) {
				$this->ajax_response(-1, self::message(0));
			}
			Payamito_OTP::payamito_resent_time_check($phone_number, $pwoo_otp_options['again_send_time_otp']);

			if ($options['pattern_active'] == true) {
				$pattern_id = trim($options['pattern_id'], " ");

				$pattern = $this->set_otp_pattern($options['pattern'], $options['number_of_code_otp']);

				$result = payamito_wc()->send->Send_pattern($phone_number, $pattern, $pattern_id);

				if ($result['result'] === true && !empty($this->OTP)) {
					$phone_number = (string) $phone_number;
					$OTP          = (string) $this->OTP;

					Payamito_OTP::payamito_set_session($phone_number, $OTP, 'payamito_wc');

					return $this->ajax_response(1, self::message(1));
				} else {
					return $this->ajax_response(-1, $result['message']);
				}
			} else {
				$messages = trim($options['text']);

				if (empty($messages)) {
					return;
				}
				$messages_value = $this->set_value($messages, $options['number_of_code_otp']);

				$result = payamito_wc()->send->Send($phone_number, $messages_value);

				if ($result === true) {
					Payamito_OTP::payamito_set_session($phone_number, $this->OTP, 'payamito_wc');

					return $this->ajax_response(1, self::message(1));
				} else {
					return $this->ajax_response(-1, $result['message']);
				}
			}
		}

		public function set_otp_pattern($pattern, $count = 4)
		{
			$send_pattern = [];
			foreach ($pattern as $item) {
				switch ($item['opt_tags']) {
					case 'OTP':
					case '{OTP}':
						$this->OTP                             = Payamito_OTP::payamito_generate_otp($count);
						$send_pattern[$item['otp_user_otp']] = $this->OTP;
						break;
					case 'site_name':
					case '{site_name}':
						$send_pattern[$item['otp_user_otp']] = get_bloginfo('name');
						break;
				}
			}

			return $send_pattern;
		}

		public function set_value($text, $count = 4)
		{
			$tags  = ['{site_name}', '{OTP}'];
			$value = [];

			foreach ($tags as $tag) {
				switch ($tag) {
					case "OTP":
					case "{OTP}":
						$this->OTP = Payamito_OTP::payamito_generate_otp($count);
						array_push($value, $this->OTP);
						break;
					case "site_name":
					case "{site_name}":
						array_push($value, get_bloginfo('name'));
						break;
				}
			}

			$message = str_replace($tags, $value, $text);

			return $message;
		}

		/**
		 * ajax response message
		 *
		 * @access public
		 * @return string
		 * @static
		 * @since  1.0.0
		 */
		public static function message($key)
		{
			$messages = [
				__('Phone number number is incorrect', 'payamito-woocommerce'),
				__('OTP sent successfully', 'payamito-woocommerce'),
				__('Failed to send OTP ', 'payamito-woocommerce'),
				__('An unexpected error occurred. Please contact support ', 'payamito-woocommerce'),
				__('Enter OTP number ', 'payamito-woocommerce'),
				__(' OTP is Incorrect ', 'payamito-woocommerce'),
			];

			return $messages[$key];
		}

		/**
		 * ajax response
		 *The response to the OTP request is given in Ajax
		 *
		 * @access public
		 * @since  1.0.0
		 * @static
		 */
		public function ajax_response($type = -1, $message = "", $redirect = null)
		{
			wp_send_json(['e' => $type, 'message' => $message, "re" => $redirect]);
			die;
		}
		/**
		 * Register custom fields after the plugin is safely loaded.
		 * products list custom filed
		 * requied
		 */
		/**
		 * Register the orders field
		 *
		 * @return void
		 * @since  1.0.0
		 */
		function fields()
		{
			if (Functions::is_request('ajax')) {
				return;
			}
			global $pwoo_otp_options;

			if ($pwoo_otp_options['active'] != true) {
				return;
			}
			$phone_number = $this->get_user_phone_number($pwoo_otp_options['meta_key']);

			if ($pwoo_otp_options['nonce'] == true && !empty($phone_number)) {
				return;
			}
		}

		public function get_user_phone_number($meta_key)
		{
			$phone_number = get_user_meta(get_current_user_id(), $meta_key, true);
			if (empty($phone_number)) {
				$phone_number = get_user_meta(get_current_user_id(), 'payamito_phone_number', true);
			}

			return $phone_number;
		}

		public function order_created($order_id)
		{			
			Payamito_OTP::payamito_delete_session($this->phone_number, 'payamito_wc');
		}

		public static function check_once()
		{
			global $options;
			$phone_number = get_user_meta(get_current_user_id(), "phone_number", true);
			if ($phone_number == false) {
				return true;
			}
			if (!isset($options['once_get'])) {
				return false;
			}
			if ($options['once_get'] === true) {
				return true;
			}
		}
	}
endif;
