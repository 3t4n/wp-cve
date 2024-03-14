<?php

namespace ZPOS\API;

use WP_REST_Server;
use ZPOS\Structure\ProductIds;
use const ZPOS\REST_NAMESPACE;
use ZPOS\Structure\AddDefaultImage;
use ZPOS\Structure\ProductResponse;

class ProductVariations extends \WC_REST_Product_Variations_Controller
{
	use AddDefaultImage, ProductResponse, ProductIds;

	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'products/(?P<product_id>[\d]+)/variations';
	protected $post_type = 'product_variation';

	public function __construct()
	{
		parent::__construct();
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		add_filter(
			"woocommerce_rest_prepare_{$this->post_type}_object",
			[$this, 'add_default_images'],
			1000
		);
	}

	public function register_routes()
	{
		parent::register_routes();
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route($this->namespace, '/products/ids/variations', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_all_ids'],
			'permission_callback' => [$this, 'get_items_permissions_check'],
		]);
	}

	public function get_items($request)
	{
		$response = parent::get_items($request);
		$response = $this->apply_categories_to_items($response);

		$count_query = new \WP_Query();
		$count_query->query(['post_type' => ['product_variation', 'product']]);
		$total = $count_query->found_posts;

		$response->header('X-WP-Total-All', $total);

		return $response;
	}

	private function apply_categories_to_items(\WP_REST_Response $response): \WP_REST_Response
	{
		$items = $response->get_data();

		foreach ($items as $item_key => $item) {
			$items[$item_key]['categories'] = Products::get_prepared_categories($item['parent_id']);
		}

		$response->data = $items;

		return $response;
	}
}
