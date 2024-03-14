<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Payment_Gateways_Controller, ZPOS\Model\Gateway;
use ZPOS\API;
use const ZPOS\REST_NAMESPACE;

class Gateways extends WC_REST_Payment_Gateways_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		register_rest_route($this->namespace, '/' . $this->rest_base, [
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [$this, 'get_items'],
				'permission_callback' => [$this, 'get_items_permissions_check'],
			],
		]);
	}

	public function get_items($request)
	{
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		$response = [];
		foreach ($payment_gateways as $payment_gateway_id => $payment_gateway) {
			$payment_gateway->id = $payment_gateway_id;
			$gateway = $this->prepare_item_for_response($payment_gateway, $request);
			$gateway = $this->prepare_response_for_collection($gateway);
			unset($gateway['_links']);
			$response[] = $gateway;
		}

		return rest_ensure_response($response);
	}

	public function prepare_item_for_response($payment_gateway, $request)
	{
		$gateway = parent::prepare_item_for_response($payment_gateway, $request);
		$data = $gateway->data;
		$gateway->data['order'] = $this->get_order($data);
		$gateway->data['pos'] = Gateway::isGatewayEnabled($data['id']);
		$gateway->data['order_status'] = Gateway::getGatewayOrderStatus($data['id']);
		$gateway->data['kiosk'] = $payment_gateway->supports('kiosk');
		if (method_exists($payment_gateway, 'rest_filter_settings')) {
			$gateway->data['settings'] = $payment_gateway->rest_filter_settings(
				$gateway->data['settings']
			);
		} else {
			unset($gateway->data['settings']);
		}
		$gateway->data['default'] = get_option('pos_gateways_default') === $data['id'];

		return $gateway;
	}

	private function get_order($gateway)
	{
		$gateways_data = get_option('pos_gateways');
		$order = isset($gateways_data[$gateway['id']])
			? (int) $gateways_data[$gateway['id']]['order']
			: 9999;
		return $order;
	}

	public function get_items_permissions_check($request)
	{
		if (!API::is_pos()) {
			return new \WP_Error('pos_rest_gateways_cannot_view', 'Support only POS requests', [
				'status' => 400,
			]);
		}

		if (current_user_can('read_woocommerce_pos_gateways')) {
			return true;
		}

		return parent::get_items_permissions_check($request);
	}
}
