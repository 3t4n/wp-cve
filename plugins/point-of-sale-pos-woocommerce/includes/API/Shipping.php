<?php

namespace ZPOS\API;

use WC_REST_Controller;
use WP_REST_Server;
use const ZPOS\REST_NAMESPACE;

class Shipping extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'shipping';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route($this->namespace, '/' . $this->rest_base, [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_shipping_methods'],
			'permission_callback' => [$this, 'permission_check'],
			'args' => [
				'country' => [
					'type' => 'string',
				],
				'state' => [
					'type' => 'string',
				],
				'postcode' => [
					'type' => 'string',
				],
			],
		]);
	}

	public function get_shipping_methods($request)
	{
		$zone = \WC_Shipping_Zones::get_zone_matching_package([
			'destination' => $request->get_params(),
		]);

		$methods = $zone->get_shipping_methods(true);

		return $this->prepare_methods($methods);
	}

	private function prepare_methods($methods)
	{
		$response = [];
		foreach ($methods as $id => $method) {
			$response[] = [
				'id' => $id,
				'rate_id' => $method->get_rate_id(),
				'title' => $method->get_title(),
				'cost' => $method->cost ?? '',
				'tax_status' => $method->tax_status ?? '',
			];
		}

		return $response;
	}

	public function permission_check()
	{
		return is_user_logged_in();
	}
}
