<?php

namespace ZPOS\API;

use WP_REST_Server;
use WC_REST_Orders_Controller;
use ZAddons\Product;
use ZPOS\Admin\Setting\PostTab;
use ZPOS\Gateway\SplitPayment;
use ZPOS\Model\Cart;
use const ZPOS\REST_NAMESPACE;

class Orders extends WC_REST_Orders_Controller
{
	protected $namespace = REST_NAMESPACE;

	/* hack to get $order object for calculate taxes (see store_order and unstore_order methods), used in calculate_taxes */
	protected $current_order = null;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		add_filter(
			"woocommerce_rest_pre_insert_{$this->post_type}_object",
			[$this, 'insert_order'],
			10,
			2
		);
		add_filter(
			"woocommerce_rest_insert_{$this->post_type}_object",
			[$this, 'payment_order'],
			10,
			2
		);
		add_filter("woocommerce_rest_insert_{$this->post_type}_object", [$this, 'insert_author_order']);

		add_action('woocommerce_order_before_calculate_totals', [$this, 'store_order'], 10, 2);
		add_action('woocommerce_order_after_calculate_totals', [$this, 'unstore_order']);
		add_action('woocommerce_order_item_after_calculate_taxes', [$this, 'calculate_taxes']);
		add_action('woocommerce_order_item_shipping_after_calculate_taxes', [
			$this,
			'calculate_shipping_taxes',
		]);
		add_action(
			'woocommerce_order_after_calculate_totals',
			function ($and_taxes, $order) {
				if ($tip = $order->get_meta('pos-tip')) {
					$order->set_total($tip + $order->get_total());
				}
			},
			10,
			2
		);
		add_filter(
			"woocommerce_rest_prepare_{$this->post_type}_object",
			[$this, 'prepare_orders'],
			10,
			3
		);
	}

	public function register_routes()
	{
		parent::register_routes();
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/email', [
			'args' => [
				'id' => [
					'description' => __('Unique identifier for the resource.', 'woocommerce'),
					'type' => 'integer',
					'required' => true,
				],
			],
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'send_email'],
				'permission_callback' => [$this, 'permission_check'],
				'args' => [
					'email' => [
						'type' => 'email',
						'required' => true,
					],
					'update_billing_email' => [
						'type' => 'boolean',
						'required' => false,
					],
				],
			],
		]);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)/base_payment_page',
			[
				'args' => [
					'id' => [
						'description' => __('Unique identifier for the resource.', 'woocommerce'),
						'type' => 'integer',
						'required' => true,
					],
				],
				[
					'methods' => WP_REST_Server::READABLE,
					'callback' => [$this, 'get_base_payment_page_url'],
					'permission_callback' => '__return_true',
				],
			]
		);

		if (class_exists('\Zprint\Model\Location')) {
			register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/print', [
				'args' => [
					'id' => [
						'description' => __('Unique identifier for the resource.', 'woocommerce'),
						'type' => 'integer',
						'required' => true,
					],
				],
				[
					'methods' => WP_REST_Server::CREATABLE,
					'callback' => [$this, 'print_order'],
					'permission_callback' => [$this, 'permission_check'],
					'args' => [
						'location' => [
							'type' => 'array',
							'required' => true,
						],
					],
				],
			]);
		}
	}

	public function send_email(\WP_REST_Request $request)
	{
		$url_params = $request->get_url_params();
		$json_params = $request->get_json_params();

		$order = $url_params['id'];
		$email = $json_params['email'];
		$update_billing_email = filter_var(
			$json_params['update_billing_email'],
			FILTER_VALIDATE_BOOLEAN
		);

		$order = new \WC_Order($order);

		if (!$order || 0 === $order->get_id()) {
			return new \WP_Error(
				"woocommerce_rest_{$this->post_type}_invalid_id",
				__('Invalid ID.', 'woocommerce'),
				['status' => 400]
			);
		}
		if (!is_email($email)) {
			return new \WP_Error(
				"woocommerce_rest_{$this->post_type}_invalid_email",
				__('Invalid Email.', 'woocommerce'),
				['status' => 400]
			);
		}

		if ($update_billing_email) {
			$order->update_meta_data('_billing_email', $email);
			$order->save();
		}

		$status = apply_filters('zpos_receipt_email', $order, $email);

		return ['success' => $status];
	}

	public function get_base_payment_page_url(\WP_REST_Request $request)
	{
		$url_params = $request->get_url_params();
		$order = wc_get_order($url_params['id']);

		return [
			'url' => add_query_arg(['pay_for_order' => 'true'], $order->get_checkout_payment_url(true)),
		];
	}

	public function insert_author_order(\WC_Order $order)
	{
		if ($order->get_meta('_pos_by')) {
			$current_user = wp_get_current_user();
			self::setUser($order, $current_user);
		}
	}

	public static function setUser($order, $user)
	{
		if (!$order instanceof \WC_Order) {
			$order = wc_get_order($order);
		}
		wp_update_post([
			'ID' => $order->get_id(),
			'post_author' => $user->ID,
		]);
		// pos meta
		$order->update_meta_data('_pos_user', $user->ID);
		$order->update_meta_data('_pos_user_name', $user->user_firstname . ' ' . $user->user_lastname);
	}

	public function insert_order(\WC_Order $order, $request)
	{
		$json_params = $request->get_json_params();
		$station_id = $order->get_meta('_pos_by');

		if (PostTab::getValue('pos_inventory_management', $station_id) === 'block') {
			Cart::delete_scheduled_hook($json_params['cart_id']);
		}

		if ($json_params['status']) {
			$order->set_status($json_params['status']);
		}
		if ($json_params['set_paid']) {
			$order->set_date_paid(current_time('mysql'));
		}

		if (!$order->has_status('completed')) {
			return $order;
		}

		if (class_exists(Product::class)) {
			array_map(function ($item) {
				/* @var $item \WC_Order_item */
				$this->add_zaddon_meta($item);
			}, $order->get_items());
		}

		return $order;
	}

	public function payment_order(\WC_Order $order, $request): \WC_Order
	{
		$json_params = $request->get_json_params();

		if (
			(SplitPayment::is_split_payment($json_params) &&
				SplitPayment::is_pending_split_payment($order)) ||
			!($json_params['set_paid'] && $json_params['status'] === 'completed')
		) {
			$gateways = WC()->payment_gateways->payment_gateways();

			$gateway = array_reduce($gateways, function ($result, $gateway) use ($order) {
				return $order->get_payment_method() === $gateway->id ? $gateway : $result;
			});

			if ($gateway) {
				$gateway->process_payment($order);
			}
		}

		return $order;
	}

	public function add_zaddon_meta(\WC_Order_item $item)
	{
		Product::add_meta_to_item(
			$item->get_product_id(),
			$item->get_meta('_zaddon_values'),
			(int) $item->get_meta('_zaddon_additional'),
			$item
		);
	}

	public function print_order(\WP_REST_Request $request)
	{
		$url_params = $request->get_url_params();
		$json_params = $request->get_json_params();

		$order = $url_params['id'];
		$order = new \WC_Order($order);

		if (!$order || 0 === $order->get_id()) {
			return new \WP_Error(
				"woocommerce_rest_{$this->post_type}_invalid_id",
				__('Invalid ID.', 'woocommerce'),
				['status' => 400]
			);
		}

		$location = array_map('intval', $json_params['location']);
		try {
			$location = array_map(function ($id) {
				return new \Zprint\Model\Location($id);
			}, $location);
		} catch (\Zprint\Exception\DB $exception) {
			return new \WP_Error(
				"woocommerce_rest_{$this->post_type}_invalid_location",
				__('Invalid Locations.', 'woocommerce'),
				['status' => 400]
			);
		}

		\Zprint\Printer::reprintOrder($order, $location);

		return ['success' => true];
	}

	public function calculate_taxes($order_item)
	{
		/* @var $order_item \WC_Order_Item_Product */
		/* @var $order \WC_Order */

		$order = $this->current_order;

		$tax_status = $order_item->get_meta('_pos_tax_status');
		$order_item->delete_meta_data('_pos_tax_status');

		$pos = $order->get_meta('_pos_by');

		if ($pos === null) {
			return;
		}

		$taxes_enabled = $order->get_meta('_pos-taxes-enabled');

		if (
			get_option('pos_tax_enabled') === 'off' ||
			'none' === $tax_status ||
			('' !== $taxes_enabled && !$taxes_enabled)
		) {
			$order_item->set_taxes([]);

			return;
		}

		if (!$this->is_taxable($order_item)) {
			return;
		}

		$tax_rates = Taxes::get_current_taxes_rates($order, $order_item->get_tax_class(), $pos);

		$taxes = \WC_Tax::calc_tax($order_item->get_total(), $tax_rates, false);

		if (method_exists($order_item, 'get_subtotal')) {
			$subtotal_taxes = \WC_Tax::calc_tax($order_item->get_subtotal(), $tax_rates, false);
			$order_item->set_taxes([
				'total' => $taxes,
				'subtotal' => $subtotal_taxes,
			]);
		} else {
			$order_item->set_taxes(['total' => $taxes]);
		}
	}

	public function calculate_shipping_taxes($order_item)
	{
		/* @var $order_item \WC_Order_Item_Shipping */
		/* @var $order \WC_Order */

		$order = $this->current_order;

		$shipping_tax_status = $order_item->get_meta('_pos_shipping_tax_status');
		$order_item->delete_meta_data('_pos_shipping_tax_status');

		$pos = $order->get_meta('_pos_by');

		if ($pos === null) {
			return;
		}

		$taxes_enabled = $order->get_meta('_pos-taxes-enabled');

		if (get_option('pos_tax_enabled') === 'off' || ('' !== $taxes_enabled && !$taxes_enabled)) {
			$order_item->set_taxes([]);

			return;
		}

		if (!$this->is_taxable($order_item)) {
			return;
		}

		if ('none' === $shipping_tax_status) {
			$order_item->set_taxes([]);
		}
	}

	public function store_order($and_taxes, $order)
	{
		$this->current_order = $order;
	}

	public function unstore_order()
	{
		$this->current_order = null;
	}

	private function is_taxable($order_item)
	{
		return '0' !== $order_item->get_tax_class() &&
			'taxable' === $order_item->get_tax_status() &&
			wc_tax_enabled();
	}

	public function permission_check()
	{
		return is_user_logged_in();
	}

	public function prepare_orders($response)
	{
		if (empty($response->data)) {
			return $response;
		}

		$order_data = $response->get_data();

		$user = get_userdata(intval($order_data['customer_id']));
		$order_data['customer_full_name'] = $user
			? $user->first_name . ' ' . $user->last_name
			: __('Guest', 'zpos-wp-api');

		foreach ($order_data['line_items'] as $key => $item) {
			$product = wc_get_product($item['product_id']);

			$order_data['line_items'][$key]['stock_quantity'] = $product
				? $product->get_stock_quantity()
				: null;

			$order_data['line_items'][$key]['_categories'] = Products::get_prepared_categories(
				$item['product_id']
			);
		}

		$response->data = $order_data;

		return $response;
	}
}
