<?php

namespace ZPOS\API\Woocommerce;

use ZPOS\Station;

class Orders
{
	public function __construct()
	{
		add_filter('woocommerce_rest_orders_prepare_object_query', [$this, 'add_query_filters'], 10, 2);

		add_filter(
			'woocommerce_rest_prepare_shop_order_object',
			[$this, 'add_extra_data_to_response'],
			10,
			3
		);
	}

	public function add_query_filters(array $args, \WP_REST_Request $request): array
	{
		if (isset($request['pos_station'])) {
			return $this->add_query_filter_by_station($args, $request['pos_station']);
		} elseif (isset($request['pos_gateway'])) {
			return $this->add_query_filter_by_gateway($args, $request['pos_gateway']);
		} elseif (isset($request['pos_user'])) {
			return $this->add_query_filter_by_user($args, $request['pos_user']);
		}

		return $args;
	}

	private function add_query_filter_by_station(array $args, /* mixed */ $value): array
	{
		$args['meta_key'] = '_pos_by';

		if ('-1' === $value) {
			return $args;
		}

		$args['meta_value'] = intval($value);

		return $args;
	}

	private function add_query_filter_by_gateway(array $args, /* mixed */ $value): array
	{
		if ('-1' === $value) {
			return $args;
		}

		$args['meta_key'] = '_payment_method';
		$args['meta_value'] = strval($value);

		return $args;
	}

	private function add_query_filter_by_user(array $args, /* mixed */ $value): array
	{
		$args['author'] = intval($value);

		return $args;
	}

	public function add_extra_data_to_response(
		\WP_REST_Response $response,
		\WC_Data $object,
		\WP_REST_Request $request
	): \WP_REST_Response {
		if (isset($request['with_pos_station']) && '1' === $request['with_pos_station']) {
			$response = $this->add_station_to_response($response);
		}

		if (isset($request['with_pos_user']) && '1' === $request['with_pos_user']) {
			$response = $this->add_user_to_response($response);
		}

		return $response;
	}

	private function add_station_to_response(\WP_REST_Response $response): \WP_REST_Response
	{
		$station_id = get_post_meta($response->data['id'], '_pos_by', true);

		if (empty($station_id)) {
			$response->data['pos_station'] = [
				'posID' => '-1',
				'name' => esc_js(__('Web order', 'zpos-wp-api')),
			];

			return $response;
		}

		$station = get_post($station_id);

		if (!is_a($station, 'WP_Post')) {
			$response->data['pos_station'] = [
				'posID' => '-1',
				'name' => esc_js(__('Deleted station', 'zpos-wp-api')),
			];

			return $response;
		}

		$response->data['pos_station'] = [
			'posID' => $station->ID,
			'name' => esc_js($station->post_title),
		];

		return $response;
	}

	private function add_user_to_response(\WP_REST_Response $response): \WP_REST_Response
	{
		$user_id = get_post_field('post_author', $response->data['id']);
		$user_name = get_the_author_meta('display_name', $user_id);

		$response->data['pos_user'] = [
			'id' => $user_id,
			'name' => esc_js($user_name),
		];

		return $response;
	}
}
