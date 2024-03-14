<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class User_Resource
 * @package Vimeotheque
 * @link https://developer.vimeo.com/api/reference/videos#get_videos
 * @ignore
 */
class User_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * User_Resource constructor.
	 *
	 * @param bool $user_id
	 * @param array $params
	 */
	public function __construct( $user_id = false, $params = [] ) {
		parent::__construct( false, $user_id, $params );

		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'filter_embeddable' => false,
			'filter_playable' => false,
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'sort' => 'date'
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'default',
				'duration',
				'last_user_action_event_date',
				'likes',
				'modified_time',
				'plays'
			]
		);

		parent::set_filtering_options([
			'app_only',
			'embeddable',
			'featured',
			'playable',
		]);

		parent::set_name( 'user', __( 'User uploads', 'codeflavors-vimeo-video-post-lite' ) );
	}

	/**
	 * Can import newly added videos after importing the entire feed
	 *
	 * @return bool
	 */
	public function can_import_new_videos() {
		return true;
	}

	/**
	 * Feed can use date limit
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return true;
	}

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		return sprintf( 'users/%s/videos', $this->resource_id );
	}


}