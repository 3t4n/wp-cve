<?php
/*

Copyright 2020 Refersion, Inc. (email : helpme@refersion.com)

This file is part of Refersion for WooCommerce.

Refersion for WooCommerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Refersion for WooCommerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Refersion for WooCommerce. If not, see <http://www.gnu.org/licenses/>.

*/

class Refersion
{

	/**
	 * Grab the user's IP
	 */
	public static function refersion_get_client_ip()
	{

		$ipAddressSetting = $defaultOrderType = Refersion::refersion_get_ip_address_setting();
		$ipaddress = null;

		if ($ipAddressSetting === 'AUTO') {

			if (getenv('HTTP_CLIENT_IP')) {
				$ipaddress = getenv('HTTP_CLIENT_IP');
			}

			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			}

			if (getenv('HTTP_X_FORWARDED')) {
				$ipaddress = getenv('HTTP_X_FORWARDED');
			}

			if (getenv('HTTP_FORWARDED_FOR')) {
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			}

			if (getenv('HTTP_FORWARDED')) {
				$ipaddress = getenv('HTTP_FORWARDED');
			}

			if (getenv('REMOTE_ADDR')) {
				$ipaddress = getenv('REMOTE_ADDR');
			}

		} else {

			// Non-automatic option chosen so try to use that if available
			if (getenv($ipAddressSetting)) {
				$ipaddress = getenv($ipAddressSetting);
			}

		}

		return $ipaddress;

	}

	/**
	 * Generates a new cart_id for Refersion
	 */
	public static function refersion_generate_cart_id($length = REFERSION_CART_ID_LENGTH)
	{

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomString;

	}

	/**
	 * Get the item price choice a user has picked in the admin of the plugin.
	 *
	 * @return string
	 */
	public static function referion_get_item_price_choice()
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		$var = $options['refersion_item_price_choice'];
		if (empty($var)) {
			$var = 'PRODUCT';
		}

		return $var;

	}

	/**
	 * Check if Woocomerce already installed or not
	 */
	public static function check_woocomerce()
	{

		// Require parent plugin
		if (!is_plugin_active('woocommerce/woocommerce.php') and current_user_can('activate_plugins') and class_exists('WooCommerce')) {

			// Stop activation redirect and show error
			wp_die('Sorry, but this plugin requires the Woocommerce to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');

		}

	}

	/**
	 * Create Refersion DB table upon plugin installation
	 */
	public static function refersion_activation_db()
	{

		global $wpdb;

		$table_name = REFERSION_WC_ORDERS_TABLE;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
				wc_order_id bigint(20) NOT NULL,
				created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				refersion_cart_id char(50) NOT NULL,
				refersion_sent_status enum('PENDING','SENT') DEFAULT 'PENDING',
				ip_address char(25) DEFAULT NULL,
				KEY refersion_sent_status (refersion_sent_status),
				KEY wc_order_id (wc_order_id),
				KEY refersion_cart_id (refersion_cart_id)
			) $charset_collate;
		";

		// Update using wp-admin function
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}

	public static function refersion_wp_enqueue_scripts()
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Only run if enabled
		if ($options['refersion_status'] && !empty($options['refersion_public_api_key'])) {

			// Add tracking script
			if (Refersion::refersion_get_tracking_version() === 'v3') {

				$subdomain = 'www';

				// Get subdomain, if any
				if (!empty($options['refersion_subdomain'])) {
					$subdomain = $options['refersion_subdomain'];
				}

				wp_enqueue_script('refersion-wc-tracking', '//' . $subdomain . '.refersion.com/tracker/v3/' . $options['refersion_public_api_key'] . '.js', array(), false, false);

			} else {

				$rfsn_vars = array(
					"public_key" => $options['refersion_public_api_key']
				);

				wp_register_script("refersion-wc-tracking", rtrim(plugin_dir_url(__FILE__), '/') . "/rfsn_v4_main.js?ver=4.0.0", array(), '4.0.0', false);
				wp_enqueue_script("refersion-wc-tracking");
				wp_localize_script("refersion-wc-tracking", "rfsn_vars", $rfsn_vars);

			}

		}

	}

	public static function refersion_footer()
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Only run if enabled
		if ($options['refersion_status'] && !empty($options['refersion_public_api_key'])) {

			if (Refersion::refersion_get_tracking_version() === 'v3') {
				echo '<!-- REFERSION TRACKING: BEGIN --><script>_refersion();</script><!-- REFERSION TRACKING: END -->';
			} else {
				echo '<!-- REFERSION TRACKING: BEGIN --><script>!function(e,n,t,i,o,c,s,a){e.TrackingSystemObject="r",(s=n.createElement(t)).async=1,s.src="https://cdn.refersion.com/refersion.js",s.onload=function(){r.pubKey="' . $options['refersion_public_api_key'] . '",r.settings.fp_off=!1;r.initializeXDLS().then(()=>{r.launchDefault()})},(a=n.getElementsByTagName(t)[0]).parentNode.insertBefore(s,a)}(window,document,"script");</script><!-- REFERSION TRACKING: END -->';
			}
		}

	}

	/**
	 * Add cart_id into database after the order is complete
	 */
	public static function refersion_woocommerce_new_order($order_id)
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Only run if enabled
		if ($options['refersion_status'] and strlen($options['refersion_public_api_key']) > 0) {

			global $wpdb;

			// Generate a cart_id
			$refersion_cart_id = Refersion::refersion_generate_cart_id();

			// Insert the cart_id, user's IP address and WC order into the Refersion DB table
			$sql = "INSERT INTO `" . REFERSION_WC_ORDERS_TABLE . "` (`wc_order_id`,`refersion_cart_id`,`ip_address`) VALUES (%d, %s, %s)";
			$sql_prep = $wpdb->prepare($sql, array($order_id, $refersion_cart_id, Refersion::refersion_get_client_ip()));
			$wpdb->query($sql_prep);

		}

	}

	/**
	 * Refersion JS code for the thank you page
	 */
	public static function refersion_woocommerce_thankyou($order_id)
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Only run if enabled
		if ($options['refersion_status'] and strlen($options['refersion_public_api_key']) > 0) {

			global $wpdb;

			// Get the cart_id from the Refersion DB table
			$sql = "SELECT `refersion_cart_id` FROM `" . REFERSION_WC_ORDERS_TABLE . "` WHERE (`wc_order_id`  = %d)";
			$refersion_cart_id = trim(@$wpdb->get_var($wpdb->prepare($sql, array($order_id))));

			if (strlen($refersion_cart_id) > 0) {

				// Use Wordpress script loader
				$rfsn_vars = array(
					"cti" => $refersion_cart_id
				);

				// Load v4 or v3 version of JS depending on the user settings
				if (Refersion::refersion_get_tracking_version() === 'v3') {
					wp_register_script("scripts_rfsn", rtrim(plugin_dir_url(__FILE__), '/') . "/rfsn.js?ver=4.6.0", array(), '4.6.0', false);
				} else {
					wp_register_script("scripts_rfsn", rtrim(plugin_dir_url(__FILE__), '/') . "/rfsn_v4_cart.js?ver=4.6.0", array(), '4.6.0', false);
				}

				wp_enqueue_script("scripts_rfsn");
				wp_localize_script("scripts_rfsn", "rfsn_vars", $rfsn_vars);

			}

		}

	}

	/**
	 * Refersion webhook cURL call
	 */
	public static function refersion_curl_post($order_data)
	{

		$options = get_option('refersion_settings');
		$order_data_json = json_encode($order_data);

		// The URL that you are posting to
		$url = 'https://inbound-webhooks.refersion.com/woocommerce/orders/paid';

		// Start cURL
		$curl = curl_init($url);

		// Verify that our SSL is active (for added security)
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

		// Send as a POST
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

		// The JSON data that you have already compiled
		curl_setopt($curl, CURLOPT_POSTFIELDS, $order_data_json);

		// Return the response
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// Set headers to be JSON-friendly
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($order_data_json),
			'Refersion-Public-Key: ' . $options['refersion_public_api_key'],
			'Refersion-Secret-Key: ' . $options['refersion_secret_api_key']
		));

		// Seconds (5) before giving up
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);

		// Execute post, capture response (if any) and status code
		$result = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		// Close connection
		curl_close($curl);

		return $result;

	}

	/**
	 * Determines the order ID format based on whether an order prefix is set or not
	 *
	 * @param $order_id - The woocommerce order ID
	 *
	 * @return string - the order id with or without prefix
	 */
	public static function refersion_order_id_format($order_id)
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Figure out the Order ID to send to Refersion based on prefix setting (prefix is only reflected in Refersion)
		if (isset($options['refersion_order_prefix']) && strlen($options['refersion_order_prefix']) > 0) {
			$prefix = $options['refersion_order_prefix'];

			return $prefix . $order_id;
		} else {
			return $order_id;
		}
	}

	/**
	 * Builds an array of items for sending an order or cancellation to Refersion
	 *
	 * @param $order - order object from WooCommerce
	 *
	 * @return array - an associative array of items formatted for acceptance in Refersion's order/cancellation APIs
	 */
	public static function refersion_build_item_array($order)
	{

		$item_array = array();
		if (WC()->version < '3.0.0') {

			$items = $order->get_items();

			foreach ($items as $item) {

				// Figure out the SKU, is it a variation or not?
				if (!empty($item['variation_id']) and $item['variation_id'] > 0) {
					$product_id = $item['variation_id'];
				} else {
					$product_id = $item['product_id'];
				}

				// Get the SKU
				$product = new WC_Product($product_id);

				// Build line items
				$product_sku = $product->get_sku();

				// Build item for the order data
				$item = array(
					'sku' => (!empty($product_sku) ? $product_sku : 'N/A'),
					'quantity' => (int)$item['qty'],
					'price' => ((float)$item['line_subtotal'] / (int)$item['qty'])
				);

				// Add item to the order data
				$item_array[] = $item;

				// Just in case
				unset($product_id, $product, $item);

			}

		}

		if (WC()->version >= '3.0.0') {

			$types = array('line_item');
			$items = $order->get_items($types);

			if (Refersion::referion_get_item_price_choice() === 'PRODUCT') {

				foreach ($items as $item) {

					$item_data = $item->get_product();

					// Check if the product is valid
					if (empty($item_data) || !is_object($item_data)) {
						continue;
					}

					try {
						$item_data = $item_data->get_data();
					} catch (Exception $e) {
						// is not a valid product
						continue;
					}

					$item_id = $item->get_variation_id();
					$item_quantity = $item->get_quantity();

					// Build item for the order data
					$item = array(
						'sku' => (!empty($item_data['sku']) ? $item_data['sku'] : 'N/A'),
						'quantity' => (int)$item['qty'],
						'price' => ((float)$item['line_subtotal'] / (int)$item['qty'])
					);

					// Add item to the order data
					$item_array[] = $item;

					// Just in case
					unset($item_data, $item_id, $item_quantity, $item);

				}

			} else {

				foreach ($items as $itemId => $itemData) {

					$productData = $itemData->get_product();

					// Check if the product is valid
					if (empty($productData) || !is_object($productData)) {
						continue;
					}

					try {
						$productData = $productData->get_data();
					} catch (Exception $e) {
						// is not a valid product
						continue;
					}

					$item_id = $itemData->get_variation_id();

					// Figure out the SKU, is it a variation or not?
					if (!empty($item_id) and $item_id > 0) {
						$product_id = $item_id;
					} else {
						$product_id = $productData['id'];
					}

					// Build line items
					$item_array[$product_id]['sku'] = (!empty($productData['sku']) ? $productData['sku'] : 'N/A');
					$item_array[$product_id]['quantity'] = (int)$itemData->get_quantity();
					$item_array[$product_id]['price'] = (float)($itemData->get_subtotal() / $itemData->get_quantity());

					// Just in case
					unset($productData, $item_id);

				}

			}

		}

		return $item_array;
	}

	public static function refersion_woocommerce_send_order($order_id)
	{

		// Array to hold order value to be converted in json
		$order_data = array();

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Only run if Refersion tracking is enabled and both API keys are set
		if ($options['refersion_status'] and strlen($options['refersion_public_api_key']) > 0 and strlen($options['refersion_secret_api_key']) > 0) {

			global $wpdb;

			// Get cart_id and IP address from database
			$sql = "SELECT `refersion_cart_id`, `ip_address` FROM `" . REFERSION_WC_ORDERS_TABLE . "` WHERE (`wc_order_id`  = %d)";
			$results = $wpdb->get_results($wpdb->prepare($sql, array($order_id)), ARRAY_A);
			$cart_id = $results[0]["refersion_cart_id"];
			$ip_address = $results[0]["ip_address"];

			// Get order object
			$order = new WC_Order($order_id);

			// Cart ID
			$order_data['cart_id'] = $cart_id;

			// Order ID
			$order_data['order_id'] = Refersion::refersion_order_id_format($order_id);

			// Order totals
			$orderGrandTotal = $order->get_total();
			$order_data['shipping'] = $order->get_total_shipping();
			$order_data['tax'] = $order->get_total_tax();

			// Coupon codes - if multiple are used, send only the first one
			$discountCode = null;
			if (count($order->get_used_coupons()) > 0) {
				$discountCode = implode(',', $order->get_used_coupons());
			}
			$order_data['discount'] = abs($order->get_total_discount());
			$order_data['discount_code'] = $discountCode;

			// Currency code
			if (WC()->version < '3.0.0') {
				$order_data['currency_code'] = $order->get_order_currency();
			}

			if (WC()->version >= '3.0.0') {
				$order_data['currency_code'] = $order->get_currency();
			}

			// Detect if we have billing info otherwise shipping information will be used
			$first_name = trim(@get_post_meta($order_id, 'billing_first_name', true));
			$address_type = (strlen($first_name) > 0) ? "_billing_" : "_shipping_";

			// Customer first and last name, default to shipping name
			if (WC()->version < '3.0.0') {
				$order_data['customer']['first_name'] = get_post_meta($order_id, $address_type . 'first_name', true);
				$order_data['customer']['last_name'] = get_post_meta($order_id, $address_type . 'last_name', true);
			}

			if (WC()->version >= '3.0.0') {
				$order_data['customer']['first_name'] = $order->get_billing_first_name();
				$order_data['customer']['last_name'] = $order->get_billing_last_name();
			}

			// Get email
			if (WC()->version < '3.0.0') {
				$order_data['customer']['email'] = (!empty($order->billing_email) ? $order->billing_email : null);
			}
			if (WC()->version >= '3.0.0') {
				$order_data['customer']['email'] = (!empty($order->get_billing_email()) ? $order->get_billing_email() : null);
			}

			// Other customer details
			$order_data['customer']['ip_address'] = $ip_address;

			// Get order line items
			$order_data['items'] = Refersion::refersion_build_item_array($order);

			// Send order data via cURL if installed, otherwise use WP backend function
			if (function_exists('curl_version')) {

				// Send using cURL
				$refersion_response = Refersion::refersion_curl_post($order_data);

			} else {

				// Compile data for WP function to send to Refersion
				$post_data = array(
					'headers' => array(
						'Content-Type' => 'application/json',
						'Refersion-Public-Key' => $options['refersion_public_api_key'],
						'Refersion-Secret-Key' => $options['refersion_secret_api_key'],
					), 'body' => json_encode($order_data)
				);

				// Send order data to Refersion via WP
				$refersion_response = wp_remote_post('https://inbound-webhooks.refersion.com/woocommerce/orders/paid', $post_data);

			}

			// Update DB to say that the order was sent to Refersion
			$sql = "UPDATE `" . REFERSION_WC_ORDERS_TABLE . "` SET `refersion_sent_status` = 'SENT' WHERE `wc_order_id` = %d AND `refersion_sent_status` = 'PENDING'";
			$sql_prep = $wpdb->prepare($sql, array($order_id));
			$wpdb->query($sql_prep);

			return $refersion_response;

		}

	}

	/**
	 * Webhook to send cancelled order data into Refersion
	 *
	 * @param string $order_id The Woocommerce order ID.
	 *
	 * @return array Refersion's success response for cancellation endpoint
	 *
	 */
	public static function refersion_woocommerce_order_status_cancelled($order_id)
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		// Only run if Refersion tracking is enabled, cancellation tracking is enabled and both API keys are set
		if ($options['refersion_status'] and strlen($options['refersion_public_api_key']) > 0 and strlen($options['refersion_secret_api_key']) > 0 and $options['refersion_cancellation_tracking']) {

			// Array to hold items for the cancellation conversion
			$cancellation_data = array();

			// Set the order ID to be cancelled
			$cancellation_data['order_id'] = Refersion::refersion_order_id_format($order_id);

			// Get order object from WooCommerce
			$order = new WC_Order($order_id);

			// Get order line items for the cancellation conversion
			$cancellation_data['items'] = Refersion::refersion_build_item_array($order);

			// Compile post data to send a cancellation conversion to Refersion
			$post_data = array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Refersion-Public-Key' => $options['refersion_public_api_key'],
					'Refersion-Secret-Key' => $options['refersion_secret_api_key'],
				), 'body' => json_encode($cancellation_data)
			);

			// Send order data to Refersion via WP Remote Post
			return wp_remote_post('https://www.refersion.com/api/cancel_conversion', $post_data);

		}

	}

	/**
	 * Refersion Webhook to send order completed data.
	 *
	 * @param string $order_id Order ID.
	 *
	 * @return bool|string
	 */
	public static function refersion_woocommerce_order_status_completed($order_id)
	{

		// Check order status setting
		$defaultOrderType = Refersion::refersion_get_order_status_setting();

		// Only run if order status setting is set to COMPLETED
		if ($defaultOrderType == 'COMPLETED') {
			return Refersion::refersion_woocommerce_send_order($order_id);
		}

	}

	/**
	 * Gets the order status setting.
	 *
	 * @return string
	 */
	public static function refersion_get_order_status_setting()
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		$var = $options['refersion_order_status_setting'];

		if (empty($var)) {
			$var = 'COMPLETED';
		}

		return $var;

	}

	/**
	 * Gets the IP Address setting.
	 *
	 * @return string
	 */
	public static function refersion_get_ip_address_setting()
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		$var = $options['refersion_ip_address_setting'];

		if (empty($var)) {
			$var = 'AUTO';
		}

		return $var;

	}

	/**
	 * Refersion Webhook to send order processing data.
	 *
	 * @param string $order_id Order ID.
	 *
	 * @return bool|string
	 */
	public static function refersion_woocommerce_order_status_processing($order_id)
	{

		// Check order status setting
		$defaultOrderType = Refersion::refersion_get_order_status_setting();

		// Only run if order status setting is set to PROCESSING
		if ($defaultOrderType == 'PROCESSING') {
			return Refersion::refersion_woocommerce_send_order($order_id);
		}

	}

	/**
	 * Get the item tracking choice a user has picked in the admin of the plugin.
	 *
	 * @return string
	 */
	public static function refersion_get_tracking_version()
	{

		// Get option set in admin
		$options = get_option('refersion_settings');

		$var = $options['refersion_tracking_version'];
		if (empty($var)) {
			$var = 'v3';
		}

		return $var;

	}

	/**
	 * Refersion Post Purchase code for the thank you page
	 */
	public static function refersion_woocommerce_post_purchase($order_id)
	{

		$options = get_option('refersion_settings');

		// Only run if the post purchase widget setting is enabled and the code has a value
		if ($options['refersion_post_purchase_setting'] && isset($options['refersion_post_purchase_code']) && strlen($options['refersion_post_purchase_code']) > 0) {

			// Get customer details from the woocommerce order
			$order = wc_get_order($order_id);
			$billing_email = $order->get_billing_email();
			$billing_first_name = $order->get_billing_first_name();
			$billing_last_name = $order->get_billing_last_name();
			$client_post_purchase_code = $options['refersion_post_purchase_code'];

			// Set variables for post purchase widget
			$rfsn_pp_vars = array(
				"email" => $billing_email,
				"first_name" => $billing_first_name,
				"last_name" => $billing_last_name,
				"code" => $client_post_purchase_code
			);

			// Register and enqueue the post purchase script
			wp_register_script("scripts_rfsn_pp", plugin_dir_url(__FILE__) . "rfsn_post_purchase.js");
			wp_enqueue_script("scripts_rfsn_pp");
			wp_localize_script("scripts_rfsn_pp", "rfsn_pp_vars", $rfsn_pp_vars);

		}
	}

}
