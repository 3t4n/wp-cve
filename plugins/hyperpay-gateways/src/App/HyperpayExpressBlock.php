<?php

namespace Hyperpay\Gateways\App;

use WC_Validation;
use Exception;

/**
 * Express Payments Blocks integration
 *
 * @since 1.0.3
 */
trait HyperpayExpressBlock
{

	public function initialize()
	{
		add_action('wc_ajax_hyperpay_prepare_checkout', [$this, "initCheckout"]);
		add_action('wc_ajax_hyperpay_update_method', [$this, 'ajax_update_shipping_method']);
		add_action('wc_ajax_hyperpay_update_checkout', [$this, "init_shipping_options"]);
		add_action('wc_ajax_hyperpay_process_checkout', [$this, 'process_checkout']);
	}


	public  function initCheckout()
	{
		$data = [
			'headers' => [
				"Authorization" => "Bearer {$this->accessToken}"
			],
			'body' => [
				"entityId" => $this->entityId,
				"paymentType" => $this->trans_type,
			]
		];


		if ($this->testMode) {
			$data['body']["testMode"] = $this->trans_mode;
		}

		if ($this->server_to_server) {
			$data['body']['paymentBrand'] = $this->brands[0];
		}


		$response = wp_remote_post($this->token_url, $data);
		$result =  json_decode(wp_remote_retrieve_body($response), true);


		if (is_wp_error($response)) {
			$error = $response->get_error_message();
		} elseif (wp_remote_retrieve_response_code($response) != 200) {
			$error =  $result['result']['description'] ?? "something wrong";
		}

		if (isset($error) || !\preg_match("/^(000\.200)/", $result['result']['code'] ?? '')) {
			return wp_send_json_error($result['result']['description'] ?? $error);
		}


		return wp_send_json($result['id']);
	}


	public function init_shipping_options()
	{

		$shipping_inputs          = filter_input_array(
			INPUT_POST,
			[
				'countryCode'               => FILTER_SANITIZE_STRING,
				'administrativeArea'        => FILTER_SANITIZE_STRING,
				'postalCode'                => FILTER_SANITIZE_STRING,
				'locality'                  => FILTER_SANITIZE_STRING,
				'address'                   => FILTER_SANITIZE_STRING,
				'address_2'                 => FILTER_SANITIZE_STRING,
			]
		);

		$shipping_address = [
			'country'   => $shipping_inputs['countryCode'],
			'state'     => $shipping_inputs['administrativeArea'],
			'postcode'  => $shipping_inputs['postalCode'],
			'city'      => $shipping_inputs['locality'],
			'address'   => $shipping_inputs['address'],
			'address_2' => $shipping_inputs['address_2'],
		];



		$product_view_options      = filter_input_array(INPUT_POST, ['is_product_page' => FILTER_SANITIZE_STRING]);
		$should_show_itemized_view = !isset($product_view_options['is_product_page']) ? true : filter_var($product_view_options['is_product_page'], FILTER_VALIDATE_BOOLEAN);

		$shipping_options =  $this->get_shipping_options($shipping_address, $should_show_itemized_view);
		return wp_send_json($shipping_options);
	}

	/**
	 * Update shipping method.
	 */
	public function ajax_update_shipping_method()
	{

		if (!defined('WOOCOMMERCE_CART')) {
			define('WOOCOMMERCE_CART', true);
		}

		$shipping_methods = filter_input(INPUT_POST, 'shipping_method', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$this->update_shipping_method($shipping_methods);

		WC()->cart->calculate_totals();

		$product_view_options      = filter_input_array(INPUT_POST, ['is_product_page' => FILTER_SANITIZE_STRING]);
		$should_show_itemized_view = !isset($product_view_options['is_product_page']) ? true : filter_var($product_view_options['is_product_page'], FILTER_VALIDATE_BOOLEAN);

		$data           = [];
		$data          += $this->build_display_items($should_show_itemized_view);
		$data['result'] = 'success';

		wp_send_json($data);
	}

	/**
	 * Gets shipping options available for specified shipping address
	 *
	 * @param array   $shipping_address       Shipping address.
	 * @param boolean $itemized_display_items Indicates whether to show subtotals or itemized views.
	 *
	 * @return array Shipping options data.
	 * phpcs:ignore Squiz.Commenting.FunctionCommentThrowTag
	 */
	public function get_shipping_options($shipping_address, $itemized_display_items = false)
	{
		try {
			// Set the shipping options.
			$data = [];

			// Remember current shipping method before resetting.
			$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
			$this->calculate_shipping($shipping_address);

			$packages          = WC()->shipping->get_packages();
			$shipping_rate_ids = [];

			if (!empty($packages) && WC()->customer->has_calculated_shipping()) {
				foreach ($packages as  $package) {
					if (empty($package['rates'])) {
						throw new Exception(__('Unable to find shipping method for address.', 'hyperpay-payments'));
					}

					foreach ($package['rates'] as  $rate) {
						if (in_array($rate->id, $shipping_rate_ids, true)) {
							// The Payment Requests will try to load indefinitely if there are duplicate shipping
							// option IDs.
							throw new Exception(__('Unable to provide shipping options for Payment Requests.', 'hyperpay-payments'));
						}
						$shipping_rate_ids[]        = $rate->id;
						$data['shipping_options'][] = [
							'identifier'     => $rate->id,
							'label'  => $rate->label,
							'detail' => '',
							'amount' => (float)($rate->cost),
						];
					}
				}
			} else {
				throw new Exception(__('Unable to find shipping method for address.', 'hyperpay-payments'));
			}

			// The first shipping option is automatically applied on the client.
			// Keep chosen shipping method by sorting shipping options if the method still available for new address.
			// Fallback to the first available shipping method.
			if (isset($data['shipping_options'][0])) {
				if (isset($chosen_shipping_methods[0])) {
					$chosen_method_id         = $chosen_shipping_methods[0];
					$compare_shipping_options = function ($a, $b) use ($chosen_method_id) {
						if ($a['id'] === $chosen_method_id) {
							return -1;
						}

						if ($b['id'] === $chosen_method_id) {
							return 1;
						}

						return 0;
					};
					usort($data['shipping_options'], $compare_shipping_options);
				}

				$first_shipping_method_id = $data['shipping_options'][0]['id'];
				$this->update_shipping_method([$first_shipping_method_id]);
			}

			WC()->cart->calculate_totals();

			$data          += $this->build_display_items($itemized_display_items);
			$data['result'] = 'success';
		} catch (Exception $e) {
			$data          += $this->build_display_items($itemized_display_items);
			$data['result'] = $e->getMessage();
		}

		return $data;
	}


	/**
	 * Updates shipping method in WC session
	 *
	 * @param array $shipping_methods Array of selected shipping methods ids.
	 */
	public function update_shipping_method($shipping_methods)
	{
		$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

		if (is_array($shipping_methods)) {
			foreach ($shipping_methods as $i => $value) {
				$chosen_shipping_methods[$i] = wc_clean($value);
			}
		}

		WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);
	}

	/**
	 * Builds the line items to pass to Payment Request
	 *
	 * @since   3.1.0
	 * @version 4.0.0
	 */
	protected function build_display_items($itemized_display_items = false)
	{
		if (!defined('WOOCOMMERCE_CART')) {
			define('WOOCOMMERCE_CART', true);
		}

		$items         = [];
		$lines         = [];
		$subtotal      = 0;
		$discounts     = 0;
		$display_items = !apply_filters('wc_stripe_payment_request_hide_itemization', true) || $itemized_display_items;

		foreach (WC()->cart->get_cart() as  $cart_item) {
			$subtotal      += $cart_item['line_subtotal'];
			$amount         = $cart_item['line_subtotal'];
			$quantity_label = 1 < $cart_item['quantity'] ? ' (x' . $cart_item['quantity'] . ')' : '';
			$product_name   = $cart_item['data']->get_name();

			$lines[] = [
				'label'  => $product_name . $quantity_label,
				'amount' => (float)($amount),
			];
		}

		if ($display_items) {
			$items = array_merge($items, $lines);
		} else {
			// Default show only subtotal instead of itemization.

			$items[] = [
				'label'  => 'Subtotal',
				'amount' => (float)($subtotal),
			];
		}

		if (version_compare(WC_VERSION, '3.2', '<')) {
			$discounts = wc_format_decimal(WC()->cart->get_cart_discount_total(), WC()->cart->dp);
		} else {
			$applied_coupons = array_values(WC()->cart->get_coupon_discount_totals());

			foreach ($applied_coupons as $amount) {
				$discounts += (float) $amount;
			}
		}

		$discounts   = wc_format_decimal($discounts, WC()->cart->dp);
		$tax         = wc_format_decimal(WC()->cart->tax_total + WC()->cart->shipping_tax_total, WC()->cart->dp);
		$shipping    = wc_format_decimal(WC()->cart->shipping_total, WC()->cart->dp);
		$items_total = wc_format_decimal(WC()->cart->cart_contents_total, WC()->cart->dp) + $discounts;
		$order_total = version_compare(WC_VERSION, '3.2', '<') ? wc_format_decimal($items_total + $tax + $shipping - $discounts, WC()->cart->dp) : WC()->cart->get_total(false);

		if (wc_tax_enabled()) {
			$items[] = [
				'label'  => esc_html(__('Tax', 'hyperpay-payments')),
				'amount' => (float)($tax),
			];
		}

		if (WC()->cart->needs_shipping()) {
			$items[] = [
				'label'  => esc_html(__('Shipping', 'hyperpay-payments')),
				'amount' => (float)($shipping),
			];
		}

		if (WC()->cart->has_discount()) {
			$items[] = [
				'label'  => esc_html(__('Discount', 'hyperpay-payments')),
				'amount' => (float)($discounts),
			];
		}

		if (version_compare(WC_VERSION, '3.2', '<')) {
			$cart_fees = WC()->cart->fees;
		} else {
			$cart_fees = WC()->cart->get_fees();
		}

		// Include fees and taxes as display items.
		foreach ($cart_fees as $key => $fee) {
			$items[] = [
				'label'  => $fee->name,
				'amount' => (float)($fee->amount),
			];
		}

		return [
			'displayItems' => $items,
			'total'        => [
				'label'   => "By HyperPay",
				'amount'  => max(0, apply_filters('woocommerce_stripe_calculated_total', (float)($order_total), $order_total, WC()->cart)),
				'pending' => false,
			],
		];
	}



	/**
	 * Calculate and set shipping method.
	 *
	 * @param array $address Shipping address.
	 *
	 * @since   3.1.0
	 * @version 5.0.0
	 */
	protected function calculate_shipping($address = [])
	{
		$country   = $address['country'];
		$state     = $address['state'];
		$postcode  = $address['postcode'];
		$city      = $address['city'];
		$address_1 = $address['address'];
		$address_2 = $address['address_2'];

		// Normalizes state to calculate shipping zones.

		// Normalizes postal code in case of redacted data from Apple Pay.

		WC()->shipping->reset_shipping();

		if ($postcode && WC_Validation::is_postcode($postcode, $country)) {
			$postcode = wc_format_postcode($postcode, $country);
		}

		if ($country) {
			WC()->customer->set_location($country, $state, $postcode, $city);
			WC()->customer->set_shipping_location($country, $state, $postcode, $city);
		} else {
			WC()->customer->set_billing_address_to_base();
			WC()->customer->set_shipping_address_to_base();
		}

		WC()->customer->set_calculated_shipping(true);
		WC()->customer->save();

		$packages = [];

		$packages[0]['contents']                 = WC()->cart->get_cart();
		$packages[0]['contents_cost']            = 0;
		$packages[0]['applied_coupons']          = WC()->cart->applied_coupons;
		$packages[0]['user']['ID']               = get_current_user_id();
		$packages[0]['destination']['country']   = $country;
		$packages[0]['destination']['state']     = $state;
		$packages[0]['destination']['postcode']  = $postcode;
		$packages[0]['destination']['city']      = $city;
		$packages[0]['destination']['address']   = $address_1;
		$packages[0]['destination']['address_2'] = $address_2;

		foreach (WC()->cart->get_cart() as $item) {
			if ($item['data']->needs_shipping()) {
				if (isset($item['line_total'])) {
					$packages[0]['contents_cost'] += $item['line_total'];
				}
			}
		}

		$packages = apply_filters('woocommerce_cart_shipping_packages', $packages);

		WC()->shipping->calculate_shipping($packages);
	}

	/**
	 * Create order. Security is handled by WC.
	 *
	 * @since   3.1.0
	 * @version 5.1.0
	 */
	public function process_checkout()
	{
		if (WC()->cart->is_empty()) {
			wp_send_json_error(__('Empty cart', 'hyperpay-payments'));
		}

		if (!isset($_POST['checkoutId'])) {
			wp_send_json_error(__('invalid checkoutId', 'hyperpay-payments'));
		}

		if (!defined('WOOCOMMERCE_CHECKOUT')) {
			define('WOOCOMMERCE_CHECKOUT', true);
		}


		$this->legacy = false;

		WC()->checkout()->process_checkout();

		die(0);
	}


	public function process_payment($order_id)
	{
		// that mean the request comes from checkout blocks
		if (isset($_POST['checkoutId'])) {

			$checkoutId = sanitize_text_field($_POST['checkoutId']);
			$url = $this->token_url . "/$checkoutId";


			$checkout = $this->getCheckoutData($order_id);
			$checkout['data']['body']['shopperResultUrl'] = $checkout['postBackURL'];

			// HTTP Request to oppwa to get checkout
			$response = Http::post($url, $checkout['data']);

			if (!\preg_match("/^(000\.200)/", $response['result']['code'] ?? '')) {
				return wp_send_json_error($response['result']['description'] ?? 'unable to update checkout');
			}

			return wp_send_json(["result" => "SUCCESS"]);
		}

		// legacy checkout
		return parent::process_payment($order_id);
	}
}
