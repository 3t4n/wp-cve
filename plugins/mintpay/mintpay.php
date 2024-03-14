<?php
/**
 * Plugin Name: Mintpay
 * Plugin URI: https://www.mintpay.lk
 * Description: WooCommerce plugin of Mintpay. Sri Lanka's first buy now pay later platform, that allows consumers to split their payment into 3 interst-free installments.
 * Version: 1.0.4
 * Author: Mintpay (Private) Limited
 * Author URI: https://www.mintpay.lk
 * Text Domain: mintpay
 * Requires at least: 4.6
 * Requires PHP: 5.6
 *
 * @package Mintpay
 */

add_action('plugins_loaded', 'woocommerce_gateway_mintpay_init', 0);
define('mintpay_IMG', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets/img/');

function woocommerce_gateway_mintpay_init()
{
	if (!class_exists('WC_Payment_Gateway')) return;

	if (!session_id()) {
		session_start([
			'read_and_close' => true,
		]);
	}

	/**
	 * Gateway class
	 */
	class WC_Gateway_mintpay extends WC_Payment_Gateway
	{
		/**
		 * Make __construct()
		 **/
		public function __construct()
		{
			$this->id 					= 'mintpay'; // ID for WC to associate the gateway values
			$this->icon                 = "https://static.mintpay.lk/static/base/logo/mintpay_logo.png";
			$this->method_title 		= "Mintpay"; // Gateway Title as seen in Admin Dashboad
			$this->method_description	= "Sri Lanka's first buy now pay later platform, that allows consumers to split their payment into 3 interst-free installments.";
			$this->has_fields 			= false; // Inform WC if any fileds have to be displayed to the visitor in Frontend 

			$this->init_form_fields();	// defines your settings to WC
			$this->init_settings();		// loads the Gateway settings into variables for WC

			$this->title 		= 'Mintpay';
			$this->description 	=  "Pay in 3 interest-free instalments using your <b>" . esc_html__('Debit / Credit', 'mintpay') . "</b> card.";
			$this->merchant_id 		= $this->get_option('merchant_id');
			$this->merchant_secret 	= $this->get_option('merchant_secret');
			$this->test_mode        = 'yes' === $this->get_option('test_mode');

			$this->msg['message']	= '';
			$this->msg['class'] 	= '';

			add_action('init', array(&$this, 'check_mintpay_response'));
			add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'check_mintpay_response')); //update for woocommerce >2.0
			add_action('woocommerce_gateway_icon', array($this, 'modify_gateway_icon_css'), 10, 2);

			if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options')); //update for woocommerce >2.0
			} else {
				add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options')); // WC-1.6.6
			}
			add_action('woocommerce_receipt_mintpay', array(&$this, 'receipt_page'));
		} //END-__construct

		function modify_gateway_icon_css($icon_html, $payment_gateway_id)
		{
			if ($payment_gateway_id != $this->id) {
				return $icon_html;
			}

			$new_css = 'class="ph-logo-style" src';
			$icon_html = preg_replace('/(src){1}/', $new_css, $icon_html);

			return $icon_html;
		}

		/**
		 * Initiate Form Fields in the Admin Backend
		 **/
		function init_form_fields()
		{
			$this->form_fields = array(
				// Activate the Gateway
				'enabled' => array(
					'title' 		=> __('Enable', 'mintpay'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Enable mintpay gateway', 'mintpay'),
					'default' 		=> 'yes',
					'description' 	=> __('Show mintpay as a payment option at checkout', 'mintpay'),
					'desc_tip' 		=> true
				),

				// Activate Test mode
				'test_mode' => array(
					'title' 		=> __('Test Mode', 'mintpay'),
					'type' 			=> 'checkbox',
					'label' 		=> __('Enable test mode', 'mintpay'),
					'default' 		=> 'yes',
					'description' 	=> __('Test mintpay payment gateway in sandbox environment', 'mintpay'),
					'desc_tip' 		=> true
				),

				// LIVE Key-ID
				'merchant_id' => array(
					'title' 		=> __('Merchant ID', 'mintpay'),
					'type' 			=> 'text',
					'description' 	=> __('Your mintpay Merchant ID'),
					'desc_tip' 		=> true
				),
				// LIVE Key-Secret
				'merchant_secret' => array(
					'title' 			=> __('Merchant Secret', 'mintpay'),
					'type' 			=> 'text',
					'description' 	=> __('Your mintpay Merchant Secret'),
					'desc_tip' 		=> true
				),
			);
		} //END-init_form_fields

		/**
		 * Admin Panel Options
		 * - Show info on Admin Backend
		 **/
		public function admin_options()
		{
			_e('<h3> Mintpay </h3>', 'mintpay');
			_e('<p> WooCommerce payment plugin of Mintpay payment gateway. Sri Lanka\'s first buy now pay later platform, that allows consumers to split their payment into 3 interst-free installments </p>', 'mintpay');
			_e('<table class="form-table">', 'mintpay');
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			_e('</table>', 'mintpay');
		} //END-admin_options

		/**
		 *  There are no payment fields, but we want to show the description if set.
		 **/
		function payment_fields()
		{
			if ($this->description) {
				_e($this->description);
			}
		} //END-payment_fields

		/**
		 * Receipt Page
		 **/
		function receipt_page($order)
		{
			_e('<p><strong>' . esc_html__('Thank you for your order') . '.<br/>' . esc_html__('The payment page will open soon.') . '</strong></p>', 'mintpay');
			_e($this->generate_mintpay_form($order));
		} //END-receipt_page

		/**
		 * Generate button link
		 **/
		function generate_mintpay_form($order_id)
		{
			global $woocommerce;

			$order = wc_get_order($order_id);

			$merchant_id = $this->merchant_id;
			$merchant_secret = $this->merchant_secret;
			$amount = $order->get_total();

			$success_hash = hash_hmac('sha256', $merchant_id . sprintf("%.02f", round($amount, 2)) . $order_id, $merchant_secret);
			$fail_hash = hash_hmac('sha256', $order_id, $merchant_secret);


			$_SESSION['order_id'] = $order_id;

			$redirect_url = $order->get_checkout_order_received_url();

			$notify_url = "";
			// Redirect URL : For WooCoomerce 2.0
			if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
				$notify_url = add_query_arg('wc-api', get_class($this), $redirect_url);
			}

			$success_url = $notify_url . '&orderId=' . $order_id . '&hash=' .  base64_encode($success_hash);
			$fail_url = $notify_url . '&orderId=' . $order_id . '&hash=' .  base64_encode($fail_hash);


			if ($this->test_mode) {
				$api_url = "https://dev.mintpay.lk/user-order/api/";
				$form_url = "https://dev.mintpay.lk/user-order/login/";
			} else {
				$api_url = "https://app.mintpay.lk/user-order/api/";
				$form_url = "https://app.mintpay.lk/user-order/login/";
			}

			foreach ($order->get_items() as $item_id => $item) {

				$order_items[] = array(
					'name'         => $item->get_name(),
					'product_id'   => $item->get_product_id(),
					'sku'          => $item->get_type(),
					'quantity'     => $item->get_quantity(),
					'unit_price'   => $item->get_total(),
					'created_date' => "2001-10-01 01:10:01",
					'updated_date' => "2001-10-01 01:10:01",
					'discount'     => "0.00"
				);
			}

			// request body sent to mintpay api
			$postData = [
				'merchant_id'           => $merchant_id,
				'order_id'              => $order_id,
				'total_price'           => $amount,
				'discount'              => $order->get_total_discount(),
				'customer_id'           => $order->get_customer_id(),
				'customer_email'        => $order->get_billing_email(),
				'customer_telephone'    => $order->get_billing_phone(),
				'ip'                    => $order->get_customer_ip_address(),
				'x_forwarded_for'       => $order->get_customer_ip_address(),
				'delivery_street'       => $order->get_billing_address_1() . $order->get_billing_address_2(),
				'delivery_region'       => $order->get_billing_city(),
				'delivery_postcode'     => $order->get_billing_postcode(),
				'cart_created_date'     => date_format($order->get_date_created(), "Y-m-d H:i:s"),
				'cart_updated_date'     => date_format($order->get_date_modified(), "Y-m-d H:i:s"),
				'success_url'           => $success_url,
				'fail_url'              => $fail_url,
				'products'              => $order_items,
				'currency_code'			=> $order->get_currency(),
				'currency_symbol'		=> get_woocommerce_currency_symbol($order->get_currency())
			];

			// headers sent with the request
			$headers = [
				'Authorization' => 'Token ' . $merchant_secret,
				'Content-Type' => 'application/json',
			];

			// create arguments for the post request
			$args = array(
				'body'        => json_encode($postData),
				'headers'     => $headers,
				'timeout'     => '15',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
			);

			// receive the response and retreive the body and decode json
			$response = wp_remote_post($api_url, $args);
			$response_body = wp_remote_retrieve_body($response);
			$mintpayRequestData = json_decode($response_body, true);

			/**
			 * when debugging API issues use logs (i.e: file_put_contents)
			 */

			if (isset($mintpayRequestData['message']) && $mintpayRequestData['message'] == 'Success') {

				return '<form action="' . $form_url . '"method="post" id="mintpay_payment_form">
				<input type="hidden" name="purchase_id" value="' . $mintpayRequestData['data'] . '" >
				<input type="submit" class="button-alt" id="submit_mintpay_payment_form" value="' . __('Pay via Mintpay', 'mintpay') . '" /> <a class="button cancel" href="' . $order->get_cancel_order_url() . '">' . __('Cancel order &amp; restore cart', 'mintpay') . '</a>
					<script type="text/javascript">
					jQuery(function(){
					jQuery("body").block({
						message: "' . __('Thanks for your order! We are now redirecting you to Mintpay payment gateway to make the payment.', 'mintpay') . '",
						overlayCSS: {
							background		: "#fff",
							opacity			: 0.8
						},
						css: {
							padding			: 20,
							textAlign		: "center",
							color			: "#333",
							border			: "1px solid #eee",
							backgroundColor	: "#fff",
							cursor			: "wait",
							lineHeight		: "32px"
						}
					});
					jQuery("#submit_mintpay_payment_form").click();});
					</script>
				</form>';
			} else {
				return wp_redirect(wc_get_cart_url());
			}
		} //END-generate_mintpay_form


		function check_mintpay_response()
		{
			global $woocommerce;

			if (isset($_GET['key']) && isset($_GET['hash'])) {

				if (isset($_GET['orderId'])) {
					$order = wc_get_order($_GET['orderId']);

					$post_success_hash = hash_hmac('sha256', $this->merchant_id . sprintf("%.02f", round($order->get_total(), 2)) . $_GET['orderId'],  $this->merchant_secret);

					$post_failed_hash = hash_hmac('sha256', $_GET['orderId'],  $this->merchant_secret);

					if (base64_decode($_GET['hash']) == $post_success_hash) {
						$order->payment_complete();
						$order->add_order_note(__('Mintpay payment completed', 'mintpay'));
						$woocommerce->cart->empty_cart();
						wp_redirect($order->get_checkout_order_received_url());
					} else if (base64_decode($_GET['hash']) == $post_failed_hash) {
						$cancelled_text = "Payment failed.";
						wc_add_notice($cancelled_text, 'error');
						$order->update_status('failed', $cancelled_text);
						wp_redirect($order->get_checkout_payment_url());
					} else {
						$cancelled_text = "Suspicious response.";
						$order->update_status('cancelled', $cancelled_text);
						wc_add_notice(__('Payment error:', 'woothemes') . $cancelled_text, 'error');
						wp_redirect($order->get_checkout_payment_url());
					}
				} else {
					wp_redirect(wc_get_checkout_url() . "A");
				}
			} else {
				wp_redirect(wc_get_checkout_url() . "B");
			}
		}

		/**
		 * Process the payment and return the result
		 **/
		function process_payment($order_id)
		{
			global $woocommerce;
			$order = new WC_Order($order_id);

			if (version_compare(WOOCOMMERCE_VERSION, '2.1.0', '>=')) { // For WC 2.1.0
				$checkout_payment_url = $order->get_checkout_payment_url(true);
			} else {
				$checkout_payment_url = get_permalink(get_option('woocommerce_pay_page_id'));
			}

			return array(
				'result' => 'success',
				'redirect' => add_query_arg(
					'order',
					$order->get_id(),
					add_query_arg(
						'key',
						$order->get_order_key(),
						$checkout_payment_url
					)
				)
			);
		} //END-process_payment


		/**
		 * Get Page list from WordPress
		 **/
		function mintpay_get_pages($title = false, $indent = true)
		{
			$wp_pages = get_pages('sort_column=menu_order');
			$page_list = array();
			if ($title) $page_list[] = $title;
			foreach ($wp_pages as $page) {
				$prefix = '';
				// show indented child pages?
				if ($indent) {
					$has_parent = $page->post_parent;
					while ($has_parent) {
						$prefix .=  ' - ';
						$next_page = get_post($has_parent);
						$has_parent = $next_page->post_parent;
					}
				}
				// add to page list array array
				$page_list[$page->ID] = $prefix . $page->post_title;
			}
			return $page_list;
		} //END-mintpay_get_pages

	} //END-class

	/**
	 * Add the Gateway to WooCommerce
	 **/
	function woocommerce_add_gateway_mintpay_gateway($methods)
	{
		$methods[] = 'WC_Gateway_mintpay';
		return $methods;
	} //END-wc_add_gateway

	add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_mintpay_gateway');
} //END-init

/**
 * 'Settings' link on plugin page
 **/
add_filter('plugin_action_links', 'mintpay_add_action_plugin', 10, 5);
function mintpay_add_action_plugin($actions, $plugin_file)
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {

		$settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=checkout&section=wc_gateway_mintpay">' . __('Settings') . '</a>');

		$actions = array_merge($settings, $actions);
	}

	return $actions;
} //END-settings_add_action_link

// include price-breakdow only if Price Breakdown plugin is unavailable
if ( !function_exists('price_below_text_func') ){
	include_once("price_break_down.php");
}