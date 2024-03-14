<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Controller;
use ZPOS\Model;

class Cart extends WC_REST_Controller
{
	protected $namespace = 'wc-pos';
	protected $rest_base = 'carts/(?P<cart_id>[\w-]+)';
	protected $cart_action_hook_name = 'pos_restore_product_quantity';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route($this->namespace, '/carts', [
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => [$this, 'create_cart'],
			'permission_callback' => [$this, 'get_permissions_check'],
		]);
		register_rest_route($this->namespace, '/' . $this->rest_base, [
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => [$this, 'add_product_to_cart'],
			'permission_callback' => [$this, 'get_permissions_check'],
		]);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/products/(?P<cart_item_id>[\w-]+)',
			[
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => [$this, 'change_product_quantity'],
				'permission_callback' => [$this, 'get_permissions_check'],
				'args' => [
					'quantity' => [
						'type' => 'integer',
					],
				],
			]
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/products/(?P<cart_item_id>[\w-]+)',
			[
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => [$this, 'remove_item_from_cart'],
				'permission_callback' => [$this, 'get_permissions_check'],
			]
		);
		register_rest_route($this->namespace, '/' . $this->rest_base, [
			'methods' => WP_REST_Server::DELETABLE,
			'callback' => [$this, 'clear_cart'],
			'permission_callback' => [$this, 'get_permissions_check'],
		]);
		register_rest_route($this->namespace, '/' . $this->rest_base, [
			'methods' => WP_REST_Server::EDITABLE,
			'callback' => [$this, 'update_cart'],
			'permission_callback' => [$this, 'get_permissions_check'],
		]);
	}

	public function create_cart(\WP_REST_Request $request)
	{
		$cart_id = uniqid();
		$product = $request['product'];
		$station_id = $request->get_header('X-POS');

		$response = Model\Cart::add_item($cart_id, $product, $station_id);
		$response['cartId'] = $cart_id;

		return $response;
	}

	public function add_product_to_cart(\WP_REST_Request $request)
	{
		$cart_id = $request['cart_id'];
		$product = $request['product'];
		$station_id = $request->get_header('X-POS');

		return Model\Cart::add_item($cart_id, $product, $station_id);
	}

	public function change_product_quantity(\WP_REST_Request $request)
	{
		$cart_id = $request['cart_id'];
		$cart_item_id = $request['cart_item_id'];
		$quantity = $request['quantity'];
		$station_id = $request->get_header('X-POS');

		return Model\Cart::change_product_quantity($cart_id, $cart_item_id, $quantity, $station_id);
	}

	public function remove_item_from_cart(\WP_REST_Request $request)
	{
		$cart_item_id = $request['cart_item_id'];
		$cart_id = $request['cart_id'];
		$station_id = $request->get_header('X-POS');

		return Model\Cart::remove_from_cart($cart_id, $cart_item_id, $station_id);
	}

	public function clear_cart(\WP_REST_Request $request)
	{
		$cart_id = $request['cart_id'];
		$restore_products = $request['restore_products'];
		$station_id = $request->get_header('X-POS');

		return Model\Cart::clear_cart($cart_id, $restore_products, $station_id);
	}

	public function update_cart(\WP_REST_Request $request)
	{
		$cart_id = $request['cart_id'];
		$station_id = $request->get_header('X-POS');

		return Model\Cart::restore_cart($cart_id, $station_id);
	}

	public function get_permissions_check()
	{
		return current_user_can('publish_shop_orders');
	}
}
