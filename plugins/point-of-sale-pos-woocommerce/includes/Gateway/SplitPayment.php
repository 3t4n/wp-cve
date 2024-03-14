<?php

namespace ZPOS\Gateway;

use WC_Order;
use ZPOS\API;
use ZPOS\Model\Gateway;
use const ZPOS\TEXTDOMAIN;

class SplitPayment extends Base
{
	public $id = 'pos_split_payment';
	public $method_title = 'Split Payment';
	public $has_fields = true;
	public $supports = ['products', 'pos'];

	public static function getID(): string
	{
		return 'pos_split_payment';
	}

	public function __construct()
	{
		parent::__construct();

		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');

		add_action('woocommerce_pos_update_options_payment_gateways_' . $this->id, [
			$this,
			'process_admin_options',
		]);
	}

	public static function is_split_payment(/* mixed */ $raw_post_data = []): bool
	{
		if (empty($raw_post_data)) {
			$raw_post_data = API::get_raw_data();
		}

		return isset($raw_post_data['payment_details']['id']) &&
			self::getID() === $raw_post_data['payment_details']['id'];
	}

	public static function is_pending_split_payment(\WC_Order $order): bool
	{
		return count($order->get_meta('_pos_successful_split_payments') ?: []) !==
			count($order->get_meta('_pos_split_payments') ?: []);
	}

	public function process_payment(/* int|\WC_Order */ $order_id): array
	{
		if ($order_id instanceof WC_Order) {
			$order = $order_id;
		} else {
			$order = new WC_Order($order_id);
		}

		$split_payments = $order->get_meta('_pos_split_payments');
		$response_data = ['result' => 'success'];

		if (!is_array($split_payments) || empty($split_payments)) {
			$response_data = $this->get_base_failed_response();

			$this->save_response_data($order, $response_data);
			return $response_data;
		}

		$gateways = WC()
			->payment_gateways()
			->get_available_payment_gateways();
		$tendered = $this->get_split_payment_tendered($split_payments, $order, $gateways);
		$order_total = $order->get_total();
		$is_amount_match = in_array(Cash::getID(), $split_payments, true)
			? $tendered >= $order_total
			: $tendered === $order_total;

		if (!$is_amount_match) {
			$response_data = [
				'result' => 'failed',
				'message' => __('Oops! The amount does not match.', TEXTDOMAIN),
			];

			$this->save_response_data($order, $response_data);
			return $response_data;
		}

		$successful_payments = $order->get_meta('_pos_successful_split_payments') ?: [];
		$failed_payments = [];

		if ($successful_payments) {
			$split_payments = array_filter($split_payments, function (string $split_payment) use (
				$successful_payments
			): bool {
				return !in_array($split_payment, $successful_payments, true);
			});
		}

		foreach ($split_payments as $split_payment) {
			if (empty($gateways[$split_payment])) {
				$response_data = $this->get_base_failed_response();

				$this->save_response_data($order, $response_data, $successful_payments);
				return $response_data;
			}

			$payment_result = $gateways[$split_payment]->process_payment($order);

			if (isset($payment_result['result']) && 'success' === $payment_result['result']) {
				$successful_payments[] = $split_payment;
			} else {
				$response_data['result'] = 'failed';
				$failed_payments[] = $split_payment;
			}
		}

		if ('success' === $response_data['result']) {
			$order->payment_complete();
			$order->update_status(Gateway::getGatewayOrderStatus($this->id));
		} else {
			$order->update_status('pending');

			$response_data['message'] = $this->get_massage_failed($failed_payments, $gateways);
		}

		$this->save_response_data($order, $response_data, $successful_payments);
		return $response_data;
	}

	public function get_split_payment_tendered(
		array $split_payments,
		\WC_Order $order,
		array $gateways
	): float {
		return array_reduce(
			$split_payments,
			function ($result, $split_payment) use ($order, $gateways) {
				$key = str_replace('_', '-', $split_payment);
				$amount_tendered = (float) $order->get_meta($key . '-tendered');

				if (empty($gateways[$split_payment]) || !is_numeric($amount_tendered)) {
					return $result;
				}

				return $result + $amount_tendered;
			},
			0.0
		);
	}

	private function save_response_data(
		\WC_Order $order,
		array $response_data,
		array $successful_payments = []
	): void {
		$order->update_meta_data('_pos_payment_status', $response_data);

		if ($successful_payments) {
			$order->update_meta_data('_pos_successful_split_payments', $successful_payments);
		}

		$order->save();
	}

	private function get_massage_failed(array $failed_payments, array $gateways): string
	{
		$failed_payments_count = count($failed_payments);

		if (!$failed_payments_count) {
			return $this->get_base_failed_response()['message'];
		}

		$message =
			1 === $failed_payments_count
				? __('Oops! Payment method %s has failed processing.', TEXTDOMAIN)
				: __('Oops! Following payment methods have failed processing: %s.', TEXTDOMAIN);
		$failed_payments_str = array_reduce(
			$failed_payments,
			function (string $result, string $failed_payment) use ($gateways): string {
				$method_title = $gateways[$failed_payment]->method_title;

				return $result . ($result ? ", $method_title" : $method_title);
			},
			''
		);

		return sprintf($message, $failed_payments_str);
	}

	private function get_base_failed_response(): array
	{
		return [
			'result' => 'failed',
			'message' => __('Oops! Something went wrong.', TEXTDOMAIN),
		];
	}
}
