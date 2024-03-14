<?php

namespace ZPOS\API;

use WC_REST_Controller;
use WP_REST_Server;
use ZPOS\Admin\Stations\Post;
use ZPOS\Station;
use const ZPOS\REST_NAMESPACE;

class Stations extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'stations';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		register_rest_route($this->namespace, '/' . $this->rest_base, [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_all_stations'],
			'permission_callback' => [$this, 'permission_check'],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/update-slug', [
			'methods' => WP_REST_Server::EDITABLE,
			'callback' => [$this, 'update_station_slug'],
			'args' => [
				'stationId' => [
					'type' => 'integer',
					'required' => true,
				],
				'stationSlug' => [
					'type' => 'string',
					'required' => true,
				],
			],
			'permission_callback' => [$this, 'permission_check'],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/shared', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_all_shared_stations'],
			'args' => [
				'userEmail' => [
					'type' => 'string',
					'required' => true,
				],
			],
			'permission_callback' => [$this, 'permission_check'],
		]);
	}

	public function get_all_stations()
	{
		$stations = get_posts([
			'numberposts' => -1,
			'post_type' => Post::TYPE,
		]);

		return array_map(function ($station) {
			return [
				'posID' => $station->ID,
				'name' => esc_js($station->post_title),
				'cloudStationSlug' => Station::getCloudAppStationSlug($station->ID) ?: $station->ID,
				'access' => current_user_can('access_woocommerce_pos', $station->ID),
			];
		}, $stations);
	}

	public function update_station_slug(\WP_REST_Request $request)
	{
		// todo: add validation and some transforming for the station slug, for security
		return Station::setCloudAppStationSlug($request['stationId'], $request['stationSlug']);
	}

	public function get_all_shared_stations(\WP_REST_Request $request)
	{
		$user = get_user_by('email', $request->get_param('userEmail'));

		return [
			'adminName' => wp_get_current_user()->display_name,
			'currentUserEmail' => wp_get_current_user()->user_email,
			'roles' => $user->roles,
			'stations' => $this->get_all_stations(),
		];
	}

	public function permission_check()
	{
		return is_user_logged_in();
	}
}
