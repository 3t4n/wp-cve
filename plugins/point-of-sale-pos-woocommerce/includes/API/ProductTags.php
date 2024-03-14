<?php

namespace ZPOS\API;

use const ZPOS\REST_NAMESPACE;

class ProductTags extends \WC_REST_Product_Tags_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function get_items_permissions_check($request)
	{
		$user = wp_get_current_user();
		if (in_array('cashier', $user->roles)) {
			return true;
		}
		return parent::get_item_permissions_check($request);
	}

	public function register_routes()
	{
		parent::register_routes();

		register_rest_route($this->namespace, '/' . $this->rest_base, [
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [$this, 'get_items'],
				'permission_callback' => [$this, 'get_items_permissions_check'],
				'args' => $this->get_collection_params(),
			],
			'schema' => [$this, 'get_public_item_schema'],
		]);
	}
}
