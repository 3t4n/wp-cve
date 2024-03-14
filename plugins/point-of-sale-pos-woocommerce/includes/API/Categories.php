<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Product_Categories_Controller, ZPOS\Admin\Woocommerce;
use const ZPOS\REST_NAMESPACE;
use ZPOS\Structure\AddDefaultImage;

class Categories extends WC_REST_Product_Categories_Controller
{
	use AddDefaultImage;

	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		add_filter("woocommerce_rest_prepare_{$this->taxonomy}", [$this, 'add_default_image'], 1000);
		add_filter("woocommerce_rest_prepare_{$this->taxonomy}", [$this, 'add_stylization'], 1000);
		add_filter("woocommerce_rest_prepare_{$this->taxonomy}", [$this, 'normalize_slug']);
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
	}

	public function get_all_ids()
	{
		return array_map(function ($term) {
			return $term->term_id;
		}, get_terms($this->taxonomy));
	}

	public function add_stylization(\WP_REST_Response $response)
	{
		$response->data['stylization'] = Woocommerce\Categories::get_stylization($response->data['id']);
		return $response;
	}

	public function get_items_permissions_check($request)
	{
		if (current_user_can('read_woocommerce_pos_categories')) {
			return true;
		}

		return parent::get_items_permissions_check($request);
	}

	public function normalize_slug(\WP_REST_Response $response)
	{
		$response->data['slug'] = Woocommerce\Categories::normalize_slug($response->data['slug']);

		return $response;
	}
}
