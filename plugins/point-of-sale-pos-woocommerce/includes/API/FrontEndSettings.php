<?php

namespace ZPOS\API;

use WC_REST_Controller;
use WP_REST_Server;
use ZPOS\Admin\Stations\Post;
use ZPOS\Frontend;
use ZPOS\Station;
use const ZPOS\REST_NAMESPACE;

class FrontEndSettings extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'front_end_settings';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		register_rest_route($this->namespace, '/' . $this->rest_base, [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_front_end_settings'],
			'args' => [
				'stationSlug' => [
					'type' => 'string',
					'required' => true,
				],
			],
			'permission_callback' => [$this, 'permission_check'],
		]);
	}

	public function get_front_end_settings(\WP_REST_Request $request)
	{
		$query = new \WP_Query([
			'post_type' => Post::TYPE,
			'meta_query' => [
				[
					'key' => Station::CLOUD_POS_STATION_SLUG_META_FIELD,
					'value' => $request['stationSlug'],
				],
			],
			'fields' => 'ids',
		]);

		$posts = $query->posts;

		// todo: filter response, leave only needed params, add error handling
		if ($posts && isset($posts[0])) {
			$station = new Station($posts[0]);
		} else {
			$station = new Station($request['stationSlug']);
		}

		return Frontend::getPOSSettings($station);
	}

	public function permission_check()
	{
		return is_user_logged_in();
	}
}
