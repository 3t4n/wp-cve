<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Category_Resource
 * @package Vimeotheque
 * @link https://developer.vimeo.com/api/reference/categories
 * @ignore
 */
class Category_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Category_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $params = [] ) {
		parent::__construct( $resource_id, false, $params );

		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'filter_embeddable' => false,
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
				'duration',
				'featured',
				'likes',
				'plays',
				'relevant'
			]
		);

		parent::set_filtering_options([
			'conditional_featured',
			'embeddable'
		]);

		parent::set_name( 'category', __( 'Category', 'codeflavors-vimeo-video-post-lite' ) );
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
	 * @return string
	 */
	public function get_api_endpoint() {
		return sprintf( 'categories/%s/videos', $this->resource_id );
	}

	/**
	 * Searching within the returned results isn't allowed by API
	 *
	 * @return bool
	 */
	public function can_search_results() {
		return false;
	}
}