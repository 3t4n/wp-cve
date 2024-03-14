<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video_Resource
 * @package Vimeotheque
 * @ignore
 */
class Video_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Video_Resource constructor.
	 *
	 * @param $resource_id
	 */
	public function __construct( $resource_id ) {
		parent::__construct( $resource_id, false, false );

		parent::set_name( 'video', __( 'Video', 'codeflavors-vimeo-video-post-lite' ) );
	}

	/**
	 * @return bool
	 */
	public function is_single_entry(){
		return true;
	}

	/**
	 * No automatic import
	 *
	 * @return bool
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
		return sprintf( 'videos/%s', $this->resource_id );
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