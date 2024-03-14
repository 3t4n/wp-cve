<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Controller;
use const ZPOS\REST_NAMESPACE;

class Groups extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'groups';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
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
		if (!class_exists('\ZAddons\Model\Group')) {
			return [];
		}
		$groups = $this->is_legacy() ? \ZAddons\Model\Group::getAll() : \ZAddons\Model\Group::get_all();
		return array_map(function (\ZAddons\Model\Group $group) {
			return $this->is_legacy() ? $group->getDataAPI() : $group->get_data_api();
		}, $groups);
	}

	public function get_all_ids()
	{
		if (!class_exists('\ZAddons\Model\Group')) {
			return [];
		}
		$groups = $this->is_legacy() ? \ZAddons\Model\Group::getAll() : \ZAddons\Model\Group::get_all();
		return array_map(function (\ZAddons\Model\Group $group) {
			return $this->is_legacy() ? $group->getID() : $group->get_id();
		}, $groups);
	}

	public function get_item($request)
	{
		$id = $request['id'];

		return $this->is_legacy()
			? \ZAddons\Model\Group::getByID($id)->getDataAPI()
			: \ZAddons\Model\Group::get_by_id($id)->get_data_api();
	}

	public function permissionCheck()
	{
		return is_user_logged_in();
	}

	public function is_legacy()
	{
		return !function_exists('\ZAddons\app');
	}
}
