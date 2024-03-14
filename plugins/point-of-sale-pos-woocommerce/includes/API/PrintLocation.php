<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Controller;
use Zprint\Exception\DB as ExceptionDB;
use Zprint\Model\Location;
use const ZPOS\REST_NAMESPACE;

class PrintLocation extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'print_location';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		if (!class_exists(Location::class)) {
			return null;
		}
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route($this->namespace, '/' . $this->rest_base . '/', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_all'],
			'permission_callback' => [$this, 'permissionCheck'],
		]);
		register_rest_route($this->namespace, '/' . $this->rest_base . '/ids', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_all_ids'],
			'permission_callback' => [$this, 'permissionCheck'],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_item'],
			'permission_callback' => [$this, 'permissionCheck'],
		]);
	}

	public function get_all()
	{
		$locations = Location::getAll();

		if (!$locations) {
			return [];
		}

		$values = array_values($locations);
		$values = array_map([$this, 'prepare_item'], $values);
		return $values;
	}

	public function get_all_ids()
	{
		$locations = Location::getAll();

		if (!$locations) {
			return [];
		}

		return array_map(function (Location $location) {
			return $location->getID();
		}, $locations);
	}

	public function get_item($request)
	{
		$id = $request['id'];

		try {
			$location = new Location($id);
		} catch (ExceptionDB $exception) {
			if ($exception->getCode() === 404) {
				return new \WP_Error(
					'woocommerce_rest_print_location_invalid_id',
					__('Invalid ID.', 'woocommerce'),
					['status' => 400]
				);
			}
			return new \WP_Error('woocommerce_rest_print_location_error_request', 'Error', [
				'status' => 500,
			]);
		}

		return $this->prepare_item($location);
	}

	private function prepare_item(Location $item)
	{
		return [
			'id' => $item->getID(),
			'name' => $item->title,
		];
	}

	public function permissionCheck()
	{
		return is_user_logged_in();
	}
}
