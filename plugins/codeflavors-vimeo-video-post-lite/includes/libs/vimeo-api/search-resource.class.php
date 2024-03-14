<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Search_Resource
 * https://developer.vimeo.com/api/reference/videos#search_videos
 *
 * @package Vimeotheque
 * @ignore
 */
class Search_Resource extends Resource_Abstract implements Resource_Interface {
	/**
	 * Search_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $params = [] ) {

		// search uses field "query" instead of resource id; set it
		$params['query'] = $resource_id;

		parent::__construct( false, false, $params );

		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'links' => '',
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'sort' => 'date',
			'uris' => ''
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'duration',
				'likes',
				'plays',
				'relevant'
			]
		);

		parent::set_filtering_options([
			'CC',
			'CC-BY',
			'CC-BY-NC',
			'CC-BY-NC-ND',
			'CC-BY-NC-SA',
			'CC-BY-ND',
			'CC-BY-SA',
			'CC0',
			'categories',
			'duration',
			'in-progress',
			'minimum_likes',
			'trending',
			'upload_date'
		]);

		parent::set_name( 'search', __( 'Search', 'codeflavors-vimeo-video-post-lite' ) );

	}

	public function set_resource_id( $resource_id ) {
		$this->params['query'] = $resource_id;
		parent::set_resource_id( $resource_id );
	}

	public function set_params( $params ) {
		if( isset( $this->params['query'] ) ){
			$params['query'] = $this->params['query'];
		}

		parent::set_params( $params );
	}

	/**
	 * Does not have automatic import
	 *
	 * @return boolean
	 */
	public function has_automatic_import() {
		return false;
	}

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		return 'videos';
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