<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Album_Resource
 * @package Vimeotheque
 * @link https://developer.vimeo.com/api/reference/showcases
 * @ignore
 */
class Album_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Album_Resource constructor.
	 *
	 * @param $resource_id
	 * @param bool $user_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $user_id = false, $params = [] ) {
		// built without direction
		$default_params = [
			'filter' => '',
			'filter_embeddable' => false,
			'page' => 1,
			'password' => '',
			'per_page' => 20,
			'query' => '',
			'sort' => 'modified_time' // "modified_time" sorts by the date the video was added to album
		];

		// when sort is default, direction must be eliminated
		if( isset( $params['sort'] ) && 'default' == $params['sort'] ){
			unset( $params['direction'] );
		}else{
			$default_params['direction'] = 'desc';
		}

		parent::__construct( $resource_id, $user_id, $params );

		parent::set_default_params( $default_params );

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'default',
				'duration',
				'likes',
				'manual',
				'modified_time',
				'plays'
			]
		);

		parent::set_filtering_options([
			'embeddable'
		]);

		parent::set_name( 'album', __( 'Showcase/Album', 'codeflavors-vimeo-video-post-lite' ) );

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
	 * Allows import limiting by date
	 *
	 * @return bool
	 */
	public function has_date_limit() {
		return true;
	}

	/**
	 * @return string
	 */
	public function get_api_endpoint() {
		return sprintf(
			'users/%s/albums/%s/videos',
			$this->user_id,
			$this->resource_id
		);
	}

	/**
	 * @see Resource_Interface::requires_user_id()
	 *
	 * @return bool
	 */
	public function requires_user_id() {
		return true;
	}

	/**
	 * @see Resource_Interface::label_user_id()
	 *
	 * @return bool|string|void
	 */
	public function label_user_id() {
		return __( 'Album user ID', 'codeflavors-vimeo-video-post-lite' );
	}

	/**
	 * @see Resource_Interface::placeholder_user_id()
	 *
	 * @return bool|string|void
	 */
	public function placeholder_user_id() {
		return __( 'Album owner user ID' );
	}
}