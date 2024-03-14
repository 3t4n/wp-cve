<?php

/**
 * Spoki Abandoned Carts Class
 */
class Spoki_Abandoned_Carts
{

	protected static $_instance = null;

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{

		$this->define_cart_abandonment_constants();

		add_action('woocommerce_after_checkout_form', array($this, 'cart_abandonment_tracking_script'));

		// trigger abandoned checkout event
		add_action('wp_ajax_spoki_cartflows_save_cart_abandonment_data', array($this, 'save_cart_abandonment_data'));
		add_action('wp_ajax_nopriv_spoki_cartflows_save_cart_abandonment_data', array($this, 'save_cart_abandonment_data'));

		add_action('rest_api_init', function () {
			register_rest_route('api/v1', '/getWoocommerceInfo', array(
				'methods' => 'GET',
				'callback' => array($this, 'getWoocommerceInfo'),
				'permission_callback' => true
			));
		});
		add_action('rest_api_init', function () {
			register_rest_route('api/v1', '/getAccessToken', array(
				'methods' => 'GET',
				'callback' => array($this, 'getAccessToken'),
				'permission_callback' => true
			));
		});

		add_action('rest_api_init', function () {
			register_rest_route('api/v1', '/getOrderUrl', array(
				'methods' => 'GET',
				'callback' => array($this, 'getOrderUrl'),
				'permission_callback' => true
			));
		});

		add_action('rest_api_init', function () {
			register_rest_route('api/v1', '/setCartAsContacted', array(
				'methods' => 'POST',
				'callback' => array($this, 'setCartAsContacted'),
				'permission_callback' => false
			));
		});

		add_filter('jwt_auth_whitelist', function ($endpoints) {
			return array(
				'/wp-json/api/v1/getWoocommerceInfo',
				'/wp-json/api/v1/getOrderUrl',
				'/wp-json/api/v1/getAccessToken',
				'/wp-json/api/v1/setCartAsContacted',
			);
		});

		add_filter('wp', array($this, 'restore_cart_abandonment_data'), 10);
		add_action('woocommerce_order_status_changed', array($this, 'spoki_ca_update_order_status'), 999, 3);
	}

	/**
	 *  Initialise all the constants
	 */
	public function define_cart_abandonment_constants()
	{
		define('SPOKI_CARTFLOWS_CART_ABANDONMENT_TRACKING_URL', SPOKI_URL . 'modules/abandoned-carts/');
		define('SPOKI_CART_ABANDONED_ORDER', 'abandoned');
		define('SPOKI_CART_COMPLETED_ORDER', 'completed');
		define('SPOKI_CART_LOST_ORDER', 'lost');
		define('SPOKI_CART_NORMAL_ORDER', 'normal');
		define('SPOKI_CART_FAILED_ORDER', 'failed');
		define('SPOKI_CA_DATETIME_FORMAT', 'Y-m-d H:i:s');
	}

	public function get_spoki_setting_by_meta($meta_key)
	{
		global $wpdb;
		$spoki_setting_table = $wpdb->prefix . SPOKI_SETTING_TABLE;

		$res = $wpdb->get_row(
			$wpdb->prepare("select * from $spoki_setting_table where meta_key = %s", $meta_key) // phpcs:ignore
		);

		if ($res != null) {
			return $res->meta_value;
		}

		return null;
	}

	public function set_spoki_setting_by_meta($input_meta_key, $input_meta_value)
	{
		global $wpdb;
		$spoki_setting_tb = $wpdb->prefix . SPOKI_SETTING_TABLE;

		$meta_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $spoki_setting_tb WHERE meta_key = %s ", $input_meta_key));

		$meta_data = array(
			$input_meta_key => $input_meta_value
		);

		if ((!$meta_count)) {
			foreach ($meta_data as $meta_key => $meta_value) {
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO $spoki_setting_tb ( `meta_key`, `meta_value` ) 
						VALUES ( %s, %s )",
						$meta_key,
						$meta_value
					)
				);
			}
		} else {
			foreach ($meta_data as $meta_key => $meta_value) {
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE $spoki_setting_tb SET meta_value = '$meta_value' WHERE meta_key = %s",
						$meta_key
					)
				);
			}
		}

		return true;

	}

	public function cart_abandonment_tracking_script()
	{
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift($roles);

		global $post;
		wp_enqueue_script(
			'spoki-abandoned-carts-tracking',
			SPOKI_CARTFLOWS_CART_ABANDONMENT_TRACKING_URL . 'assets/js/abandoned-carts-tracking.js',
			array('jquery'),
			"1.0",
			true
		);

		$vars = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'_nonce' => wp_create_nonce('spoki_cartflows_save_cart_abandonment_data'),
			'_post_id' => get_the_ID(),
			'_show_gdpr_message' => false,
			'_gdpr_message' => get_option('spoki_ca_gdpr_message'),
			'_gdpr_nothanks_msg' => __('No Thanks', 'spoki'),
			'_gdpr_after_no_thanks_msg' => __('You won\'t receive further emails from us, thank you!', 'spoki'),
			'_default_prefix' => (Spoki()->options['default_prefix'] ?? '') . '',
			'enable_ca_tracking' => true,
		);

		wp_localize_script('spoki-abandoned-carts-tracking', 'SpokiACVars', $vars);
	}

	public function save_cart_abandonment_data()
	{
		$post_data = $this->sanitize_post_data();
		if (isset($post_data['spoki_email'])) {
			$user_email = sanitize_email($post_data['spoki_email']);
			global $wpdb;
			$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;

			// Verify if email is already exists.
			$session_id = WC()->session->get('spoki_session_id');
			$session_checkout_details = null;
			if (isset($session_id)) {
				$session_checkout_details = $this->get_checkout_details($session_id);
			} else {
				$session_checkout_details = $this->get_checkout_details_by_email($user_email);
				if ($session_checkout_details) {
					$session_id = $session_checkout_details->session_id;
					WC()->session->set('spoki_session_id', $session_id);
				} else {
					$session_id = md5(uniqid(wp_rand(), true));
				}
			}

			$checkout_details = $this->prepare_abandonment_data($session_id, $post_data);

			if (isset($session_checkout_details) && $session_checkout_details->order_status === "completed") {
				WC()->session->__unset('spoki_session_id');
				$session_id = md5(uniqid(wp_rand(), true));
			}


			if ((!is_null($session_id)) && !is_null($session_checkout_details)) {

				// Updating row in the Database where users Session id = same as prevously saved in Session.
				$wpdb->update(
					$cart_abandonment_table,
					$checkout_details,
					array('session_id' => $session_id)
				);
				$this->webhook_abandonedCheckout_to_spoki($session_id, '', false);
			} else {

				$checkout_details['session_id'] = sanitize_text_field($session_id);
				// Inserting row into Database.
				$wpdb->insert(
					$cart_abandonment_table,
					$checkout_details
				);

				// Storing session_id in WooCommerce session.
				WC()->session->set('spoki_session_id', $session_id);
				$this->webhook_abandonedCheckout_to_spoki($session_id, '', true);
			}

			wp_send_json_success();
		}
	}

	public function spoki_ca_update_order_status($order_id, $old_order_status, $new_order_status)
	{
		if ((SPOKI_CART_FAILED_ORDER === $new_order_status)) {
			return;
		}

		$session_id = null;

		if (WC()->session) {
			$session_id = WC()->session->get('spoki_session_id');
		}

		if ($order_id && $session_id) {
			$session_id = WC()->session->get('spoki_session_id');
			$captured_data = $this->get_checkout_details($session_id);
			if ($captured_data) {
				$captured_data->order_status = SPOKI_CART_COMPLETED_ORDER;
				$this->webhook_abandonedCheckout_to_spoki($session_id, SPOKI_CART_COMPLETED_ORDER, false);

				global $wpdb;
				$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
				$wpdb->update(
					$cart_abandonment_table,
					[
						"order_status" => SPOKI_CART_COMPLETED_ORDER,
						"session_id" => NULL,
						"order_id" => $order_id,
						"email" => NULL,
						"checkout_id" => NULL
					],
					array('session_id' => $session_id)
				);
			}
		}

		if ($session_id) {
			WC()->session->__unset('spoki_session_id');
		}
	}

	public function spoki_resend_ca_notification($session_id): bool
	{
		$checkoutDetails = $this->get_checkout_details($session_id);
		$recontacted = Spoki()->notify_abandoned_cart_info($session_id, $checkoutDetails, 'order.abandoned_cart');
		if ($recontacted) {
			global $wpdb;
			$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
			$wpdb->update(
				$cart_abandonment_table,
				['recontacted' => 1],
				['session_id' => $session_id]
			);
		}
		return $recontacted;
	}

	/**
	 * Unschedule hook for versions <= v2.8.1
	 */
	public function unschedule_hook()
	{
		$timestamp = wp_next_scheduled('spoki_abandoned_carts_cron_hook');
		if ($timestamp) {
			wp_unschedule_event($timestamp, 'spoki_abandoned_carts_cron_hook');
		}
	}

	public function restore_cart_abandonment_data($fields = array())
	{
		// Restore only if user is not logged in.
		$spoki_options = Spoki()->options;
		$custom_checkout_session_id_param = (isset($spoki_options['custom_checkout_session_id_param']) && $spoki_options['custom_checkout_session_id_param'] != "") ? $spoki_options['custom_checkout_session_id_param'] : "session_id";

		$spoki_session_id = filter_input(INPUT_GET, $custom_checkout_session_id_param, FILTER_SANITIZE_STRING);
		if (!$spoki_session_id) {
			$url_components = parse_url(home_url($_SERVER['REQUEST_URI']));
			if (isset($url_components['query'])) {
				parse_str($url_components['query'], $params);
				if (isset($params[$custom_checkout_session_id_param])) {
					$spoki_session_id = $params[$custom_checkout_session_id_param];
				}
			}
		}

		if (!$spoki_session_id && $custom_checkout_session_id_param != 'session_id') {
			$custom_checkout_session_id_param = 'session_id';
			$spoki_session_id = filter_input(INPUT_GET, $custom_checkout_session_id_param, FILTER_SANITIZE_STRING);
			if (!$spoki_session_id) {
				$url_components = parse_url(home_url($_SERVER['REQUEST_URI']));
				if (isset($url_components['query'])) {
					parse_str($url_components['query'], $params);
					if (isset($params[$custom_checkout_session_id_param])) {
						$spoki_session_id = $params[$custom_checkout_session_id_param];
					}
				}
			}
		}

		if (!$spoki_session_id) {
			return false;
		}

		global $woocommerce;
		$result = array();

		$result = $this->get_checkout_details($spoki_session_id);
		if (isset($result) && (SPOKI_CART_ABANDONED_ORDER === $result->order_status || SPOKI_CART_LOST_ORDER === $result->order_status)) {
			WC()->session->set('spoki_session_id', $spoki_session_id);
		}
		if ($result) {
			$cart_content = unserialize($result->cart_contents);

			if ($cart_content) {
				$woocommerce->cart->empty_cart();
				wc_clear_notices();
				foreach ($cart_content as $cart_item) {

					$cart_item_data = array();
					$variation_data = array();
					$id = $cart_item['product_id'];
					$qty = $cart_item['quantity'];

					// Skip bundled products when added main product.
					if (isset($cart_item['bundled_by'])) {
						continue;
					}

					if (isset($cart_item['variation'])) {
						foreach ($cart_item['variation'] as $key => $value) {
							$variation_data[$key] = $value;
						}
					}

					$cart_item_data = $cart_item;

					$woocommerce->cart->add_to_cart($id, $qty, $cart_item['variation_id'], $variation_data, $cart_item_data);
				}

				if (isset($token_data['spoki_coupon_code']) && !$woocommerce->cart->applied_coupons) {
					$woocommerce->cart->add_discount($token_data['spoki_coupon_code']);
				}
			}
			$other_fields = unserialize($result->other_fields);

			$parts = explode(',', $other_fields['spoki_location']);
			if (count($parts) > 1) {
				$country = $parts[0];
				$city = trim($parts[1]);
			} else {
				$country = $parts[0];
				$city = '';
			}

			foreach ($other_fields as $key => $value) {
				$key = str_replace('spoki_', '', $key);
				$_POST[$key] = sanitize_text_field($value);
			}
			$_POST['billing_first_name'] = sanitize_text_field($other_fields['spoki_first_name']);
			$_POST['billing_last_name'] = sanitize_text_field($other_fields['spoki_last_name']);
			$_POST['billing_phone'] = sanitize_text_field($other_fields['spoki_phone_number']);
			$_POST['billing_email'] = sanitize_email($result->email);
			$_POST['billing_city'] = sanitize_text_field($city);
			$_POST['billing_country'] = sanitize_text_field($country);

		}
		return $fields;
	}

	public function prepare_abandonment_data($session_id, $post_data = array())
	{

		if (function_exists('WC')) {

			// Retrieving cart total value and currency.
			$cart_total = WC()->cart->total;

			// Retrieving cart products and their quantities.
			$products = WC()->cart->get_cart();
			$current_time = current_time(SPOKI_CA_DATETIME_FORMAT);

			$phone = trim(sanitize_text_field($post_data['spoki_phone']));
			if ($phone != '' && $phone[0] != '+') {
				$default_prefix = isset($this->options['default_prefix']) ? $this->options['default_prefix'] : '';
				$phone = $default_prefix . $phone;
			}

			$other_fields = array(
				'spoki_billing_company' => $post_data['spoki_billing_company'],
				'spoki_billing_address_1' => $post_data['spoki_billing_address_1'],
				'spoki_billing_address_2' => $post_data['spoki_billing_address_2'],
				'spoki_billing_state' => $post_data['spoki_billing_state'],
				'spoki_billing_postcode' => $post_data['spoki_billing_postcode'],
				'spoki_shipping_first_name' => $post_data['spoki_shipping_first_name'],
				'spoki_shipping_last_name' => $post_data['spoki_shipping_last_name'],
				'spoki_shipping_company' => $post_data['spoki_shipping_company'],
				'spoki_shipping_country' => $post_data['spoki_shipping_country'],
				'spoki_shipping_address_1' => $post_data['spoki_shipping_address_1'],
				'spoki_shipping_address_2' => $post_data['spoki_shipping_address_2'],
				'spoki_shipping_city' => $post_data['spoki_shipping_city'],
				'spoki_shipping_state' => $post_data['spoki_shipping_state'],
				'spoki_shipping_postcode' => $post_data['spoki_shipping_postcode'],
				'spoki_order_comments' => $post_data['spoki_order_comments'],
				'spoki_first_name' => $post_data['spoki_name'],
				'spoki_last_name' => $post_data['spoki_surname'],
				'spoki_phone_number' => $phone,
				'spoki_location' => $post_data['spoki_country'] . ', ' . $post_data['spoki_city'],
				'spoki_session_id' => $session_id ?? null,
			);

			$checkout_details = array(
				'email' => $post_data['spoki_email'],
				'phone' => $phone,
				'cart_contents' => serialize($products),
				'cart_total' => sanitize_text_field($cart_total),
				'time' => sanitize_text_field($current_time),
				'other_fields' => serialize($other_fields),
				'checkout_id' => $post_data['spoki_post_id'],
			);
		}
		return $checkout_details;
	}

	public function sanitize_post_data()
	{

		$input_post_values = array(
			'spoki_billing_company' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_email' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_EMAIL,
			),
			'spoki_billing_address_1' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_billing_address_2' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_billing_state' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_billing_postcode' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_first_name' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_last_name' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_company' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_country' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_address_1' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_address_2' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_city' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_state' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_shipping_postcode' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_order_comments' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_name' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_surname' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_phone' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_country' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_city' => array(
				'default' => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'spoki_post_id' => array(
				'default' => 0,
				'sanitize' => FILTER_SANITIZE_NUMBER_INT,
			),
		);

		$sanitized_post = array();
		foreach ($input_post_values as $key => $input_post_value) {

			if (isset($_POST[$key])) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
				$sanitized_post[$key] = filter_input(INPUT_POST, $key, $input_post_value['sanitize']);
			} else {
				$sanitized_post[$key] = $input_post_value['default'];
			}
		}
		return $sanitized_post;

	}

	public function get_checkout_details($spoki_session_id)
	{
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$result = $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE session_id = %s', $spoki_session_id) // phpcs:ignore
		);
		return $result;
	}

	public function get_order_checkout_details($order_id)
	{
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$result = $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE order_id = %s', $order_id) // phpcs:ignore
		);
		return $result;
	}

	public function get_checkout_details_by_email($email)
	{
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$result = $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE email = %s AND `order_status` IN ( %s, %s )', $email, SPOKI_CART_ABANDONED_ORDER, SPOKI_CART_NORMAL_ORDER) // phpcs:ignore
		);
		return $result;
	}

	public function webhook_abandonedCheckout_to_spoki($session_id, $order_status, $is_create)
	{
		$checkoutDetails = $this->get_checkout_details($session_id);
		$url = $this->get_spoki_setting_by_meta('spoki_domain') . "/api/v1/woocommerce/webhookCheckout";
		$other_fields = unserialize($checkoutDetails->other_fields);
		$parts = explode(',', $other_fields['spoki_location']);
		$country = $parts[0];

		$phone = trim(sanitize_text_field(isset($other_fields['spoki_phone_number']) ? $other_fields['spoki_phone_number'] : ''));
		if ($phone != '' && $phone[0] != '+') {
			$default_prefix = isset($this->options['default_prefix']) ? $this->options['default_prefix'] : '';
			$phone = $default_prefix . $phone;
		}

		$checkout_base_url = (isset($this->options['custom_checkout_url']) && $this->options['custom_checkout_url'] != "") ? $this->options['custom_checkout_url'] : wc_get_page_permalink('checkout');
		$custom_checkout_session_id_param = (isset($this->options['custom_checkout_session_id_param']) && $this->options['custom_checkout_session_id_param'] != "") ? $this->options['custom_checkout_session_id_param'] : "session_id";

		$options = [
			'body' => json_encode([
				'sessionId' => $checkoutDetails->session_id,
				'email' => $checkoutDetails->email,
				'phone' => $phone,
				'country' => sanitize_text_field($country),
				'name' => sanitize_text_field($other_fields['spoki_first_name']) . ' ' . sanitize_text_field($other_fields['spoki_last_name']),
				'total' => $checkoutDetails->cart_total,
				'status' => ($order_status == '' ? $checkoutDetails->order_status : $order_status),
				'checkoutUrl' => $checkout_base_url . '?' . $custom_checkout_session_id_param . '=' . $checkoutDetails->session_id,
				'currency' => get_woocommerce_currency(),
				'wordpressDomain' => get_home_url()
			]),
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'timeout' => 60,
			'redirection' => 5,
			'blocking' => true,
			'httpversion' => '1.0',
			'sslverify' => false,
			'data_format' => 'body',
		];
		wp_remote_post($url, $options);

		if ($order_status == '') {
			Spoki()->notify_abandoned_cart_info($session_id, $checkoutDetails, $is_create ? 'checkout.created' : 'checkout.updated');
		}
		return true;
	}

	public function getWoocommerceInfo()
	{
		$accessToken = sanitize_text_field($_GET['accessToken']);
		if ($accessToken == $this->get_spoki_setting_by_meta('access_token')) {
			return array(
				"currency" => get_woocommerce_currency(),
				"shopName" => $this->get_spoki_setting_by_meta('shop_name'),
				"email" => $this->get_spoki_setting_by_meta('email'),
				"whatsappNumber" => $this->get_spoki_setting_by_meta('whatsapp_number'),
				"pluginActivated" => $this->get_spoki_setting_by_meta('plugin_activated')
			);
		} else {
			return null;
		}
	}

	public function getAccessToken()
	{

		$code = sanitize_text_field($_GET['code']);
		if ($code == $this->get_spoki_setting_by_meta('code')) {
			$access_token = $this->get_spoki_setting_by_meta("access_token");
			return array(
				"Access_Token" => $this->get_spoki_setting_by_meta("access_token"),
				"WoocommerceInfo" => array(
					"Currency" => get_woocommerce_currency(),
					"ShopName" => $this->get_spoki_setting_by_meta("shop_name"),
					"WhatsappNumber" => $this->get_spoki_setting_by_meta("whatsapp_number"),
					"Email" => $this->get_spoki_setting_by_meta("email")
				)
			);
		} else {
			return null;
		}
	}

	public function getOrderUrl()
	{
		$accessToken = sanitize_text_field($_GET['accessToken']);
		$order_id = sanitize_text_field($_GET['order_id']);
		if ($accessToken == $this->get_spoki_setting_by_meta('access_token')) {
			$order = wc_get_order($order_id);

			if (!$order) {
				return null;
			}

			return array(
				"order_url" => $order->get_checkout_order_received_url()
			);
		} else {
			return null;
		}
	}

	public function setCartAsContacted($req)
	{
		if (!isset($req['session_id'])) {
			$response = ["message" => "Missing required field 'session_id'"];
			$res = new WP_REST_Response($response);
			$res->set_status(400);
			return ['req' => $res];
		}

		$session_id = sanitize_text_field($req['session_id']);
		$session_checkout_details = $this->get_checkout_details($session_id);
		if (!$session_checkout_details) {
			$response = ["message" => "Checkout not found"];
			$res = new WP_REST_Response($response);
			$res->set_status(404);
			return ['req' => $res];
		}

		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$contacted = $wpdb->update(
			$cart_abandonment_table,
			['contacted' => 1, 'order_status' => SPOKI_CART_ABANDONED_ORDER],
			array('session_id' => $session_id)
		);

		if ($contacted) {
			$response = ["message" => "Checkout set as contacted"];
			$res = new WP_REST_Response($response);
			$res->set_status(200);
			return ['req' => $res];
		} else {
			$response = ["message" => "Can't set checkout as contacted"];
			$res = new WP_REST_Response($response);
			$res->set_status(500);
		}
	}

	public function save_webhook_url($spokiDomain)
	{
		$userID = get_current_user_id();

		$webhook = new WC_Webhook($this->get_spoki_setting_by_meta("webhook_order_deleted_id"));
		$webhook->set_user_id($userID);
		$webhook->set_topic("order.deleted");
		$webhook->set_secret("secret");
		$webhook->set_delivery_url($spokiDomain . "/api/v1/woocommerce/webhookOrders/order_deleted");
		$webhook->set_status("active");
		$webhook->set_name("WP_SPOKI_ORDER_DELETED");
		$this->set_spoki_setting_by_meta("webhook_order_deleted_id", $webhook->save());

		$webhook = new WC_Webhook($this->get_spoki_setting_by_meta("webhook_order_updated_id"));
		$webhook->set_user_id($userID);
		$webhook->set_topic("order.updated");
		$webhook->set_secret("secret");
		$webhook->set_delivery_url($spokiDomain . "/api/v1/woocommerce/webhookOrders/order_updated");
		$webhook->set_status("active");
		$webhook->set_name("WP_SPOKI_ORDER_UPDATED");
		$this->set_spoki_setting_by_meta("webhook_order_updated_id", $webhook->save());

		$webhook = new WC_Webhook($this->get_spoki_setting_by_meta("webhook_order_created_id"));
		$webhook->set_user_id($userID);
		$webhook->set_topic("order.created");
		$webhook->set_secret("secret");
		$webhook->set_delivery_url($spokiDomain . "/api/v1/woocommerce/webhookOrders/order_created");
		$webhook->set_status("active");
		$webhook->set_name("WP_SPOKI_ORDER_CREATED");
		$this->set_spoki_setting_by_meta("webhook_order_created_id", $webhook->save());
	}

	public function disable_webhook_url($spokiDomain)
	{
		$userID = get_current_user_id();

		$webhook = new WC_Webhook($this->get_spoki_setting_by_meta("webhook_order_deleted_id"));
		$webhook->set_user_id($userID);
		$webhook->set_topic("order.deleted");
		$webhook->set_secret("secret");
		$webhook->set_delivery_url($spokiDomain . "/api/v1/woocommerce/webhookOrders/order_deleted");
		$webhook->set_status("disabled");
		$webhook->set_name("WP_SPOKI_ORDER_DELETED");
		$this->set_spoki_setting_by_meta("webhook_order_deleted_id", $webhook->save());

		$webhook = new WC_Webhook($this->get_spoki_setting_by_meta("webhook_order_updated_id"));
		$webhook->set_user_id($userID);
		$webhook->set_topic("order.updated");
		$webhook->set_secret("secret");
		$webhook->set_delivery_url($spokiDomain . "/api/v1/woocommerce/webhookOrders/order_updated");
		$webhook->set_status("disabled");
		$webhook->set_name("WP_SPOKI_ORDER_UPDATED");
		$this->set_spoki_setting_by_meta("webhook_order_updated_id", $webhook->save());

		$webhook = new WC_Webhook($this->get_spoki_setting_by_meta("webhook_order_created_id"));
		$webhook->set_user_id($userID);
		$webhook->set_topic("order.created");
		$webhook->set_secret("secret");
		$webhook->set_delivery_url($spokiDomain . "/api/v1/woocommerce/webhookOrders/order_created");
		$webhook->set_status("disabled");
		$webhook->set_name("WP_SPOKI_ORDER_CREATED");
		$this->set_spoki_setting_by_meta("webhook_order_created_id", $webhook->save());
	}

	public function rand_string($length)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$size = strlen($chars);
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= $chars[rand(0, $size - 1)];
		}
		return $str;
	}

	/**
	 *  Get Attributable revenue.
	 *  Represents the revenue generated by this campaign.
	 *
	 * @param string $type abondened|completed.
	 */
	public function get_report_by_type($type = SPOKI_CART_ABANDONED_ORDER)
	{
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$attributable_revenue = $wpdb->get_row(
			$wpdb->prepare("SELECT  SUM(`cart_total`) as revenue, count('*') as no_of_orders  FROM {$cart_abandonment_table} WHERE `order_status` = %s AND `contacted` = 1  ", $type), // phpcs:ignore
			ARRAY_A
		);
		return $attributable_revenue;
	}

	/**
	 *  Get Recontacted report.
	 */
	public function get_recontacted_recovered_report()
	{
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$attributable_revenue = $wpdb->get_row(
			$wpdb->prepare("SELECT  SUM(`cart_total`) as revenue, count('*') as no_of_orders  FROM {$cart_abandonment_table} WHERE `recontacted` = 1 AND `order_status` = %s", SPOKI_CART_COMPLETED_ORDER), // phpcs:ignore
			ARRAY_A
		);
		return $attributable_revenue;
	}

	/**
	 *  Get Recontactable carts.
	 *
	 * @param string $type abondened|completed.
	 */
	public function get_recontactable_carts()
	{
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . SPOKI_ABANDONMENT_TABLE;
		$results = $wpdb->get_results(
			$wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE `order_status` = %s AND `contacted` = 1 AND `recontacted` = 0 ORDER BY `time`', SPOKI_CART_ABANDONED_ORDER) // phpcs:ignore
		);
		return $results;
	}
}
