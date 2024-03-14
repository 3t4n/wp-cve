<?php

namespace ZPOS\Gateway;

use WC_Order;
use ZPOS\API;

class Stripe extends Base
{
	public $id = 'pos_stripe';
	public $method_title = 'POS Stripe Card';
	public $method_description = 'Stripe card payment method';
	public $has_fields = true;
	public $supports = ['products', 'pos', 'kiosk', 'refunds'];
	protected $stripe;
	protected $stripe_import_fields = [
		'publishable_key',
		'testmode',
		'capture',
		'three_d_secure',
		'secret_key',
	];
	protected $public_stripe_import_fields = [
		'publishable_key',
		'testmode',
		'capture',
		'three_d_secure',
	];

	public static function getID(): string
	{
		return 'pos_stripe';
	}

	public function __construct()
	{
		parent::__construct();

		$this->init_stripe();
		$this->init_settings();

		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');

		add_action('woocommerce_pos_update_options_payment_gateways_' . $this->id, [
			$this,
			'process_admin_options',
		]);
	}

	public function init_stripe(): void
	{
		$this->stripe = new \WC_Gateway_Stripe();

		// remove default stripe actions
		remove_action('wp_enqueue_scripts', [$this->stripe, 'payment_scripts']);
		remove_action('admin_enqueue_scripts', [$this->stripe, 'admin_scripts']);
		remove_action('woocommerce_update_options_payment_gateways_' . $this->stripe->id, [
			$this->stripe,
			'process_admin_options',
		]);
		remove_action('woocommerce_admin_order_totals_after_total', [
			$this->stripe,
			'display_order_fee',
		]);
		remove_action(
			'woocommerce_admin_order_totals_after_total',
			[$this->stripe, 'display_order_payout'],
			20
		);
		remove_action(
			'woocommerce_customer_save_address',
			[$this->stripe, 'show_update_card_notice'],
			10
		);
		remove_filter('woocommerce_available_payment_gateways', [
			$this->stripe,
			'prepare_order_pay_page',
		]);
		remove_action(
			'woocommerce_account_view-order_endpoint',
			[$this->stripe, 'check_intent_status_on_order_page'],
			1
		);
		remove_filter('woocommerce_payment_successful_result', [
			$this->stripe,
			'modify_successful_payment_result',
		]);
		remove_action('set_logged_in_cookie', [$this->stripe, 'set_cookie_on_current_request'], 99999);
	}

	public function init_settings(): void
	{
		parent::init_settings();

		$settings = $this->stripe->settings;

		if ($settings['testmode'] === 'yes') {
			$settings['publishable_key'] = $settings['test_publishable_key'];
			$settings['secret_key'] = $settings['test_secret_key'];
		}
		$this->settings = array_merge(
			$this->settings,
			array_filter(
				$settings,
				function ($key) {
					return in_array($key, $this->stripe_import_fields);
				},
				ARRAY_FILTER_USE_KEY
			)
		);
	}

	public function init_form_fields(): void
	{
		parent::init_form_fields();
		$this->stripe->init_form_fields();
		$this->form_fields = array_filter(
			$this->stripe->form_fields,
			function ($key) {
				return in_array($key, $this->stripe_import_fields);
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	public function rest_filter_settings(array $settings): array
	{
		return array_filter(
			$settings,
			function ($key) {
				return in_array($key, $this->public_stripe_import_fields);
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	public function process_payment(/* int|\WC_Order */ $order_id): array
	{
		if ($order_id instanceof WC_Order) {
			$order = $order_id;
		} else {
			$order = new WC_Order($order_id);
		}

		$data = API::get_raw_data();
		$is_split_payment = SplitPayment::is_split_payment($data);
		$payment_id = $is_split_payment
			? $data['payment_details']['splitPayments'][$this->id]['stripe_source']['id'] ?? false
			: $data['payment_details']['stripe_source']['id'] ?? false;

		try {
			if ($payment_id) {
				$stripe = Stripe\API::instance();
				$tendered = $order->get_meta('pos-stripe-tendered');
				$stripe_data = $stripe->createCharge(
					$payment_id,
					$tendered,
					strtolower($order->get_currency()),
					$order->get_id()
				);

				$order->set_transaction_id($stripe_data->transaction_id);
				$order->add_order_note(
					sprintf(
						__('POS Stripe charge complete (Charge ID: %s)', 'zpos-wp-api'),
						$stripe_data->transaction_id
					)
				);
				$order->add_meta_data('_stripe_net', $stripe_data->net);
				$order->add_meta_data('_stripe_fee', $stripe_data->fee);
				$order->add_meta_data('_stripe_currency', $stripe_data->currency);
				$order->add_meta_data('_stripe_source_id', $stripe_data->source);
				$order->add_meta_data('_stripe_charge_captured', $stripe_data->captured);

				if (!$is_split_payment) {
					$order->payment_complete();
					$order->set_status('completed');
				}

				$order->save();

				return [
					'result' => 'success',
				];
			} else {
				return [
					'result' => 'failed',
				];
			}
		} catch (\Exception $e) {
			if (!$is_split_payment) {
				$order->set_status('failed');
			}

			$order->add_meta_data('_pos_stripe_gateway_error', $e->getMessage());
			$order->save();

			return [
				'result' => 'failed',
			];
		}
	}

	public function process_refund($order_id, $amount = null, $reason = '')
	{
		// get order object
		if ($order_id instanceof WC_Order) {
			$order = $order_id;
		} else {
			$order = new WC_Order($order_id);
		}

		$currency = strtolower($order->get_currency());
		$charge = $order->get_transaction_id();

		$stripe = Stripe\API::instance();
		$response = $stripe->refund($charge, $currency, $amount, $reason);

		if ($response->status === 'failed') {
			return false;
		}

		$refund_message = sprintf(
			__('Refunded %1$s - Refund ID: %2$s - Reason: %3$s', 'woocommerce-gateway-stripe'),
			$response->amount,
			$response->id,
			$reason
		);

		$order->update_meta_data('_stripe_refund_id', $response->id);
		$order->add_order_note($refund_message);
		return true;
	}
}
