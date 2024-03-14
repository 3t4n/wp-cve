<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Coupons_Controller;
use const ZPOS\REST_NAMESPACE;

class Coupons extends WC_REST_Coupons_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'coupons';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		parent::register_routes();
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route($this->namespace, '/' . $this->rest_base . '/ids', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_all_ids'],
			'permission_callback' => [$this, 'get_items_permissions_check'],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<code>[\w]+)', [
			'args' => [
				'code' => [
					'description' => __('Unique identifier for the resource.', 'woocommerce'),
					'type' => 'string',
				],
			],
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [$this, 'get_single_item'],
				'permission_callback' => [$this, 'get_single_item_permissions_check'],
				'args' => [
					'context' => $this->get_context_param(['default' => 'view']),
				],
			],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/validate', [
			'args' => [
				'user_id' => [
					'type' => 'number',
				],
			],
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [$this, 'validate_coupon'],
				'permission_callback' => '__return_true',
			],
		]);
	}

	public function get_all_ids()
	{
		$args = [
			'post_type' => [$this->post_type],
			'post_status' => ['publish'],
			'posts_per_page' => -1,
			'fields' => 'ids',
			'order' => isset($filter['order']) ? $filter['order'] : 'ASC',
			'orderby' => isset($filter['orderby']) ? $filter['orderby'] : 'title',
		];

		if (isset($filter['updated_at_min'])) {
			$args['date_query'][] = [
				'column' => 'post_modified',
				'after' => $filter['updated_at_min'],
				'inclusive' => false,
			];
		}

		$query = new \WP_Query($args);
		return rest_ensure_response($query->posts);
	}

	public function get_single_item($request)
	{
		$object = $this->get_object($request['code']);

		if (!$object || 0 === $object->get_id()) {
			return new \WP_Error(
				"woocommerce_rest_{$this->post_type}_invalid_code",
				__('Invalid Coupon Code.', 'woocommerce'),
				['status' => 404]
			);
		}

		$data = $this->prepare_object_for_response($object, $request);
		$response = rest_ensure_response($data);

		if ($this->public) {
			$response->link_header('alternate', $this->get_permalink($object), [
				'type' => 'text/html',
			]);
		}

		return $response;
	}

	public function prepare_object_for_response($object, $request)
	{
		$data = parent::prepare_object_for_response($object, $request);
		$coupon_data = $data->get_data();
		$coupon = new \WC_Coupon($coupon_data['id']);
		$data->data['amount'] = wc_format_decimal($coupon->get_amount(), '');
		return $data;
	}

	public function get_single_item_permissions_check()
	{
		return current_user_can('read_woocommerce_pos_single_coupons');
	}

	public function prepare_objects_query($request)
	{
		$args = parent::prepare_objects_query($request);

		$meta_query = [];
		if (isset($request['pos'])) {
			$meta_query[] = [
				'key' => '_pos',
				'value' => 'true',
			];
		}

		if (isset($request['type'])) {
			$meta_query[] = [
				'key' => 'discount_type',
				'value' => $request['type'],
			];
		}

		if (isset($request['amount'])) {
			$meta_query[] = [
				'key' => 'coupon_amount',
				'value' => $request['amount'],
			];
		}

		if (count($meta_query) > 0) {
			$args['meta_query'] = $meta_query;
		}

		return $args;
	}

	public function validate_coupon($request)
	{
		$coupon_id = $request['id'];
		$user_id = $request['user_id'];
		$coupon = new \WC_Coupon($coupon_id);

		if (
			$coupon &&
			$user_id &&
			apply_filters(
				'woocommerce_coupon_validate_user_usage_limit',
				$coupon->get_usage_limit_per_user() > 0,
				$user_id,
				$coupon,
				$this
			)
		) {
			$data_store = $coupon->get_data_store();
			$usage_count = $data_store->get_usage_by_user_id($coupon, $user_id);
			if ($usage_count >= $coupon->get_usage_limit_per_user()) {
				return ['isValid' => false];
			}
		}

		return ['isValid' => true];
	}
}
